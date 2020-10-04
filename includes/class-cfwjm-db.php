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
$cfwjm_db_version = '1.1';
class Cfwjm_Db {

    public function __constructor(){
        
    }
    public static function install(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $field_tbl_name = self::fieldTableName();
        $sql = "CREATE TABLE $field_tbl_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                field_key varchar(30) DEFAULT 'cfwjm_key' NOT NULL, 
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
    public static function updateField($field_data, $id){
        global $wpdb;
        return $wpdb->update(self::fieldTableName(), $field_data, ['id' => $id]);
    }
    public static function deleteField($where){
        global $wpdb;
        return $wpdb->delete(self::fieldTableName(), $where);
    }
    public static function get($id){
        global $wpdb;
        $tbl_name = self::fieldTableName();        
        return $wpdb->get_row($wpdb->prepare("select * from $tbl_name where id=%d", $id), ARRAY_A );
    }
    public static function getWhere($where, $glue = "and"){
        global $wpdb;
        $tbl_name = self::fieldTableName();        

        $where_phrase = [];
        $vals = [];
        foreach($where as $k => $v){
            $where_phrase[] = $k . "=%s";
            $vals[] = $v;
        }
        $where = "where " . implode(" ". $glue . " ", $where_phrase);        
        return $wpdb->get_row($wpdb->prepare("select * from $tbl_name $where", $vals), ARRAY_A);
    }
    public static function getAll(){
        global $wpdb;
        $tbl_name = self::fieldTableName();
        return $wpdb->get_results("select * from $tbl_name", ARRAY_A );
    }
}