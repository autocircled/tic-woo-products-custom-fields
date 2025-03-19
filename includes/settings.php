<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use TIC_Woo_Products_Custom_Fields_DB as DB;
class TIC_Woo_Products_Custom_Fields_Settings {

    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function init() {
        add_action( 'admin_menu', [$this, 'eazyproo_settings_page'] );
        add_action( 'admin_enqueue_scripts', [$this, 'eazyproo_settings_page_assets'] );
        add_action( 'wp_ajax_ez_remove_meta_item', [$this, 'eazyproo_remove_meta_item_by_ajax'] );
    }

    public function eazyproo_settings_page()
    {
        add_menu_page( __('Eazyproo Settings', 'woodmart-child'), __('Eazyproo', 'woodmart-child'), 'manage_options', 'eazyproo-settings', [$this, 'eazyproo_render_settings_page'], '', 30);
    }

    public function eazyproo_render_settings_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'woodmart-child'));
        }
        DB::getInstance()->eazyproo_ensure_all_settings_tables_exists();
        $errors = [];

        if (isset($_POST['submit']) && (isset($_POST['eazyproo_overview_title_fields']) || isset($_POST['eazyproo_sys_reqs_title_fields']) || isset($_POST['eazyproo_meta_title_fields']) || isset($_POST['eazyproo_app_title_fields']) || isset($_POST['eazyproo_feature_title_fields'])) || isset($_POST['tic_others_settings'])) {
            if (
                isset($_POST['eazyproo_overview_title_fields']) 
                && is_array($_POST['eazyproo_overview_title_fields']) 
                && count($_POST['eazyproo_overview_title_fields']) > 0
            ) {
                foreach ($_POST['eazyproo_overview_title_fields'] as $id => $data) {
                    if (empty($data['title'])) {
                        $errors[$id]['title'] = esc_html__('Title cannot be empty', 'woodmart-child');
                    }else {
                        $title = $data['title'];
                        $image = isset($data['image']) ? $data['image'] : '';
                        DB::getInstance()->eazyproo_insert_data('tic_meta_overview', $id, $title, $image);
                    }
                }
            }

            

            if (
                isset($_POST['eazyproo_sys_reqs_title_fields']) 
                && is_array($_POST['eazyproo_sys_reqs_title_fields']) 
                && count($_POST['eazyproo_sys_reqs_title_fields']) > 0
            ) {
                foreach ($_POST['eazyproo_sys_reqs_title_fields'] as $id => $data) {
                    if (empty($data['title'])) {
                        $errors[$id]['title'] = esc_html__('Title cannot be empty', 'woodmart-child');
                    }else {
                        $title = $data['title'];
                        $image = isset($data['image']) ? $data['image'] : '';
                        DB::getInstance()->eazyproo_insert_data('tic_meta_sys_reqs', $id, $title, $image);
                    }
                }
            }


            if (
                isset($_POST['eazyproo_meta_title_fields']) 
                && is_array($_POST['eazyproo_meta_title_fields']) 
                && count($_POST['eazyproo_meta_title_fields']) > 0
            ) {
                foreach ($_POST['eazyproo_meta_title_fields'] as $id => $data) {
                    if (empty($data['title'])) {
                        $errors[$id]['title'] = esc_html__('Title cannot be empty', 'woodmart-child');
                    }else {
                        $title = $data['title'];
                        $image = isset($data['image']) ? $data['image'] : '';
                        DB::getInstance()->eazyproo_insert_data('tic_meta_custom', $id, $title, $image);
                    }
                }
            }


            if (
                isset($_POST['eazyproo_app_title_fields']) 
                && is_array($_POST['eazyproo_app_title_fields']) 
                && count($_POST['eazyproo_app_title_fields']) > 0
            ) {
                foreach ($_POST['eazyproo_app_title_fields'] as $id => $data) {
                    if (empty($data['title'])) {
                        $errors[$id]['title'] = esc_html__('Title cannot be empty', 'woodmart-child');
                    }else {
                        $title = $data['title'];
                        $image = isset($data['image']) ? $data['image'] : '';
                        DB::getInstance()->eazyproo_insert_data('tic_meta_apps', $id, $title, $image);
                    }
                }
            }
            
            if (
                isset($_POST['eazyproo_feature_title_fields']) 
                && is_array($_POST['eazyproo_feature_title_fields']) 
                && count($_POST['eazyproo_feature_title_fields']) > 0
            ) {
                
                foreach ($_POST['eazyproo_feature_title_fields'] as $id => $data) {
                    if (empty($data['title'])) {
                        $errors[$id]['title'] = esc_html__('Title cannot be empty', 'woodmart-child');
                    }else {
                        $title = $data['title'];
                        $image = isset($data['image']) ? $data['image'] : '';
                        $icon = isset($data['icon']) ? $data['icon'] : '';
                        DB::getInstance()->eazyproo_insert_data('tic_key_features', $id, $title, $image, $icon);
                    }
                }
            }

            if (
                isset($_POST['tic_others_settings']) 
                && is_array($_POST['tic_others_settings']) 
                && count($_POST['tic_others_settings']) > 0
            ) {

                // save data to options table
                foreach ($_POST['tic_others_settings'] as $id => $data) {
                    if (empty($data)) {
                        unset($_POST['tic_others_settings'][$id]);
                    }
                }
                if (count($_POST['tic_others_settings']) == 0) {
                    delete_option('tic_others_settings');
                }else{
                    update_option('tic_others_settings', json_encode($_POST['tic_others_settings']));
                }

            }
            
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Eazyproo Product Data Settings', 'woodmart-child'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=eazyproo-settings&tab=overview" class="nav-tab <?php echo $this->eazyproo_get_active_tab('overview'); ?>">
                    <?php echo esc_html__('Overview', 'woodmart-child'); ?>
                </a>
                <a href="?page=eazyproo-settings&tab=sys-reqs" class="nav-tab <?php echo $this->eazyproo_get_active_tab('sys-reqs'); ?>">
                    <?php echo esc_html__('System Requirements', 'woodmart-child'); ?>
                </a>
                <a href="?page=eazyproo-settings&tab=meta-fields" class="nav-tab <?php echo $this->eazyproo_get_active_tab('meta-fields'); ?>">
                    <?php echo esc_html__('Meta Fields', 'woodmart-child'); ?>
                </a>
                <a href="?page=eazyproo-settings&tab=apps" class="nav-tab <?php echo $this->eazyproo_get_active_tab('apps'); ?>">
                    <?php echo esc_html__('Apps', 'woodmart-child'); ?>
                </a>
                <a href="?page=eazyproo-settings&tab=features" class="nav-tab <?php echo $this->eazyproo_get_active_tab('features'); ?>">
                    <?php echo esc_html__('Key Features', 'woodmart-child'); ?>
                </a>
                <a href="?page=eazyproo-settings&tab=others" class="nav-tab <?php echo $this->eazyproo_get_active_tab('others'); ?>">
                    <?php echo esc_html__('Others', 'woodmart-child'); ?>
                </a>
            </h2>

            <form method="post" action="<?php echo esc_url(admin_url()); ?>?page=eazyproo-settings<?php echo isset($_GET['tab']) && !empty($_GET['tab']) ? '&tab=' . esc_attr($_GET['tab']) : ''; ?>">
                <?php
                $tab = isset($_GET['tab']) ? $_GET['tab'] : 'overview';

                if ($tab == 'overview') {
                    $this->eazyproo_overview_section_callback();
                    $this->eazyproo_overview_title_fields_callback($errors);
                } elseif ($tab == 'sys-reqs') {
                    $this->eazyproo_sys_reqs_section_callback();
                    $this->eazyproo_sys_reqs_title_fields_callback();
                } elseif ($tab == 'meta-fields') {
                    $this->eazyproo_meta_fields_section_callback();
                    $this->eazyproo_meta_title_fields_callback();
                } elseif ($tab == 'apps') {
                    $this->eazyproo_included_apps_section_callback();
                    $this->eazyproo_app_title_fields_callback();
                } elseif ($tab == 'features') {
                    $this->eazyproo_key_features_section_callback();
                    $this->eazyproo_key_features_title_fields_callback();
                } elseif ($tab == 'others') {
                    $this->eazyproo_others_section_callback();
                    $this->eazyproo_others_fields_callback();
                }

                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function eazyproo_get_active_tab($tab)
    {
        return isset($_GET['tab']) && $_GET['tab'] === $tab ? 'nav-tab-active' : '';
    }

    public function eazyproo_settings_page_assets($hook_suffix)
    {
        // Load assets only on your custom page
        if ($hook_suffix === 'toplevel_page_eazyproo-settings') {
            wp_enqueue_style('eazyproo-theme',  trailingslashit(get_stylesheet_directory_uri()) . 'assets/css/admin-style.css', [], EAZYPROO_VERSION);
            wp_enqueue_media();
            wp_enqueue_script('eazyproo-theme', trailingslashit(get_stylesheet_directory_uri()) . 'assets/js/admin-script.js', array('jquery'), EAZYPROO_VERSION, true);
        }
    }

    public function eazyproo_overview_section_callback()
    {
        echo '<p>' . esc_html__('Overview title for product data panel', 'woodmart-child') . '</p>';
    }

    public function eazyproo_sys_reqs_section_callback()
    {
        echo '<p>' . esc_html__('System Requirements title for product data panel', 'woodmart-child') . '</p>';
    }

    public function eazyproo_meta_fields_section_callback()
    {
        echo '<p>' . esc_html__('Meta Fields title for product data panel', 'woodmart-child') . '</p>';
    }

    public function eazyproo_included_apps_section_callback()
    {
        echo '<p>' . esc_html__('Apps title for product data panel', 'woodmart-child') . '</p>';
    }
    public function eazyproo_key_features_section_callback()
    {
        echo '<p>' . esc_html__('Key Features for your products', 'woodmart-child') . '</p>';
    }
    public function eazyproo_others_section_callback()
    {
        echo '<p>' . esc_html__('All others settings', 'woodmart-child') . '</p>';
    }

    public function eazyproo_overview_title_fields_callback($errors = [])
    {
        $overview_titles = DB::getInstance()->eazyproo_get_settings('tic_meta_overview');
        $last_item_id = DB::getInstance()->eazyproo_get_last_item_id($overview_titles);
        ?>
        <div id='ez_overview_settings_wrapper' class='panel eazyproo-panel ez-theme-settings'>
            <div id="overview-wrapper" class="eazyproo-repeater" data-count="<?php echo esc_attr($last_item_id); ?>">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($overview_titles && count($overview_titles) > 0) {
                                foreach ($overview_titles as $item) {
                                    ?>
                                    <tr class="options_group gp-<?php echo esc_attr($item->id); ?>">                        
                                        <td>
                                            <input type="text" class="full" name="eazyproo_overview_title_fields[<?php echo esc_attr($item->id); ?>][title]" id="eazyproo_overview_title_fields[<?php echo esc_attr($item->id); ?>][title]" placeholder="Example: Developer" value="<?php echo esc_html($item->title); ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="system_repeater_remove_btn button button-danger" data-meta_id="<?php echo esc_attr($item->id); ?>" data-action="tic_meta_overview">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                }                            
                            } else {
                                ?>
                                <tr class="options_group gp-0">                        
                                    <td>
                                        <input type="text" class="full" name="eazyproo_overview_title_fields[0][title]" id="eazyproo_overview_title_fields[1][title]" placeholder="Example: Adobe" value="">
                                    </td>
                                    <td>
                                        <button type="button" class="system_repeater_remove_btn button button-danger">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                    
                        </tbody>
                    </table>
                    
                    <button type="button" id="add-new-item" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>        

        </div>
        <?php
    }

    public function eazyproo_sys_reqs_title_fields_callback($errors = [])
    {
        $sys_reqs_titles = DB::getInstance()->eazyproo_get_settings('tic_meta_sys_reqs');
        $last_item_id = DB::getInstance()->eazyproo_get_last_item_id($sys_reqs_titles);
        ?>
        <div id='ez_sys_reqs_settings_wrapper' class='panel eazyproo-panel ez-theme-settings'>
            <div id="sys_reqs-wrapper" class="eazyproo-repeater" data-count="<?php echo esc_attr($last_item_id); ?>">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($sys_reqs_titles && count($sys_reqs_titles) > 0) {
                                
                                foreach ($sys_reqs_titles as $item) {
                                    ?>
                                    <tr class="options_group gp-<?php echo esc_attr($item->id); ?>">                        
                                        <td>
                                            <input type="text" class="full" name="eazyproo_sys_reqs_title_fields[<?php echo esc_attr($item->id); ?>][title]" id="eazyproo_sys_reqs_title_fields[<?php echo esc_attr($item->id); ?>][title]" placeholder="Example: OS" value="<?php echo esc_html($item->title); ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="system_repeater_remove_btn button button-danger" data-meta_id="<?php echo esc_attr($item->id); ?>" data-action="tic_meta_sys_reqs">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                }                            
                            } else {
                                ?>
                                <tr class="options_group gp-0">                        
                                    <td>
                                        <input type="text" class="full" name="eazyproo_sys_reqs_title_fields[0]" id="eazyproo_sys_reqs_title_fields[1][title]" placeholder="Example: Adobe" value="">
                                    </td>
                                    <td>
                                        <button type="button" class="system_repeater_remove_btn button button-danger">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                    
                        </tbody>
                    </table>
                    
                    <button type="button" id="add-new-item" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>        

        </div>
        <?php
    }

    public function eazyproo_meta_title_fields_callback($errors = [])
    {
        $meta_field_titles = DB::getInstance()->eazyproo_get_settings('tic_meta_custom');
        $last_item_id = DB::getInstance()->eazyproo_get_last_item_id($meta_field_titles);
        ?>
        <div id='ez_meta_fields_settings_wrapper' class='panel eazyproo-panel ez-theme-settings'>
            <div id="meta_fields-wrapper" class="eazyproo-repeater" data-count="<?php echo esc_attr($last_item_id); ?>">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Image/Icon</th>
                                <th>Title</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($meta_field_titles && count($meta_field_titles) > 0) {
                                foreach ($meta_field_titles as $item) {
                                    ?>
                                    <tr class="options_group gp-<?php echo esc_attr($item->id); ?>">         
                                        <td>
                                            <div class="img-wrapper">
                                                <input type="hidden" name="eazyproo_meta_title_fields[<?php echo esc_attr($item->id); ?>][image]" value="<?php echo isset($item->image) ? esc_attr($item->image) : ''; ?>" />
                                                <button type="button" class="upload_image_button button"><?php echo esc_html('Upload/Add Image', 'woodmart') ?> </button>
                                                <div class="image_preview">
                                                    <?php
                                                    if (isset($item->image) && $item->image) {
                                                        echo  wp_get_attachment_image($item->image, 'thumbnail');
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </td>                
                                        <td>
                                            <input type="text" class="full" name="eazyproo_meta_title_fields[<?php echo esc_attr($item->id); ?>][title]" id="eazyproo_meta_title_fields[<?php echo esc_attr($item->id); ?>][title]" placeholder="Example: Version, Platform" value="<?php echo isset($item->title) ? esc_html($item->title) : ''; ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="system_repeater_remove_btn button button-danger" data-meta_id="<?php echo esc_attr($item->id); ?>" data-action="tic_meta_custom">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                }                            
                            } else {
                                ?>
                                <tr class="options_group gp-0">    
                                    <td>
                                        <div class="img-wrapper">
                                            <input type="hidden" name="eazyproo_meta_title_fields[1][image]" />
                                            <button type="button" class="upload_image_button button">Upload/Add Image</button>
                                            <div class="image_preview"></div>
                                        </div>
                                    </td>                    
                                    <td>
                                        <input type="text" class="full" name="eazyproo_meta_title_fields[1][title]" id="eazyproo_meta_title_fields[1][title]" placeholder="Example: Version, Platform" value="">
                                    </td>
                                    <td>
                                        <button type="button" class="system_repeater_remove_btn button button-danger">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                    
                        </tbody>
                    </table>
                    
                    <button type="button" id="add-new-item" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>        

        </div>
        <?php
    }

    public function eazyproo_app_title_fields_callback($errors = [])
    {
        $app_titles = DB::getInstance()->eazyproo_get_settings('tic_meta_apps');
        $last_item_id = DB::getInstance()->eazyproo_get_last_item_id($app_titles);
        ?>
        <div id='ez_included_apps_settings_wrapper' class='panel eazyproo-panel ez-theme-settings'>
            <div id="included_apps-wrapper" class="eazyproo-repeater" data-count="<?php echo esc_attr($last_item_id); ?>">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Image/Icon</th>
                                <th>Title</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($app_titles && count($app_titles) > 0) {
                                foreach ($app_titles as $item) {
                                    ?>
                                    <tr class="options_group gp-<?php echo esc_attr($item->id); ?>">    
                                        <td>
                                            <div class="img-wrapper">
                                                <input type="hidden" name="eazyproo_app_title_fields[<?php echo esc_attr($item->id); ?>][image]" value="<?php echo isset($item->image) ? esc_attr($item->image) : ''; ?>" />
                                                <button type="button" class="upload_image_button button"><?php echo esc_html('Upload/Add Image', 'woodmart') ?> </button>
                                                <div class="image_preview">
                                                    <?php
                                                    if (isset($item->image) && $item->image) {
                                                        echo  wp_get_attachment_image($item->image, 'thumbnail');
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </td>                    
                                        <td>
                                            <input type="text" class="full" name="eazyproo_app_title_fields[<?php echo esc_attr($item->id); ?>][title]" id="eazyproo_app_title_fields[<?php echo esc_attr($item->id); ?>]['title]" placeholder="Example: Adobe, Autodesk" value="<?php echo isset($item->title) ? esc_html($item->title) : ''; ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="system_repeater_remove_btn button button-danger" data-meta_id="<?php echo esc_attr($item->id); ?>" data-action="tic_meta_apps">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                }                            
                            } else {
                                ?>
                                <tr class="options_group gp-0"> 
                                    <td>
                                        <div class="img-wrapper">
                                            <input type="hidden" name="eazyproo_app_title_fields[1][image]" />
                                            <button type="button" class="upload_image_button button"><?php echo esc_html('Upload/Add Image', 'woodmart') ?> </button>
                                            <div class="image_preview"></div>
                                        </div>
                                    </td>                       
                                    <td>
                                        <input type="text" class="full" name="eazyproo_app_title_fields[1][title]" id="eazyproo_app_title_fields[1][title]" placeholder="Example: Adobe, Autodesk">
                                    </td>
                                    <td>
                                        <button type="button" class="system_repeater_remove_btn button button-danger">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                    
                        </tbody>
                    </table>
                    
                    <button type="button" id="add-new-item" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>        

        </div>
        <?php
    }
    public function eazyproo_key_features_title_fields_callback($errors = [])
    {
        $key_features = DB::getInstance()->eazyproo_get_settings('tic_key_features');
        $last_item_id = DB::getInstance()->eazyproo_get_last_item_id($key_features);
        ?>
        <div id='ez_key_features_settings_wrapper' class='panel eazyproo-panel ez-theme-settings'>
            <div id="key_features-wrapper" class="eazyproo-repeater" data-count="<?php echo esc_attr($last_item_id); ?>">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Icon</th>
                                <th>Image</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($key_features && count($key_features) > 0) {
                                foreach ($key_features as $item) {
                                    ?>
                                    <tr class="options_group gp-<?php echo esc_attr($item->id); ?>">   
                                        <td>
                                            <input type="text" class="full" name="eazyproo_feature_title_fields[<?php echo esc_attr($item->id); ?>][title]" id="eazyproo_feature_title_fields[<?php echo esc_attr($item->id); ?>]['title]" placeholder="Example: Adobe, Autodesk" value="<?php echo isset($item->title) ? esc_html($item->title) : ''; ?>">
                                        </td> 
                                        <td>
                                            <div class="img-wrapper">
                                                <input type="text" class="full" name="eazyproo_feature_title_fields[<?php echo esc_attr($item->id); ?>][icon]" id="eazyproo_feature_title_fields[<?php echo esc_attr($item->id); ?>]['icon]" placeholder='fas fa-tasks' value="<?php echo isset($item->icon) ? esc_html($item->icon) : ''; ?>">
                                                <?php 
                                                if( isset($item->icon) && !empty($item->icon) ){
                                                    echo '<i class="'. esc_attr($item->icon) .'"></i>';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="img-wrapper">
                                                <input type="hidden" name="eazyproo_feature_title_fields[<?php echo esc_attr($item->id); ?>][image]" value="<?php echo isset($item->image) ? esc_attr($item->image) : ''; ?>" />
                                                <button type="button" class="upload_image_button button"><?php echo esc_html__('Upload/Add Image', 'woodmart') ?> </button>
                                                <div class="image_preview">
                                                    <?php
                                                    if (isset($item->image) && $item->image) {
                                                        echo  wp_get_attachment_image($item->image, 'thumbnail');
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </td>                                    
                                        <td>
                                            <button type="button" class="system_repeater_remove_btn button button-danger" data-meta_id="<?php echo esc_attr($item->id); ?>" data-action="tic_key_features">Remove</button>
                                        </td>
                                    </tr>
                                    <?php
                                }                            
                            } else {
                                ?>
                                <tr class="options_group gp-0"> 
                                    <td>
                                        <input type="text" class="full" name="eazyproo_feature_title_fields[1][title]" id="eazyproo_feature_title_fields[1][title]" placeholder="Example: Adobe, Autodesk">
                                    </td>
                                    <td>
                                        <input type="text" class="full" name="eazyproo_feature_title_fields[1][icon]" id="eazyproo_feature_title_fields[1][icon]" placeholder="fas fa-tasks">
                                    </td>
                                    <td>
                                        <div class="img-wrapper">
                                            <input type="hidden" name="eazyproo_feature_title_fields[1][image]" />
                                            <button type="button" class="upload_image_button button"><?php echo esc_html__('Upload/Add Image', 'woodmart') ?> </button>
                                            <div class="image_preview"></div>
                                        </div>
                                    </td>                       
                                    
                                    <td>
                                        <button type="button" class="system_repeater_remove_btn button button-danger">Remove</button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                    
                        </tbody>
                    </table>
                    
                    <button type="button" id="add-new-item" class="button button-primary button-large"><?php _e('Add More', 'woodmart'); ?></button>
                </div>
            </div>        

        </div>
        <?php
    }
    public function eazyproo_others_fields_callback($errors = [])
    {
        $others_data = json_decode(get_option('tic_others_settings'), true);
        ?>
        <div id='tic_others_settings_data_wrapper' class='panel eazyproo-panel ez-theme-settings'>
            <div class="">
                <div class="inner-wrapper">
                    <table class="eazyproo-table form-table">
                        <tbody>
                            <tr>
                                <th>Delivery Notice</th>
                                <td>
                                    <textarea name="tic_others_settings[delivery_notice]" cols="50" rows="3" placeholder="Delivery Notice"><?php echo isset($others_data['delivery_notice']) ? esc_attr($others_data['delivery_notice']) : ''; ?></textarea>
                                
                                </td>
                            </tr>
                                            
                        </tbody>
                    </table>
                    
                </div>
            </div>        

        </div>
        <?php
    }

    public function eazyproo_remove_meta_item_by_ajax()
    {
        $meta_id = isset($_POST['meta_id']) ? $_POST['meta_id'] : '';
        $table = isset($_POST['table']) ? $_POST['table'] : '';

        $result = DB::getInstance()->eazyproo_delete_item_by_id($table, $meta_id);
        if ($result) {
            echo '1';
        }
        die();
    }
}