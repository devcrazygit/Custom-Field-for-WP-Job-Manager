<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://devcrazy.com
 * @since      1.0.0
 *
 * @package    Cfwjm
 * @subpackage Cfwjm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cfwjm
 * @subpackage Cfwjm/admin
 * @author     Devcrazy <devcrazy@hotmail.com>
 */

// namespace Cfwjm\lib;

use Cfwjm\lib\WP_List_Table;

include_once 'class-wp-list-table.php';
// require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
include_once CFWJM_INCLUDE_PATH . 'class-cfwjm-db.php';

class Cfwjm_ListTable extends WP_List_Table {
    
    protected $plugin_name;

    public function __construct($plugin_name = '', $array){
        $this->plugin_name = $plugin_name;
        parent::__construct($array);
        
    }

    public function get_columns(){
        
        $columns = array(
            'cb'        =>  '<input type="checkbox" />',            
            'label'     =>  __('Label', $this->plugin_name),
            'type'      =>  __('Type', $this->plugin_name),
            'placeholder'=> __('Placeholder', $this->plugin_name),
            'priority'  =>  __('Priority', $this->plugin_name),
            'required'  =>  __('required', $this->plugin_name),
            'description'=> __('Description', $this->plugin_name),
            'cfwjm_tag'   =>__('Cfwjm Tag', $this->plugin_name),            
        );

        return $columns;
    }
    public function no_items(){
        _e('No fields available.', $this->plugin_name);
    }
    // public function get_bulk_actions(){
    //     $actions = array(
    //         'delete'    =>  __("Delete", $this->plugin_name)
    //     );
    //     return $actions;
    // }
    public function process_bulk_action(){
        if('delete' === $this->current_action()){
            $id = $_REQUEST['id'];
            $res = Cfwjm_Db::deleteField(['id' => $id]);                        
        }
    }
    
    public function prepare_items(){
        // $this->_column_headers = $this->get_column_info();
        // $table_data = $this->fetch();
        // $this->items = $table_data;

        $this->process_bulk_action();
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $res = $this->table_data();

        $perPage = $this->get_items_per_page('cfwjm_per_page');
        $this->set_pagination_args( array(
            'total_items' => $res['count'],
            'per_page'    => $perPage
        ) );

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $res['data'];        
    }

    public function table_data(){
        global $wpdb;
        $tbl_name = Cfwjm_Db::fieldTableName();
        $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'id';
        $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';
        
        $perPage = $this->get_items_per_page('cfwjm_per_page');
        $currentPage = $this->get_pagenum();
        
        $offset = ( $currentPage - 1 ) * $perPage;
        $limit = $perPage;

        $query = "select id, label, type, placeholder, priority, required, description, cfwjm_tag 
            from $tbl_name order by $orderby $order limit $offset, $limit;";
        
        $totalItems = $wpdb->get_var("select count(*) from $tbl_name;");

        $data = $wpdb->get_results($query, ARRAY_A);
        return ['data' => $data, 'count' => $totalItems];
    }
    protected function column_cb( $item ) {
        return sprintf(		
        '<label class="screen-reader-text" for="field_' . $item['id'] . '">' . __( 'Select fields', $this->plugin_name ) . '</label>'
        . "<input type='checkbox' name='users[]' id='field_{$item['id']}' value='{$item['id']}' />"					
        );
    }

    public function column_default($item, $column_name){
        switch($column_name){
            case 'required':
                return empty($item['required']) ? "" : __("required", $this->plugin_name);
            case 'label':
                $id = $item['id'];
                $actions = array(
                    'edit'  =>  "<a href='edit.php?post_type=job_listing&page=cfjm_menu_add_field&action=edit&id=$id'>" . __("Edit", $this->plugin_name) . "</a>",
                    'delete'=>  "<a href='edit.php?post_type=job_listing&page=cfjm_menu_add_field&action=delete&id=$id'>" . __("Delete", $this->plugin_name) . "</a>"
                );
                return sprintf("%s %s", $item['cfwjm_tag'], $this->row_actions($actions));                
            default:
                return $item[$column_name];                
        }
    }
    
     /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('priority' => array('priority', false));
    }
}