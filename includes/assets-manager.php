<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use TIC_Woo_Products_Custom_Fields_DB as DB;

class TIC_Woo_Products_Custom_Fields_Assets_Manager {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'), 9999);
    }

    public function enqueue_assets() {
        wp_enqueue_style('select-2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION, 'all');
        wp_enqueue_script('select-2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION, true);
        wp_enqueue_style('flag-icon', 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css', [], TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION);
        wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', [], TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION);
        wp_enqueue_style('custom-admin', TIC_WOO_PRODUCTS_CUSTOM_FIELDS_URL . '/assets/custom-admin.css', [], TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION);
        wp_enqueue_script('custom-admin', TIC_WOO_PRODUCTS_CUSTOM_FIELDS_URL . '/assets/custom-admin.js', array('jquery'), TIC_WOO_PRODUCTS_CUSTOM_FIELDS_VERSION, true);
        $countries = [];
        if (class_exists('WC_Countries')){
            $countries_obj   = new \WC_Countries();
            $countries   = $countries_obj->__get('countries');
        }
        $meta_hooks = array(
            'eazyproo_display_custom_field_before_price' => 'Before Price',
            'eazyproo_display_content_after_price' => 'After Price',
            'eazyproo_display_content_after_add_to_cart' => 'After Add to Cart',
        );
        
        $overview_arr = array();
        $overviews = DB::getInstance()->eazyproo_get_settings('tic_meta_overview') ?: array();
        $sys_reqs = DB::getInstance()->eazyproo_get_settings('tic_meta_sys_reqs') ?: array();
        $apps = DB::getInstance()->eazyproo_get_settings('tic_meta_apps') ?: array();
        $meta_fields = DB::getInstance()->eazyproo_get_settings('tic_meta_custom') ?: array();
        
        foreach( $overviews as $item ) {
            $overview_arr[$item->id] = $item->title;
        }
        
        $sys_arr = array();

        foreach( $sys_reqs as $item ) {
            $sys_arr[$item->id] = $item->title;
        }

        $apps_arr = array();
        foreach ($apps as $item) {
            if( isset($item->title) ){
                $apps_arr[$item->id]['title'] = $item->title ?: '';
            }
            if( isset($item->image) && !empty($item->image) ) {
                $thumb = wp_get_attachment_image_src($item->image, 'thumbnail');
                $apps_arr[$item->id]['image'] = $thumb[0] ?: '';
            }
        }

        $meta_arr = array();
        
        foreach ($meta_fields as $item) {
            if( isset($item->title) && !empty($item->title) ){
                $meta_arr[$item->id]['title'] = $item->title ?: '';
            }
            if( isset($item->image) && !empty($item->image) ) {
                $thumb = wp_get_attachment_image_src($item->image, 'thumbnail');
                $meta_arr[$item->id]['image'] = $thumb[0] ?: '';
            }
        }

        wp_localize_script('custom-admin', 'EAZYPROO_OBJ', array(
            'countries' =>  $countries,
            'meta_hooks' => $meta_hooks,
            'overview' => $overview_arr,
            'reqs' => $sys_arr,
            'apps' => $apps_arr,
            'meta_fields' => $meta_arr,
        ));
    }
}