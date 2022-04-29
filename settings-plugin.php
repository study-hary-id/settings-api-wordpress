<?php
/**
 * @package SettingsPlugin
 */
/**
 * Plugin Name:       Settings Plugin
 * Plugin URI:        https://github.com/study-hary-id/SimplePluginTemplate
 * Description:       A complete and practical example of the WordPress Settings API.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      5.6
 * Author:            Muhammad Haryansyah
 * Author URI:        https://study-hary-id.github.io
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       settings-plugin
 */
/*
Copyright (C) 2022  Muhammad Haryansyah

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Settings_Plugin' ) ) {
	/**
	 * Class Settings_Plugin is a constructor for this plugin.
	 */
	class Settings_Plugin {
		private $plugin;
		private $plugin_path;

		function __construct() {
			$this->plugin      = plugin_basename( __FILE__ );
			$this->plugin_path = plugin_dir_path( __FILE__ );
		}

		/**
		 * Add/register services to wordpress hooks.
		 *
		 * @return void
		 */
		function register() {
			add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}

		function activate() {
			flush_rewrite_rules();
		}

		function deactivate() {
			flush_rewrite_rules();
		}

		/**
		 * Get new-settings template files.
		 *
		 * @return void
		 */
		public function get_template() {
			require_once $this->plugin_path . 'templates/new-settings.php';
		}

		/**
		 * Add new menu to WordPress settings.
		 *
		 * @return void
		 */
		public function add_settings_menu() {
			add_options_page(
				'New Settings',
				'New Settings',
				'manage_options',
				'new_settings_plugin',
				array( $this, 'get_template' )
			);
		}

		/**
		 * Add custom link to the plugin list settings.
		 *
		 * @param array $links Default global links.
		 *
		 * @return array        Modified global links.
		 */
		function settings_link( $links ) {
			$settings_link = '<a href="plugins.php">Settings</a>';
			array_push( $links, $settings_link );

			return $links;
		}
	}

	// Create an instance and register hooks.
	$settings_plugin = new Settings_Plugin();
	$settings_plugin->register();

	// Register listener for activation of the plugin.
	register_activation_hook(
		__FILE__, array( $settings_plugin, 'activate' )
	);

	// Register listener for deactivation of the plugin.
	register_deactivation_hook(
		__FILE__, array( $settings_plugin, 'deactivate' )
	);
}
