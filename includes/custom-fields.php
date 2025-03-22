<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use TIC_Woo_Products_Custom_Fields_DB as DB;

class TIC_Woo_Products_Fields_Instance {

    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        add_filter('woocommerce_product_data_tabs', [$this, 'eazyproo_product_data_tabs_back']);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_display_overview_tab_content" ]);
        add_action("woocommerce_process_product_meta", [$this, "save_overview_tab_content"]);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_display_system_requirement_tab_content"]);
        add_action('woocommerce_process_product_meta', [$this, "save_system_requrement_custom_fields"]);

        add_action('woocommerce_product_data_panels', [$this, "eazyproo_meta_contents_data_panel"]);
        add_action('woocommerce_process_product_meta', [$this, "eazyproo_save_meta_contents_data_panel_fields"]);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_display_include_apps_tab_content"]);
        add_action("woocommerce_process_product_meta", [$this, "eazyproo_save_include_apps_meta"]);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_display_key_features_tab_content"]);
        add_action("woocommerce_process_product_meta", [$this, "eazyproo_save_key_features"]);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_display_notice_tab_content"]);
        add_action('woocommerce_process_product_meta', [$this, "save_notice_system_requrement_custom_fields"]);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_faq_tab_content"]);
        add_action('woocommerce_process_product_meta', [$this, 'eazyproo_save_faq_tab_content']);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_title_tag_tab_content"]);
        add_action('woocommerce_process_product_meta', [$this, 'eazyproo_save_title_tag_tab_content']);

        add_action("woocommerce_product_data_panels", [$this, "eazyproo_delivery_notice_tab_content"]);
        add_action('woocommerce_process_product_meta', [$this, 'eazyproo_save_delivery_notice_tab_content']);
    }

    public function eazyproo_product_data_tabs_back($tabs)
    {
        $tabs['overview_tab_content'] = array(
            'label'    => __('EPro Overview', 'woocommerce'),
            'target'   => 'add_overview_tab_content',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['system_requement_setup'] = array(
            'label'    => __('EPro System Reqs', 'woocommerce'),
            'target'   => 'system_requirement_tab_content',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['eazyproo_meta_fields'] = array(
            'label'    => __('EPro Meta Fields', 'woocommerce'),
            'target'   => 'eazyproo_meta_contents_product_data',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['include_apps_meta_tab'] = array(
            'label'    => __('EPro Included Apps', 'woocommerce'),
            'target'   => 'include_apps_meta_panel',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['key_features_tab'] = array(
            'label'    => __('EPro Key Features', 'woocommerce'),
            'target'   => 'key_features_panel',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['sp_notice_meta_tab'] = array(
            'label'    => __('EPro Notices', 'woocommerce'),
            'target'   => 'notice_requirement_tab_content',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['sp_faq_meta_tab'] = array(
            'label'    => __('EPro FAQs', 'woocommerce'),
            'target'   => 'faq_tab_content',
            'class'    => array('show_if_simple'),
            'priority' => 12,
        );
        $tabs['sp_title_tag_meta_tab'] = array(
            'label'    => __('Title Tags', 'woocommerce'),
            'target'   => 'title_tag_tab_content',
            'priority' => 12,
        );
        $tabs['sp_deliver_notice_meta_tab'] = array(
            'label'    => __('Delivery Notices', 'woocommerce'),
            'target'   => 'deliver_notice_tab_content',
            'priority' => 13,
        );
        return $tabs;
    }

    public function eazyproo_display_overview_tab_content()
    {
        global $post;
        $overview_infos = get_post_meta($post->ID, "_eazyproo_overview_info", true) ?? [];
        $overview_flag_info = get_post_meta($post->ID, "_eazyproo_overview_flag_info", true) ?? [];
        $overview_titles = DB::getInstance()->eazyproo_get_settings('tic_meta_overview');
        if (empty($overview_titles) || !is_array($overview_titles)) {
            return;
        }
        ?>
        <div id='add_overview_tab_content' class='panel woocommerce_options_panel'>
            <div id="overview-wrapper" class="eazyproo-repeater" data-count="<?php echo !empty( $overview_infos ) ? esc_attr(count($overview_infos)) : esc_attr(1); ?>">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="options_group">
                                <td>
                                    <input type="text" class="full" name="eazyproo_overview_flag[title]" id="eazyproo_overview_flag[title]" placeholder="Example: Language" value="<?php echo isset($overview_flag_info['title']) ? esc_attr($overview_flag_info['title']) : 'Supported Languages'; ?>">
                                </td>
                                <td colspan="2">
                                    <select id="ez_all_countries" class="short" name="eazyproo_overview_flag[content][]" multiple="multiple">
                                        <?php                                    
                                        $countries_obj   = new WC_Countries();
                                        $countries   = $countries_obj->__get('countries');
                                        $saved_countries = isset($overview_flag_info['content']) && !empty($overview_flag_info['content']) && is_array($overview_flag_info['content']) ? $overview_flag_info['content'] : [];
                                        foreach ($countries as $key => $value) {
                                            ?>
                                            <option value="<?php echo esc_attr($key); ?>" <?php  selected(in_array($key, $saved_countries)); ?>><?php echo esc_html($value . ' (' . $key . ')'); ?></option>
                                            
                                            <?php
                                        }
                                        ?>                            
                                    </select>
                                </td>
                            </tr>
                            <?php 
                            if ( !empty($overview_titles) ) :
                                foreach ($overview_titles as $key => $item) : 
                                    $filtered = is_array($overview_infos) ? array_filter($overview_infos, fn($value) => $value['title'] == $item->id) : [];
                                    ?>
                                    <tr class="options_group">
                                        <td>
                                            <div>
                                                <span><?php echo esc_html($item->title); ?></span>
                                                <input type="hidden" value="<?php echo esc_attr($item->id); ?>" name="eazyproo_overview[<?php echo esc_attr($key); ?>][title]">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="full overview_content_input" name="eazyproo_overview[<?php echo esc_attr($key); ?>][content]" id="eazyproo_overview[<?php echo esc_attr($key); ?>][content]" value="<?php echo !empty($filtered) ? esc_attr(reset($filtered)['content']) : ''; ?>">
                                        </td>
                                        <td><button type="button" class="ez_remover_overview button button-danger">Clear</button></td>
                                    </tr>
                                    <?php 
                                endforeach; 
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>        

        </div>
        <?php
    }

    public function save_overview_tab_content($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        

        if (isset($_POST["eazyproo_overview_flag"]) && !empty($_POST["eazyproo_overview_flag"])) {
            if( isset( $_POST["eazyproo_overview_flag"]['title'] ) && isset( $_POST["eazyproo_overview_flag"]['content'] ) ){
                $_POST["eazyproo_overview_flag"]['title'] = sanitize_text_field($_POST["eazyproo_overview_flag"]['title']);
                $_POST["eazyproo_overview_flag"]['content'] = array_map( 'sanitize_text_field', $_POST["eazyproo_overview_flag"]['content'] );
            }
            update_post_meta($post_id, "_eazyproo_overview_flag_info", $_POST["eazyproo_overview_flag"]);
        }

        if (isset($_POST["eazyproo_overview"]) && !empty($_POST["eazyproo_overview"])) {
            $eazyproo_overview = $_POST["eazyproo_overview"];

            foreach ($eazyproo_overview as $key => $value) {
                if (empty($value['content'])) {
                    unset($eazyproo_overview[$key]);
                } else {
                    $value['title'] = sanitize_text_field($value['title']);

                    if (isset($value['content']) && is_array($value['content'])) {
                        $value['content'] = array_map('sanitize_text_field', $value['content']);
                    } else {
                        $value['content'] = sanitize_text_field($value['content']);
                    }
                }
            }

            update_post_meta($post_id, "_eazyproo_overview_info", $eazyproo_overview);
        } else {
            update_post_meta($post_id, "_eazyproo_overview_info", array());
        }
    }

    public function eazyproo_display_system_requirement_tab_content()
    {
        global $post;

        if ( empty( $post ) || ! is_object( $post ) ) {
            return;
        }

        // $sys_req__titles = get_option('eazyproo_sys_reqs_title_fields');
        $sys_req__titles = DB::getInstance()->eazyproo_get_settings('tic_meta_sys_reqs');

        // Minimum Requirements
        $system_requirement_infos = get_post_meta( $post->ID, "_update_system_requirement_info", true ) ?? [];
        
        // Recommended Requirements
        $system_recommends_infos = get_post_meta( $post->ID, "_update_system_recommend_info", true ) ?? [];

        $system_requirement_recommend = get_post_meta( $post->ID, "system_requirement_recommend", true ) ?? [];



        ?>
        <div id="system_requirement_tab_content" class="panel woocommerce_options_panel">
            <div id="system_wrapper_requirements" class="eazyproo-repeater">
                <div class="inner-wrapper">
                    <h3><?php esc_html_e('System Requirements and Recommends', 'woodmart'); ?></h3>                
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Components</th>
                                <th>Requirements</th>
                                <th>Recommends</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        foreach ( $sys_req__titles as $key => $item ) : ?>
                            <tr>
                                <td>
                                    <span><?php echo esc_html( $item->title ); ?></span>
                                    <input type="hidden" name="system_requirement_recommend[<?php echo esc_attr( $key ); ?>][component]" value="<?php echo esc_attr( $item->title ); ?>" />
                                </td>
                                <td><input type="text" class="full" name="system_requirement_recommend[<?php echo esc_attr( $key ); ?>][requirement]" placeholder="Example: 2GB of RAM" value="<?php
                                echo isset( $system_requirement_recommend[$key]['requirement'] ) ? esc_attr( $system_requirement_recommend[$key]['requirement'] ) : ''; 
                                ?>"></td>
                                <td><input type="text" class="full" name="system_requirement_recommend[<?php echo esc_attr( $key ); ?>][recommend]" placeholder="Example: 2GB of RAM" value="<?php
                                echo isset( $system_requirement_recommend[$key]['recommend'] ) ? esc_attr( $system_requirement_recommend[$key]['recommend'] ) : '';
                                ?>"></td>
                                <td><button type="button" data-action="requirement" class="ez_clear_reqs button">Clear</button></td>
                            </tr>
                            <?php
                        endforeach; ?>
                        </tbody>
                    </table>  
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=eazyproo-settings&tab=sys-reqs' ) ); ?>" target="_blank" class="button button-secondary">Add more requirements</a>                  
                </div>
            </div>

            <div id="system_wrapper_requirements" class="eazyproo-repeater">
                <div class="inner-wrapper">
                    <h3><?php esc_html_e('System Requirements', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ( ! empty( $system_requirement_infos ) && is_array( $system_requirement_infos ) ) {
                                $count = 0;
                                foreach ( $system_requirement_infos as $system_requirement_info ) { ?>
                                    <tr class="options_group">
                                        <td>
                                            <select name="system_requirement_info[<?php echo esc_attr( $count ); ?>][title]" id="">
                                                <option>Select</option>
                                                <?php foreach ( $sys_req__titles as $item ) : ?>
                                                    <option value="<?php echo esc_attr( $item->id ); ?>" <?php selected( $system_requirement_info['title'] == $item->id ); ?>><?php echo esc_html( $item->title ); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="full" name="system_requirement_info[<?php echo esc_attr( $count ); ?>][info]" id="system_requirement_info[<?php echo esc_attr( $count ); ?>][info]" placeholder="Example: 2GB of RAM" value="<?php echo esc_attr( $system_requirement_info['info'] ); ?>">
                                        </td>
                                        <td>
                                            <button type="button" data-action="requirement" class="ez_remover_reqs button">Remove</button>
                                        </td>
                                    </tr>
                                    <?php 
                                    $count++;
                                }
                            }else {
                                ?>
                                <tr class="options_group">
                                    <td>
                                        <select name="system_requirement_info[0][title]">
                                            <option>Select</option>
                                            <?php foreach ( $sys_req__titles as $item ) : ?>
                                                <option value="<?php echo esc_attr( $item->id ); ?>"><?php echo esc_html( $item->title ); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="full" name="system_requirement_info[0][info]" id="system_requirement_info[0][info]" placeholder="Example: 2GB of RAM" value="">
                                    </td>
                                    <td>
                                        <button type="button" data-action="requirement" class="ez_remover_reqs button">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                        
                        </tbody>
                    </table>
                    <!-- <button type="button" id="add-system-repeater" data-action="requirement" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button> -->
                </div>
            </div>

            <div id="system_wrapper_recommends" class="eazyproo-repeater">
                <div class="inner-wrapper">
                    <h3><?php esc_html_e('System Recommends', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ( ! empty( $system_recommends_infos ) && is_array( $system_recommends_infos ) ) {
                                $count = 0;
                                foreach ( $system_recommends_infos as $system_recommends_info ) { ?>
                                    <tr class="options_group">
                                        <td>
                                            <select name="system_recommend_info[<?php echo esc_attr( $count ); ?>][title]" id="">
                                                <option>Select</option>
                                                <?php foreach ( $sys_req__titles as $item ) : ?>
                                                    <option value="<?php echo esc_attr( $item->id ); ?>" <?php selected( $system_recommends_info['title'] == $item->id ); ?>><?php echo esc_html( $item->title ); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="full" name="system_recommend_info[<?php echo esc_attr( $count ); ?>][info]" id="system_recommends_info[<?php echo esc_attr( $count ); ?>][info]" placeholder="Example: 2GB of RAM" value="<?php echo esc_attr( $system_recommends_info['info'] ); ?>">
                                        </td>
                                        <td>
                                            <button type="button" data_action="recommend" class="ez_remover_reqs button">Remove</button>
                                        </td>
                                    </tr>
                                    <?php 
                                    $count++;
                                }
                            }else {
                                ?>
                                <tr class="options_group">
                                    <td>
                                        <select name="system_recommend_info[0][title]">
                                            <option>Select</option>
                                            <?php foreach ( $sys_req__titles as $item ) : ?>
                                                <option value="<?php echo esc_attr( $item->id ); ?>"><?php echo esc_html( $item->title ); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="full" name="system_recommend_info[0][info]" id="system_recommends_info[0][info]" placeholder="Example: 2GB of RAM" value="">
                                    </td>
                                    <td>
                                        <button type="button" data_action="recommend" class="ez_remover_reqs button">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                        
                        </tbody>
                    </table>
                    <!-- <button type="button" id="add-system-repeater" data-action="recommend" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button> -->
                </div>
            </div>
        </div>
        <?php

    }

    public function save_system_requrement_custom_fields($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['system_requirement_recommend']) && !empty($_POST['system_requirement_recommend'])){
            $data = $_POST['system_requirement_recommend'];
            foreach ($data as $key => $value) {
                // var_dump($value, empty($value['requirement']) && empty($value['recommend']));
                if (empty($value['requirement']) && empty($value['recommend'])) {
                    unset($data[$key]);
                }else{
                    $value['requirement'] = sanitize_text_field($value['requirement']);
                    $value['recommend'] = sanitize_text_field($value['recommend']);
                }
            }
            if (count($data) > 0) {
                update_post_meta($post_id, 'system_requirement_recommend', $data);
            } else {
                delete_post_meta($post_id, 'system_requirement_recommend');
            }
        }

        if (isset($_POST['system_requirement_info']) && !empty($_POST['system_requirement_info'])) {

            $requirements = $_POST['system_requirement_info'];
            foreach ($requirements as $key => $value) {
                if (empty($value['title']) || empty($value['info'])) {
                    unset($requirements[$key]);
                }else{
                    $value['title'] = sanitize_text_field($value['title']);
                    $value['info'] = sanitize_text_field($value['info']);
                }
            }

            if (count($requirements) > 0) {
                update_post_meta($post_id, '_update_system_requirement_info', $requirements);
            } else {
                delete_post_meta($post_id, '_update_system_requirement_info');
            }
        }else{
            delete_post_meta($post_id, '_update_system_requirement_info');
        }
        
        if (isset($_POST['system_recommend_info']) && !empty($_POST['system_recommend_info'])) {

            $recommends = $_POST['system_recommend_info'];
            foreach ($recommends as $key => $value) {
                if (empty($value['title']) || empty($value['info'])) {
                    unset($recommends[$key]);
                }else{
                    $value['title'] = sanitize_text_field($value['title']);
                    $value['info'] = sanitize_text_field($value['info']);
                }
            }

            if (count($recommends) > 0) {
                update_post_meta($post_id, '_update_system_recommend_info', $recommends);
            } else {
                delete_post_meta($post_id, '_update_system_recommend_info');
            }
        }else{
            delete_post_meta($post_id, '_update_system_recommend_info');
        }
    }
    
    public function eazyproo_meta_contents_data_panel()
    {
        global $post;
        if (empty($post)) {
            return;
        }

        $hooks = array(
            'eazyproo_display_custom_field_before_price' => 'Before Price',
            'eazyproo_display_content_after_price' => 'After Price',
            'eazyproo_display_content_after_add_to_cart' => 'After Add to Cart',
        );

        $hooks = apply_filters('eazyproo_custom_hooks', $hooks);
        $custom_fields = get_post_meta($post->ID, '_custom_repeater_field', true) ?: [];
        $custom_fields_titles = DB::getInstance()->eazyproo_get_settings('tic_meta_custom');
        if (empty($custom_fields_titles)) {
            return;
        }
        // echo '<pre>';
        // var_dump($custom_fields_titles, $custom_fields);
        // echo '</pre>';
        // die();

        ?>
        <div id='eazyproo_meta_contents_product_data' class='panel woocommerce_options_panel'>
            <div id="ezproo-meta" class="eazyproo-repeater" data-count="<?php echo !empty( $custom_fields ) ? esc_attr(count($custom_fields)) : 0; ?>">
                <div class="inner-wrapper">
                    <h3><?php _e('Custom Fields', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th colspan="2">Components</th>
                                <th>Content</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($custom_fields_titles as $item) :
                                $thumb = wp_get_attachment_image_src($item->image, 'thumbnail');
                                ?>
                                <tr>
                                    <td>
                                        <?php if( $thumb ) { ?>
                                        <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php esc_attr($item->title); ?>" width="30" height="30" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div>                                        
                                            <span><?php echo esc_html($item->title); ?></span>
                                            <input type="hidden" name="custom_repeater_field[<?php echo esc_attr($item->id); ?>][key]" value="<?php echo esc_attr($item->id); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <input class="full content-input" type="text" name="custom_repeater_field[<?php echo esc_attr($item->id) ?>][value]" placeholder="<?php echo esc_attr($item->title); ?>" value="<?php
                                            if (is_array($custom_fields) && count($custom_fields) > 0){
                                                foreach( $custom_fields as $kd => $data){
                                                    if($item->id == $kd){
                                                        echo esc_html($data['value']);
                                                    }
                                                }
                                            }
                                        ?>" />
                                    </td>
                                    <td>
                                        <select name="custom_repeater_field[<?php echo esc_attr($item->id) ?>][hook_name]" id="custom_field_hook_list" class="meta_hook">
                                            <option value="">Select Location</option>
                                            <?php
                                            foreach ($hooks as $hook => $title) :
                                                ?>
                                                <option value="<?php echo esc_attr($hook) ?>" <?php
                                                    foreach($custom_fields as $kd => $data){
                                                        if($kd == $item->id){
                                                            selected($data['hook_name'], $hook);
                                                        }else{
                                                            selected("eazyproo_display_custom_field_before_price", $hook);
                                                        }
                                                    }
                                                ?>><?php echo esc_html($title) ?></option>
                                                <?php
                                            endforeach;?>
                                        </select>
                                    </td>
                                    <td><button type="button" class="ez_remover_meta button btn-outline-danger"><?php echo esc_html__('Clear', 'woodmart') ?> </button></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    public function eazyproo_save_meta_contents_data_panel_fields($post_id)
    {
        if (isset($_POST['custom_repeater_field']) && is_array($_POST['custom_repeater_field'])) {

            $raw_items = $_POST['custom_repeater_field'];       
            foreach ($raw_items as $key => $item) {
                if ( empty($item['key']) || empty($item['value'])) {
                    unset($raw_items[$key]);
                }else{
                    $item['key'] = sanitize_text_field($item['key']);
                    $item['value'] = sanitize_text_field($item['value']);
                    if (isset($item['hook_name']) && !empty($item['hook_name'])) {
                        $item['hook_name'] = sanitize_text_field($item['hook_name']);
                    }
                }
            }

            if (count($raw_items) == 0) {
                delete_post_meta($post_id, '_custom_repeater_field');
            } else if (count($raw_items) > 0) {
                update_post_meta($post_id, '_custom_repeater_field', $raw_items);
            }
        }else{
            delete_post_meta($post_id, '_custom_repeater_field');
        }
    }

    public function eazyproo_display_include_apps_tab_content()
    {
        global $post;
        $include_apps = get_post_meta($post->ID, "_include_apps_list", true);
        $apps = DB::getInstance()->eazyproo_get_settings('tic_meta_apps') ?: [];
        ?>
        <div id="include_apps_meta_panel" class="panel woocommerce_options_panel">
            <div id="inlcude_apps_wrapper" class="eazyproo-repeater" data-count="<?php echo !empty( $include_apps ) ? esc_attr(count($include_apps)) : 0; ?>">
                <div class="inner-wrapper">
                    <h3>Apps</h3>
                    <?php
                    if(empty($apps)){
                        echo '<p>No apps found</p>';
                        echo '<a class="button" href="'. admin_url('post-new.php?post_type=type_app') .'">'. __('Add Apps', 'woodmart') .'</a>';
                    } else {
                        ?>
                        <div class="app-header-container">
                            <select id="ez_all_apps">
                                <option>Select apps</option>
                                <?php
                                foreach ($apps as $app) { ?>
                                    <option value="<?php echo esc_attr($app->id); ?>"><?php echo isset($app->title) ? esc_attr($app->title) : ''; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                    <div id="ez-apps-wrapper" class="below-wrapper">
                        <?php
                        if (!empty($include_apps)) {
                            echo '<ul id="selected_apps_wrapper">';
                            foreach ($include_apps as $app) { 
                                if( !is_numeric($app) ){ continue; }
                                $label = DB::getInstance()->eazyproo_get_title_data_by_id('tic_meta_apps', $app);
                                if(empty($label->title)){ continue; }
                                ?>
                                <li data-title="<?php echo esc_attr($label->title); ?>" class="options_group">
                                    <div class="item-inner">
                                        <input type="hidden" class="title_hidden" name="include_apps_info[]" value="<?php echo esc_attr($label->id); ?>">
                                        <?php if(!empty($label->image)): 
                                            $thumb = wp_get_attachment_image_src($label->image, 'thumbnail');
                                            ?>
                                            <img src="<?php echo esc_url($thumb[0]); ?>" width="150" height="150">
                                        <?php endif; ?>
                                        <?php if(!empty($label->title)): ?>
                                            <span class="title"><?php echo esc_html($label->title); ?></span>
                                        <?php endif; ?>
                                        <span class="remove_item_button">X</span>
                                    </div>
                                </li>
                                <?php
                            }
                            echo '</ul>';
                        } else { ?>
                            <div class="include-apps-field-wrapper">
                                <ul id="selected_apps_wrapper"></ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function eazyproo_display_key_features_tab_content()
    {
        global $post;
        $key_features = get_post_meta($post->ID, "_key_features_list", true);
        $all_features = DB::getInstance()->eazyproo_get_settings('tic_key_features') ?: [];
        ?>
        <div id="key_features_panel" class="panel woocommerce_options_panel">
            <div id="key_features_wrapper" class="eazyproo-repeater" data-count="<?php echo !empty( $key_features ) ? esc_attr(count($key_features)) : 0; ?>">
                <div class="inner-wrapper">
                    <h3>Key Features</h3>
                    <?php
                    if(empty($all_features)){
                        echo '<p>No Key Features found</p>';
                        echo '<a class="button" href="'. admin_url('?page=eazyproo-settings&tab=features') .'">'. __('Add New Key Feature', 'woodmart') .'</a>';
                    } else {
                        ?>
                        <div class="app-header-container">
                            <ul class="key-list">
                                <?php
                                foreach ($all_features as $feature) { 
                                    ?>
                                    <li class="key key-<?php echo esc_attr($feature->id); ?>">
                                        <input type="checkbox" aria-hidden="true" id="feature-<?php echo esc_attr($feature->id); ?>" name="key_features[]" tabindex="-1" value="<?php echo esc_attr($feature->id); ?>" <?php echo is_array($key_features) && in_array($feature->id, $key_features) ? 'checked' : ''; ?> />
                                        <label for="feature-<?php echo esc_attr($feature->id); ?>" class="key-label">
                                            <div class="feature-wrapper">
                                                <?php if(empty($feature->icon) && !empty($feature->image)){ 
                                                    $thumb = wp_get_attachment_image_src($feature->image, 'thumbnail');
                                                    ?>
                                                    <img src="<?php echo esc_url( $thumb[0] ); ?>" width="30" height="30" alt="<?php echo esc_attr($feature->title)?>" class="rounded" />
                                                <?php } ?>
                                                <?php if(!empty($feature->icon)){ ?>
                                                    <i class="<?php echo esc_attr($feature->icon); ?>"></i>
                                                <?php } ?>
                                                <span><?php echo esc_html($feature->title)?></span>
                                            </div>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function eazyproo_save_key_features($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST["key_features"]) && is_array($_POST["key_features"])) {
            update_post_meta($post_id, "_key_features_list", array_map('sanitize_text_field', $_POST["key_features"]));
        } else {
            delete_post_meta($post_id, "_key_features_list");
        }
    }

    public function eazyproo_save_include_apps_meta($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST["include_apps_info"]) && is_array($_POST["include_apps_info"])) {
            update_post_meta($post_id, "_include_apps_list", array_map('sanitize_text_field', $_POST["include_apps_info"]));
        } else {
            delete_post_meta($post_id, "_include_apps_list");
        }
    }

    public function eazyproo_display_notice_tab_content()
    {
        global $post;

        if (empty($post) || !is_object($post)) {
            return;
        }

        $notice_system_requirement_infos = get_post_meta($post->ID, "_update_notice_system_requirement_info", true) ?? [];
        ?>
        <div id="notice_requirement_tab_content" class="panel woocommerce_options_panel">
            <div id="notice_system_wrapper" class="eazyproo-repeater" data-count="<?php echo is_array($notice_system_requirement_infos) ? esc_attr(count($notice_system_requirement_infos)) : 0; ?>">
                <div class="inner-wrapper">
                    <h3><?php _e('All Notices', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Notice</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($notice_system_requirement_infos)) {
                                $count = 0;
                                foreach ($notice_system_requirement_infos as $system_requirement_info) {
                                    ?>
                                    <tr class="options_group">
                                        <td>
                                            <input type="text" name="notice_system_requirement_info[<?php echo esc_attr($count); ?>][title]" value="<?php echo esc_attr($system_requirement_info['title']); ?>" class="notice-input full" placeholder="Type your notice here. e.g: This is a digital downloadable software not a license key.">
                                        </td>
                                        <td>
                                            <button type="button" class="notice_system_repeater_remove_btn button">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                }
                            } else { ?>
                                <tr class="options_group">
                                    <td>
                                        <input type="text" name="notice_system_requirement_info[0][title]" class="notice-input full" placeholder="Type your notice here. e.g: This is a digital downloadable software not a license key.">
                                    </td>
                                    <td>
                                        <button type="button" class="notice_system_repeater_remove_btn button">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <button type="button" id="add-notice-system-repeater" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_notice_system_requrement_custom_fields($post_id)
    {
        if (empty($_POST['notice_system_requirement_info'])) {
            delete_post_meta($post_id, '_update_notice_system_requirement_info');
            return;
        }

        $raw_items = $_POST['notice_system_requirement_info'];
        $sanitized_items = [];
        foreach ($raw_items as $key => $item) {
            if (isset($item['title']) && !empty($item['title'])) {
                $sanitized_items[$key]['title'] = sanitize_text_field($item['title']);
            }
        }

        if (empty($sanitized_items)) {
            delete_post_meta($post_id, '_update_notice_system_requirement_info');
        } else {
            update_post_meta($post_id, '_update_notice_system_requirement_info', $sanitized_items);
        }
    }

    public function eazyproo_faq_tab_content()
    {
        global $post;
        if (empty($post)) {
            return;
        }

        $faqs_data = get_post_meta($post->ID, "_update_product_faqs", true);
        if (empty($faqs_data)) {
            $faqs_data = [];
        }
        ?>
        <div id="faq_tab_content" class="panel woocommerce_options_panel">
            <div id="faqs_wrapper" class="eazyproo-repeater" data-count="<?php echo count($faqs_data); ?>">
                <div class="inner-wrapper">
                    <h3><?php _e('All FAQs', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($faqs_data) > 0) {
                                $count = 0;
                                foreach ($faqs_data as $faq) {
                                    ?>
                                    <tr class="options_group">
                                        <td>
                                            <textarea name="product_faqs[<?php echo esc_attr($count); ?>][title]" class="faq-title-input full" placeholder="Write FAQ title here"><?php echo isset( $faq['title'] ) ? esc_attr( $faq['title'] ) : ''; ?></textarea>
                                        </td>
                                        <td>
                                            <textarea name="product_faqs[<?php echo esc_attr($count); ?>][answer]" class="faq-answer-input full" placeholder="Write FAQ answer here"><?php echo isset( $faq['answer'] ) ? esc_attr( $faq['answer'] ) : ''; ?></textarea>
                                        </td>
                                        <td><button type="button" class="faq_remover_btn button">Remove</button></td>
                                    </tr>
                                    <?php
                                    $count++;
                                }
                            } else { ?>
                                <tr class="options_group">
                                    <td>
                                        <textarea name="product_faqs[0][title]" class="faq-title-input full" placeholder="Write FAQ title here"></textarea>
                                    </td>
                                    <td>
                                        <textarea name="product_faqs[0][answer]" class="faq-answer-input full" placeholder="Write FAQ answer here"></textarea>
                                    </td>
                                    <td><button type="button" class="faq_remover_btn button">Remove</button></td>
                                </tr>
                                <?php
                            } ?>

                        </tbody>
                    </table>
                    <button type="button" id="add-new-faq" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>
        </div>
        <?php    
    }

    public function eazyproo_save_faq_tab_content($post_id)
    {
        if (isset($_POST['product_faqs'])) {
            $faqs = $_POST['product_faqs'];
            
            foreach ($faqs as $key => $faq) {
                if (empty($faq['title']) || empty($faq['answer'])) {
                    unset($faqs[$key]);
                }else{
                    $faq['title'] = sanitize_text_field($faq['title']);
                    $faq['info'] = sanitize_text_field($faq['answer']);
                }
            }
            if (count($faqs) == 0) {
                delete_post_meta($post_id, '_update_product_faqs');
            }
            if (count($faqs) > 0) {
                update_post_meta($post_id, '_update_product_faqs', $faqs);
            }
        }else{
            delete_post_meta($post_id, '_update_product_faqs');
        }
    }

    public function eazyproo_title_tag_tab_content()
    {
        global $post;
        if (empty($post)) {
            return;
        }

        $title_tag_data = json_decode(get_post_meta($post->ID, "_product_title_tags", true)) ?? [];

        // get all woocommerce product tags
        $args = array(
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
        );
        $product_tags = get_terms($args);
        ?>
        <div id="title_tag_tab_content" class="panel woocommerce_options_panel">
            <div id="title_tag_wrapper" class="">
                <div class="inner-wrapper">
                    <h3><?php _e('Title Tags', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <td>
                                    <select id="ez_all_tags" class="short" name="product_title_tags[]" multiple="multiple">
                                        <option value="">Select Tag</option>  
                                        <?php
                                        if (count($product_tags) > 0) {
                                            foreach ($product_tags as $tag) {
                                                echo '<option value="' . $tag->term_id . '" ' . (in_array($tag->term_id, $title_tag_data) ? 'selected="selected"' : '') . '>' . $tag->name . '</option>';
                                            }
                                        }
                                        ?>                          
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Select tags to show them before Product Title</p>
                                    <p>Tags must have <a href="https://fontawesome.com/v5/search?m=free" target="_blank">Font Awesome Icon</a> class name without markup</p>
                                    <p>For example: <code>fa fa-home</code></p>
                                    <p>To update tags go to Product >> <a href="<?php echo esc_url(admin_url( 'edit-tags.php?taxonomy=product_tag&post_type=product' ))?>" target="_blank">Tags</a></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php    
    }

    public function eazyproo_save_title_tag_tab_content($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST["product_title_tags"]) && !empty($_POST["product_title_tags"])) {

            $title_tags = sanitize_text_field(json_encode($_POST['product_title_tags']));
            update_post_meta($post_id, "_product_title_tags", $title_tags);
        }else{
            delete_post_meta($post_id, '_product_title_tags');
        }
    }

    public function eazyproo_delivery_notice_tab_content()
    {
        global $post;
        if (empty($post)) {
            return;
        }

        $deliver_notice = get_post_meta($post->ID, "product_delivery_notice", true) ?? false;

        

        $settings_data = json_decode( get_option("tic_others_settings"), true );
        $default_deliver_notice = isset($settings_data['delivery_notice']) ? $settings_data['delivery_notice'] : 'Deliver in 1-2 days';
        ?>
        <div id="deliver_notice_tab_content" class="panel woocommerce_options_panel">
            <div id="product_delivery_notice_wrapper" class="">
                <div class="inner-wrapper">
                    <h3><?php _e('Deliver Notice', 'woodmart'); ?></h3>
                    <table class="eazyproo-table form-table">
                        <thead>
                        <tr>
                                <td>
                                    <p>Override global deliver notice</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <textarea name="product_delivery_notice" id="product_delivery_notice" cols="100" rows="5"
                                    placeholder="<?php echo esc_attr($default_deliver_notice); ?>"><?php echo $deliver_notice ? wp_kses_data($deliver_notice) : ''; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>To update global delivery notice go to <a href="<?php echo esc_url(admin_url( 'admin.php?page=eazyproo-settings&tab=others' ))?>" target="_blank">theme settings page</a></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php    
    }

    public function eazyproo_save_delivery_notice_tab_content($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST["product_delivery_notice"]) && !empty($_POST["product_delivery_notice"])) {

            $delivery_notice = wp_kses_data($_POST['product_delivery_notice']);
            update_post_meta($post_id, "product_delivery_notice", $delivery_notice);
        }else{
            delete_post_meta($post_id, 'product_delivery_notice');
        }
    }

}