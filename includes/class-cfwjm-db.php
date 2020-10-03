<?php

/**
 * Wrap Cfwjm related db actions
 *
 * @link       https://devcrazy.com
 * @since      1.0.0
 *
 * @package    Cfwjm
 * @subpackage Cfwjm/includes
 */

/**
 * Wrap Cfwjm related db actions
 *
 * @since      1.0.0
 * @package    Cfwjm
 * @subpackage Cfwjm/includes
 * @author     Devcrazy <devcrazy@hotmail.com>
 */

global $cfwjm_db_version;
$cfwjm_db_version = '1.0';
class Cfwjm_Db {

    public function __constructor(){
        
    }
    public static function install(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $field_tbl_name = self::fieldTableName();
        $sql = "CREATE TABLE $field_tbl_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                label varchar(100) DEFAULT '' NOT NULL,
                type varchar(20) DEFAULT 'text' NOT NULL,
                placeholder varchar(100),
                priority tinyint(1) DEFAULT 10 NOT NULL,
                required tinyint(1) DEFAULT 0 NOT NULL,
                description varchar(150),
                cfwjm_tag varchar(20),                
                meta_1 text,
                meta_2 text,
                meta_3 text,
                PRIMARY KEY  (id)
        );";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('cfwjm_db_version', $cfwjm_db_version);        
    }

    
    public static function fieldTableName(){
        global $wpdb;
        return $wpdb->prefix . 'cfwjm_fields';        
    }

    public static function plugin_update_db_check(){
        global $cfwjm_db_version;
        if(get_site_option('cfwjm_db_version') != $cfwjm_db_version){
            self::install();
        }
    }
    public static function insertField($field_data){
        global $wpdb;
        return $wpdb->insert(self::fieldTableName(), $field_data);
    }
    public static function deleteField($where){
        global $wpdb;
        return $wpdb->delete(self::fieldTableName(), $where);
    }
}