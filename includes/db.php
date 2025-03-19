<?php
// namespace TIC_Woo_Products_Custom_Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class TIC_Woo_Products_Custom_Fields_DB {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function eazyproo_ensure_all_settings_tables_exists()
    {
        global $wpdb;
        $tables = [
            'tic_meta_overview ' => "CREATE TABLE {$wpdb->prefix}tic_meta_overview (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                title varchar(255) NOT NULL,
                image bigint(20) UNSIGNED DEFAULT NULL,
                icon varchar(255) DEFAULT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            )",
            'tic_meta_sys_reqs' => "CREATE TABLE {$wpdb->prefix}tic_meta_sys_reqs (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                title varchar(255) NOT NULL,
                image bigint(20) UNSIGNED DEFAULT NULL,
                icon varchar(255) DEFAULT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            )",
            'tic_meta_custom' => "CREATE TABLE {$wpdb->prefix}tic_meta_custom (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                title varchar(255) NOT NULL,
                image bigint(20) UNSIGNED DEFAULT NULL,
                icon varchar(255) DEFAULT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            )",
            'tic_meta_apps' => "CREATE TABLE {$wpdb->prefix}tic_meta_apps (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                title varchar(255) NOT NULL,
                image bigint(20) UNSIGNED DEFAULT NULL,
                icon varchar(255) DEFAULT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            )",
            'tic_key_features' => "CREATE TABLE {$wpdb->prefix}tic_key_features(
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                title varchar(255) NOT NULL,
                image bigint(20) UNSIGNED DEFAULT NULL,
                icon varchar(255) DEFAULT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            )"
        ];

        // Load WordPress upgrade file for dbDelta
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Iterate over each table
        foreach ($tables as $table_key => $create_sql) {
            $table_name = $wpdb->prefix . $table_key;

            // Check if table exists
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                // Append charset collate and run dbDelta
                $create_sql .= ' ' . $wpdb->get_charset_collate() . ';';
                dbDelta($create_sql);
            }
        }
    }

    public function eazyproo_get_last_item_id($items = [])
    {
        $last_item_id = 1;
        $last_item = end($items);
        if ($last_item) {
            $last_item_id = $last_item->id;
        }
        return $last_item_id;
    }

    public function eazyproo_insert_data($table, $id, $title, $image = '', $icon = '')
    {
        global $wpdb;

        if ($this->easyproo_id_exists_in_table($table, $id)) { // if exists the update the old data

            $table_name = $wpdb->prefix . $table; // 'tic_meta_overview';

            $data = [];
            $format = [];
            $where = ['id' => $id];
            $where_format = ['%d'];

            if ($title) {
                $data['title'] = $title;
                $format[] = '%s';
            }

            
            $data['image'] = $image;
            $format[] = '%d';
        

        
            $data['icon'] = $icon;
            $format[] = '%s';

            if ($data) {
                $result = $wpdb->update($table_name, $data, $where, $format, $where_format);
                return $result !== false; // Returns true if update succeeded, false otherwise
            }
            return false; // No data to update        
        }else{ // if not exists then insert new data

            $table_name = $wpdb->prefix . $table; // 'tic_meta_overview';

            $data = [
                'title' => $title,
                'image' => $image,
                'icon' => $icon,
                'created_at' => current_time('mysql') // Adds the current timestamp
            ];

            $format = [
                '%s', // String format for title
                '%d', // Integer format for image
                '%s', // String format for title
                '%s'  // String format for created_at
            ];

            $result = $wpdb->insert($table_name, $data, $format);

            if ($result !== false) {
                return $wpdb->insert_id; // Return the ID of the inserted row
            }

            // Return false if the insert failed
            return false;
        }

    }

    public function easyproo_id_exists_in_table($table, $id)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $table;

        // Query to check if the ID exists
        $result = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE id = %d", $id));

        return $result ? true : false;
    }

    public function eazyproo_get_settings($key = '')
    {
        if ( ! $key ) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . $key;

        return $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC");
    }

    public function eazyproo_get_title_data_by_id($table = '', $id = '')
    {
        if ($id == '' || $table == ''){
            return null;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . $table;

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

        return $result ?: null;

    }

    public function eazyproo_delete_item_by_id($table = '', $id = '')
    {
        if ($id == '' || $table == ''){
            return null;
        }

        global $wpdb;

        $table_name = $wpdb->prefix . $table;

        return $wpdb->delete($table_name, ['id' => $id]);

    }
}

new TIC_Woo_Products_Custom_Fields_DB();