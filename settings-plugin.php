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

		public function __construct() {
			$this->plugin      = plugin_basename( __FILE__ );
			$this->plugin_path = plugin_dir_path( __FILE__ );
		}

		/**
		 * Add/register services to wordpress hooks.
		 *
		 * @return void
		 */
		public function register() {
			add_action( 'admin_init', array( $this, 'register_fields' ) );
			add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}

		public function activate() {
			flush_rewrite_rules();

			if ( get_option( 'settings_plugin_options' ) ) {
				return;
			}

			update_option(
				'settings_plugin_options',
				array( 'full_name' => '' )
			);
		}

		public function deactivate() {
			flush_rewrite_rules();
		}

		/**
		 * Sanitizes input from dangerous attributes/characters.
		 *
		 * @param array $input
		 *
		 * @return array
		 */
		public function sanitize_input( $input ) {
			$valid              = array();
			$valid['full_name'] = preg_replace(
				'/[^a-zA-Z\s]/', '', $input['full_name']
			);
			$valid['full_name'] = trim( $valid['full_name'] );
			if ( $valid['full_name'] !== $input['full_name'] ) {
				add_settings_error(
					'full_name',
					'new_settings_error',
					'Incorrect value entered! Please only input letters and spaces.'
				);
				$valid['full_name'] = '';
			}

			return $valid;
		}

		public function section_desc() {
			echo '<p>Enter your settings here.</p>';
		}

		public function render_input( $args ) {
			$option_name = $args['option_name'];
			$options     = get_option( $option_name );
			$id          = $args['label_for'];
			$full_name = $options[ $id ];
			echo '<input 
				id="' . $full_name . '" 
				name="' . $option_name . '[' . $id . ']" 
				type="text" 
				value="' . esc_attr( $full_name ) . '" 
				class="regular-text" 
			>';
		}

		/**
		 * Register settings, sections and custom fields.
		 *
		 * @return void
		 */
		public function register_fields() {
			$option_name = 'settings_plugin_options';
			register_setting( $option_name, $option_name, array( $this, 'sanitize_input' ) );

			add_settings_section(
				'new_settings_main',
				'Available Settings',
				array( $this, 'section_desc' ),
				'new_settings_plugin'
			);

			add_settings_field(
				'full_name',
				'Your full name',
				array( $this, 'render_input' ),
				'new_settings_plugin',
				'new_settings_main',
				array(
					'label_for'   => 'full_name',
					'option_name' => $option_name
				)
			);
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
		public function settings_link( $links ) {
			$settings_link = '<a href="options-general.php?page=new_settings_plugin">Settings</a>';
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
