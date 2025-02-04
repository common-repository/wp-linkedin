<?php
/*
Plugin Name: WP LinkedIn
Plugin URI: http://vdvn.me/pga
Description: This plugin enables you to add various part of your LinkedIn profile to your Wordpress blog.
Author: Claude Vedovini
Author URI: http://vdvn.me/
Version: 2.9.1
Text Domain: wp-linkedin
Domain Path: /languages
Network: True

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/

define('WP_LINKEDIN_VERSION', '2.9.1');
define('WP_LINKEDIN_PLUGIN_BASENAME', plugin_basename(__FILE__));

if (!defined('LI_DEBUG')) {
	define('LI_DEBUG', WP_DEBUG);
}

if (!defined('LINKEDIN_FIELDS_RECOMMENDATIONS')) {
	define('LINKEDIN_FIELDS_RECOMMENDATIONS', 'recommendations-received:(recommendation-text,recommender:(first-name,last-name,public-profile-url))');
}

define('LINKEDIN_FIELDS_BASIC', 'id, first-name, last-name, picture-url, headline, location, industry, public-profile-url');
define('LINKEDIN_FIELDS_DEFAULT', 'summary, positions');
define('LINKEDIN_FIELDS', get_option('wp-linkedin_fields', LINKEDIN_FIELDS_DEFAULT));
define('LINKEDIN_PROFILELANGUAGE', get_option('wp-linkedin_profilelanguage'));
define('LINKEDIN_SENDMAIL_ON_TOKEN_EXPIRY', get_option('wp-linkedin_sendmail_on_token_expiry', false));
if (is_multisite()) {
	define('LINKEDIN_SSL_VERIFYPEER', get_site_option('wp-linkedin_ssl_verifypeer', true));
	define('LINKEDIN_REDIRECT_THROUGH_NETWORK', get_site_option('wp-linkedin_redirect_through_network', true));
} else {
	define('LINKEDIN_SSL_VERIFYPEER', get_option('wp-linkedin_ssl_verifypeer', true));
	define('LINKEDIN_REDIRECT_THROUGH_NETWORK', false);
}
define('LINKEDIN_FULL_PROFILE', get_option('wp-linkedin_full_profile', false));

if (!class_exists('VDVNPluginUpdater')) {
	include 'lib/class-plugin-updater.php';
}

include 'class-linkedin-connection.php';
include 'class-recommendations-widget.php';
include 'class-card-widget.php';
require_once 'class-admin-pages.php';

add_action('plugins_loaded', array('WPLinkedInPlugin', 'get_instance'));

class WPLinkedInPlugin {

	private static $instance;

	public static function get_instance() {
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function __construct() {
		// Make plugin available for translation
		// Translations can be filed in the /languages/ directory
		add_filter('load_textdomain_mofile', array(&$this, 'smarter_load_textdomain'), 10, 2);
		load_plugin_textdomain('wp-linkedin', false, dirname(plugin_basename(__FILE__)) . '/languages/' );

		register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
		add_action('init', array(&$this, 'init'));
		add_action('widgets_init', array(&$this, 'widgets_init'));

		$this->admin = new WPLinkedInAdmin($this);
		$this->network_admin = new WPLinkedInNetworkAdmin($this);
	}

	function init() {
		wp_register_script('jquery.tools', plugins_url('jquery.tools.min.js', __FILE__), array('jquery'), '1.2.7', true);
		wp_register_script('responsive-scrollable', plugins_url('responsive-scrollable.js', __FILE__), array('jquery.tools'), WP_LINKEDIN_VERSION, true);
		wp_register_style('wp-linkedin', plugins_url('style.css', __FILE__), false, WP_LINKEDIN_VERSION);
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
		add_filter('removable_query_args', array(&$this, 'removable_query_args'));

		add_shortcode('li_recommendations', 'wp_linkedin_recommendations');
		add_shortcode('li_profile', 'wp_linkedin_profile');
		add_shortcode('li_card', 'wp_linkedin_card');
		add_shortcode('li_updates', 'wp_linkedin_updates');
		add_shortcode('li_picture', 'wp_linkedin_picture');
		add_shortcode('li_profile_field', 'wp_linkedin_profile_field');

		$post_types = $this->get_post_types();
		if (!empty($post_types)) {
			add_filter('the_content', array(&$this, 'filter_content'), 1);
		}

		$this->setup_rewrite_rules();
	}

	function setup_rewrite_rules() {
		if (isset($_GET['action']) && $_GET['action'] == 'deactivate' &&
				$_GET['plugin'] == plugin_basename(__FILE__)) {
			// Don't do anything if we're deactivating this plugin
			return;
		}

		add_rewrite_rule('oauth/linkedin/?$', 'index.php?oauth=linkedin', 'top');
		add_filter('query_vars', array(&$this, 'query_vars'));
		add_action('template_redirect', array(&$this, 'template_redirect'));

		$rules = get_option('rewrite_rules');
		if (!isset($rules['oauth/linkedin/?$'])) {
			flush_rewrite_rules();
		}
	}

	function query_vars($vars) {
		$vars[] = 'oauth';
		$vars[] = 'code';
		$vars[] = 'state';
		$vars[] = 'r';
		return $vars;
	}

	function template_redirect() {
		if (get_query_var('oauth') == 'linkedin') {
			$linkedin = wp_linkedin_connection();
			$linkedin->process_authorization(get_query_var('code'),
					get_query_var('state'), get_query_var('r'));
			exit;
		}
	}

	function smarter_load_textdomain($mofile, $domain) {
		if ($domain == 'wp-linkedin' && !is_readable($mofile)) {
			extract(pathinfo($mofile));
			$pos = strrpos($filename, '_');

			if ($pos !== false) {
				# cut off the locale part, leaving the language part only
				$filename = substr($filename, 0, $pos);
				$mofile = $dirname . '/' . $filename . '.' . $extension;
			}
		}

		return $mofile;
	}

	function get_post_types() {
		$post_types = get_option('wp-linkedin_add_card_to_content', array());

		if (!is_array($post_types)) {
			$post_types = ($post_types) ? array('post') : array();
		}

		return $post_types;
	}

	function filter_content($content) {
		if (is_singular($this->get_post_types())) {
			$content .= wp_linkedin_card(array('summary_length' => 2000));
		}

		return $content;
	}

	function widgets_init() {
		register_widget('WP_LinkedIn_Recommendations_Widget');
		register_widget('WP_LinkedIn_Card_Widget');
	}

	function enqueue_scripts() {
		wp_enqueue_style('wp-linkedin');
	}

	function removable_query_args($removable_query_args) {
		$removable_query_args[] = 'clear_cache';
		$removable_query_args[] = 'cache_cleared';
		$removable_query_args[] = 'oauth_status';
		$removable_query_args[] = 'oauth_message';
		return $removable_query_args;
	}
}


function wp_linkedin_message($message, $level='debug', $echo=true) {
	if (is_wp_error($message)) {
		$message = $message->get_error_message();
	}
	
	if (LI_DEBUG) {
		$level = strtolower($level);
		$message = "<p class='$level'>$message</p>";
	} else {
		$level = strtoupper($level);
		$message = "<!-- $level: $message -->";
	}
	
	if ($echo) echo $message;
	return $message;
}

function wp_linkedin_error($message) {
	return wp_linkedin_message($message, 'error', false);
}


function wp_linkedin_get_profile($options='id', $lang=LINKEDIN_PROFILELANGUAGE) {
	$linkedin = wp_linkedin_connection();
	return ($linkedin !== false) ? $linkedin->get_profile($options, $lang) : false;
}


function wp_linkedin_get_network_updates($count=50, $only_self=true) {
	$linkedin = wp_linkedin_connection();
	return ($linkedin !== false) ? $linkedin->get_network_updates($count, $only_self) : false;
}


function wp_linkedin_load_template($name, $args, $plugin=__FILE__) {
	$template = locate_template('linkedin/'. $name . '.php');

	if (empty($template) && file_exists(WP_CONTENT_DIR . '/linkedin/' . $name . '.php')) {
		$template = WP_CONTENT_DIR . '/linkedin/' . $name . '.php';
	}

	if (empty($template)) {
		$template = dirname($plugin) . '/templates/' . $name . '.php';
	}

	$template = apply_filters('linkedin_template', $template);

	extract($args);
	ob_start();
	if (LI_DEBUG) echo '<!-- template path: ' . $template . ' -->';
	require($template);
	return ob_get_clean();
}


function wp_linkedin_shortcode_atts($defaults, $args, $shortcode) {
	if (is_object($args)) {
		$atts = get_object_vars($args);
	} elseif (is_array($args)) { 
		$atts =& $args;
	} else {
		wp_parse_str($args, $atts);
	}

	if (is_array($defaults)) {
		$out = array_merge($defaults, $atts);
	}

	if ($shortcode) {
		$out = apply_filters("shortcode_atts_{$shortcode}", $out, $defaults, $atts, $shortcode);
	}

	return $out;
}


function wp_linkedin_get_field_value($object, $path) {
	if (empty($path)) {
		return $object;
	} else {
		$field_name = array_shift($path);
		$field_name = lcfirst(str_replace('-', '', ucwords($field_name, '-')));
		
		if (is_object($object)) {
			$value = $object->{$field_name};
		} elseif (is_array($object)) {
			$value = $object[$field_name];
		}
		
		return wp_linkedin_get_field_value($value, $path);
	}
}


function wp_linkedin_profile($atts='', $content='') {
	$atts = wp_linkedin_shortcode_atts(array(
				'fields' => LINKEDIN_FIELDS,
				'lang' => LINKEDIN_PROFILELANGUAGE
			), $atts, 'li_profile');
	extract($atts);

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);
	$profile = wp_linkedin_get_profile($fields, $lang);

	if (is_wp_error($profile)) {
		return wp_linkedin_error($profile);
	} elseif ($profile && is_object($profile)) {
		if (empty($content)) {
			return wp_linkedin_load_template('profile',
					array_merge($atts, array('profile' => $profile)));
		} else {
			$matches = array();
			
			if (preg_match_all('/{([^}]*)}/' , $content, $matches)) {
				foreach ($matches[1] as $field) {
					$path = explode(':', $field);
					$value = wp_linkedin_get_field_value($profile, $path);
					$content = str_replace('{'.$field.'}', $value, $content);
				}				
			}
			
			return $content;
		}
	}
}


function wp_linkedin_profile_field($atts='') {
	$atts = wp_linkedin_shortcode_atts(array(
			'field' => false,
			'fields' => LINKEDIN_FIELDS,
			'lang' => LINKEDIN_PROFILELANGUAGE
	), $atts, 'li_profile_field');
	extract($atts);
	
	if (empty($field)) return;

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);
	$profile = wp_linkedin_get_profile($fields, $lang);

	if (is_wp_error($profile)) {
		return wp_linkedin_error($profile);
	} elseif ($profile && is_object($profile)) {
		$path = explode(':', $field);
		return wp_linkedin_get_field_value($profile, $path);
	}
}


function wp_linkedin_card($atts='') {
	$atts = wp_linkedin_shortcode_atts(array(
				'picture_width' => '50',
				'summary_length' => '200',
				'fields' => 'summary',
				'lang' => LINKEDIN_PROFILELANGUAGE
			), $atts, 'li_card');
	extract($atts);

	$fields = preg_replace('/\s+/', '', LINKEDIN_FIELDS_BASIC . ',' . $fields);
	$profile = wp_linkedin_get_profile($fields, $lang);

	if (is_wp_error($profile)) {
		return wp_linkedin_error($profile);
	} elseif ($profile && is_object($profile)) {
		return wp_linkedin_load_template('card',
				array_merge($atts, array('profile' => $profile)));
	}
}


function wp_linkedin_recommendations($atts='') {
	$atts = wp_linkedin_shortcode_atts(array(
				'width' => 'auto',
				'length' => '200',
				'interval' => '4000'
			), $atts, 'li_recommendations');
	extract($atts);

	$profile = wp_linkedin_get_profile(LINKEDIN_FIELDS_RECOMMENDATIONS);

	if (is_wp_error($profile)) {
		return wp_linkedin_error($profile);
	} elseif ($profile && is_object($profile)) {
		if (isset($profile->recommendationsReceived->values) && is_array($profile->recommendationsReceived->values)) {
			return wp_linkedin_load_template('recommendations',
					array_merge($atts, array('recommendations' => $profile->recommendationsReceived->values)));
		} else {
			return wp_linkedin_error(__('You don\'t have any recommendation to show.', 'wp-linkedin'));
		}
	}
}


function wp_linkedin_updates($atts='') {
	$atts = wp_linkedin_shortcode_atts(array(
				'only_self' => true,
				'count' => 50
			), $atts, 'li_updates');
	extract($atts);

	$updates = wp_linkedin_get_network_updates($count, $only_self);

	if (is_wp_error($updates)) {
		return wp_linkedin_error($updates);
	} elseif ($updates && is_object($updates)) {
		return wp_linkedin_load_template('network-updates',
				array_merge($atts, array('updates' => $updates)));
	}
}


function wp_linkedin_original_profile_picture_url() {
	$linkedin = wp_linkedin_connection();
	$picture_urls = $linkedin->get_cache('profile_originalpicture');

	if (!$picture_urls) {
		$picture_urls = $linkedin->api_call('https://api.linkedin.com/v1/people/~/picture-urls::(original)');

		if (!is_wp_error($picture_urls)) {
			$linkedin->set_cache('profile_originalpicture', $picture_urls, WP_LINKEDIN_PROFILE_CACHE_TIMEOUT);
		}
	}

	if (is_wp_error($picture_urls)) {
		return $picture_urls;
	} else {
		return $picture_urls->values[0];
	}
}


function wp_linkedin_picture($atts='') {
	$atts = wp_linkedin_shortcode_atts(array(
			'class' => false,
			'width' => false,
			'height' => false
	), $atts, 'li_picture');
	extract($atts);

	$picture_url = wp_linkedin_original_profile_picture_url();

	if (is_wp_error($picture_url)) {
		return wp_linkedin_error($picture_urls);
	} else {
		$output = "<img src=\"$picture_url\"";
		if ($width) $output .= " width=\"$width\"";
		if ($height) $output .= " height=\"$height\"";
		if ($class) $output .= " class=\"$class\"";
		$output .= '/>';
		return $output;
	}
}


function wp_linkedin_excerpt($str, $length, $postfix='[...]') {
	$length++;

	if (function_exists('mb_strlen')) {
		if (mb_strlen($str) > $length) {
			$subex = mb_substr($str, 0, $length - 5);
			$exwords = explode(' ', $subex);
			$excut = -mb_strlen($exwords[count($exwords) - 1]);
			if ($excut < 0) {
				echo mb_substr($subex, 0, $excut);
			} else {
				echo $subex;
			}
			echo $postfix;
		} else {
			echo $str;
		}
	} else {
		if (strlen($str) > $length) {
			$subex = substr($str, 0, $length - 5);
			$exwords = explode(' ', $subex);
			$excut = -strlen($exwords[count($exwords) - 1]);
			if ($excut < 0) {
				echo substr($subex, 0, $excut);
			} else {
				echo $subex;
			}
			echo $postfix;
		} else {
			echo $str;
		}
	}
}


function wp_linkedin_cause($cause_name) {
	static $causes;
	if (!isset($causes)) {
		$causes = array(
			'animalRights' => __('Animal Welfare', 'wp-linkedin'),
			'artsAndCulture' => __('Arts and Culture', 'wp-linkedin'),
			'children' => __('Children', 'wp-linkedin'),
			'civilRights' => __('Civil Rights and Social Action', 'wp-linkedin'),
			'humanitarianRelief' => __('Disaster and Humanitarian Relief', 'wp-linkedin'),
			'economicEmpowerment' => __('Economic Empowerment', 'wp-linkedin'),
			'education' => __('Education', 'wp-linkedin'),
			'environment' => __('Environment', 'wp-linkedin'),
			'health' => __('Health', 'wp-linkedin'),
			'humanRights' => __('Human Rights', 'wp-linkedin'),
			'politics' => __('Politics', 'wp-linkedin'),
			'povertyAlleviation' => __('Poverty Alleviation', 'wp-linkedin'),
			'scienceAndTechnology' => __('Science and Technology', 'wp-linkedin'),
			'socialServices' => __('Social Services', 'wp-linkedin'));
	}

	return $causes[$cause_name];
}
