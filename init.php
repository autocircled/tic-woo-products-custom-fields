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

define( 'TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION', '1.0.0' );
define( 'TIC_WOO_PRODUCTS_CUSTOM_FIELDS_FILE', __FILE__ ); // "D:\wamp64\www\ticone\wp-content\plugins\tic-woo-products-custom-fields\init.php"
define( 'TIC_WOO_PRODUCTS_CUSTOM_FIELDS_BASENAME', plugin_basename( __FILE__ ) ); // "tic-woo-products-custom-fields\init.php"
define( 'TIC_WOO_PRODUCTS_CUSTOM_FIELDS_DIR', plugin_dir_path( __FILE__ ) ); // "D:\wamp64\www\ticone\wp-content\plugins\tic-woo-products-custom-fields\"
define( 'TIC_WOO_PRODUCTS_CUSTOM_FIELDS_URL', plugins_url( '', __FILE__ ) ); // "http://localhost/ticone/wp-content/plugins/tic-woo-products-custom-fields/"
// echo '<pre>';
// var_dump(TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION, TIC_WOO_PRODUCTS_CUSTOM_FIELDS_FILE, TIC_WOO_PRODUCTS_CUSTOM_FIELDS_BASENAME, TIC_WOO_PRODUCTS_CUSTOM_FIELDS_DIR, TIC_WOO_PRODUCTS_CUSTOM_FIELDS_URL);
// echo '</pre>';
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
            require_once( __DIR__ . '/includes/assets-manager.php' );
            require_once( __DIR__ . '/includes/db.php' );
            require_once( __DIR__ . '/includes/settings.php' );
            require_once( __DIR__ . '/includes/custom-fields.php' );
            TIC_Woo_Products_Custom_Fields_Assets_Manager::getInstance()->init();
            TIC_Woo_Products_Custom_Fields_Settings::getInstance()->init();
            TIC_Woo_Products_Fields_Instance::getInstance()->init();
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

