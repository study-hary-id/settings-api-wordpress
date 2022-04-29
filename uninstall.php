<?php
/**
 * @package SettingsPlugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die( '-1' );
}

unregister_setting(
	'settings_plugin_options',
	'settings_plugin_options'
);

delete_option( 'settings_plugin_options' );
