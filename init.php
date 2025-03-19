<?php
/**
 * AAA TIC Woo Products Custom Fields
 *
 * Plugin Name: AAA TIC Woo Products Custom Fields
 * Description: Enables custom fields for TIC Woo Products
 * Version:     1.0.0
 * Author:      autocircle
 * Author URI:  https://github.com/autocircled/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: tic-woo-products-custom-fields
 * Domain Path: /languages
 * Requires at least: 4.9
 * Requires PHP: 5.2.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) )
{
    die( 'Invalid request.' );
}

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if ( ! class_exists( 'TIC_Woo_Products_Custom_Fields' ) ) :

    class TIC_Woo_Products_Custom_Fields
    {
        public function __construct(){
            add_action( 'admin_notices', array( $this, 'global_note' ) );
            add_action( 'plugins_loaded', [ __CLASS__, 'includes'] );
            add_action( 'plugins_loaded', array( $this, 'update_check' ) );
        }

        public function global_note() {
            if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                ?>
                <div id="message" class="error">
                    <p><?php esc_html_e( 'Please install and activate WooCommerce to use TIC Woo Products Custom Fields.', 'tic-woo-products-custom-fields' ); ?></p>
                </div>
                <?php
            }
        }

        public static function includes() {
            require_once( __DIR__ . '/includes/db.php' );
            require_once( __DIR__ . '/includes/settings.php' );
            // require_once( __DIR__ . '/includes/tic-woo-products-custom-fields.php' );
            TIC_Woo_Products_Custom_Fields_Settings::getInstance()->init();
        }

        public function update_check() {
            require_once 'vendor/plugin-update-checker/plugin-update-checker.php';
            PucFactory::buildUpdateChecker(
                'https://repo.tic.com.bd/plugin/tic-woo-products-custom-fields.json',
                __FILE__, //Full path to the main plugin file or functions.php.
                'tic-woo-products-custom-fields'
            );
        }

    }

    new TIC_Woo_Products_Custom_Fields();

endif;

