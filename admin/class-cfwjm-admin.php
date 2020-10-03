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
class Cfwjm_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $list_table;

	private $option_name = 'cfjm_option';

	private $menu_prefix = "cfjm_menu_";

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		include_once CFWJM_LIB_PATH . "\\class-cfwjm-list-table.php";
		include_once CFWJM_INCLUDE_PATH . "\\class-cfwjm-loader.php";
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cfwjm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cfwjm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cfwjm-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . "bootstrap-tagsinput-css", plugin_dir_url( __FILE__ ) . 'css/bootstrap-tagsinput.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cfwjm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cfwjm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cfwjm-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . "bootstrap-tagsinput", plugin_dir_url( __FILE__ ) . 'js/bootstrap-tagsinput.js', array('jquery'), $this->version, false );
		
	}

	public function add_menu(){		
		
		// add_menu_page(
		// 	__('Job Manager Custom Fields', $this->plugin_name),
		// 	__('Job Manager Custom Fields', $this->plugin_name),
		// 	'manage_options',
		// 	$this->menu_prefix . "add_field",
		// 	[$this, 'menu_add_field']
		// );
		$hook_suffix = add_submenu_page(
			'edit.php?post_type=job_listing',
			__('Job Manager Custom Fields', $this->plugin_name),
			__('Job Manager Custom Fields', $this->plugin_name),
			'manage_options',
			$this->menu_prefix . "add_field",
			[$this, 'menu_add_field']
		);		
	}

	public function cfwjm_fields_list(){		
		include_once plugin_dir_path(__FILE__) . '../lib/class-cfwjm-list-table.php';

		$arguments = array(
			'label'		=>	__( 'Custom Fields', $this->plugin_name ),
			'default'	=>	5,
			'option'	=>	'cfwjm_per_page'
		);
		
		add_screen_option( 'cfwjm_per_page', $arguments );
		
		$arr = [
			'plural'   => 'custom_fields',
			'singular' => 'custom_field',
			'ajax'     => false,
			'screen'   => null
		];

		
		$list_table = new Cfwjm_ListTable($this->plugin_name, $arr);
		$list_table->prepare_items();

		$plugin_name = $this->plugin_name;

		include_once 'partials/cfwjm-admin-listtable.php';
	}

	public function cfwjm_fields_form(){
		if(current_user_can('administrator')){
			include_once 'partials/cfwjm-admin-add-form.php';
		}
	}

	// add_field form handler
	public function cfwjm_add_field(){
		
		if ( ! isset( $_POST['cfwjm_add_message'] ) 
			|| ! wp_verify_nonce( $_POST['cfwjm_add_message'], 'cfwjm_add_field' ) 
		) {
			wp_die( 
				__( 'Invalid nonce specified', $this->plugin_name ),
				__( 'Error', $this->plugin_name ), [
					'response' 	=> 403,
					'back_link' => 'admin.php?page=' . $this->plugin_name
				]);
		} else {
			$data = $this->post_data();
			if(empty($data['label'])){
				$error_msg = sprintf(__("%s is required", $this->plugin_name), __("Label", $this->plugin_name));
				$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
				wp_redirect($_POST['_wp_http_referer']);
				return;
			}
			if(empty($data['type'])){
				$error_msg = sprintf(__("%s is required", $this->plugin_name), __("Type", $this->plugin_name));
				$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
				wp_redirect($_POST['_wp_http_referer']);
				return;
			}
			if($data['type'] === 'radio' || $data['type'] === 'select'){
				if(empty($data['meta_1'])){					
					$error_msg = sprintf(__("%s must be specified.", $this->plugin_name), __("Items", $this->plugin_name));					
					$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);					
					wp_redirect($_POST['_wp_http_referer']); 
					return;
				}
			}
			$res = Cfwjm_Db::insertField($data);
			if(empty($res)){
				$error_msg = __("Db insert failed", $this->plugin_name);
				$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
				wp_redirect($_POST['_wp_http_referer']);
				return;
			}
			$_SESSION['cfwjm_msg'] = $this->success_notice();
			wp_redirect($_POST['_wp_http_referer']);
		}
	}
	public function post_data(){
		$data = [];
		$data['label'] = empty($_POST['tag-label']) ? null : $_POST['tag-label'];
		$data['type'] = empty($_POST['tag-type']) ? null : $_POST['tag-type'];
		if($data['type'] === 'radio' || $data['type'] === 'select'){
			$data['meta_1'] = empty($_POST['tag-meta']) ? '' : $_POST['tag-meta'];
		}
		$data['placeholder'] = empty($_POST['tag-placeholder']) ? '' : $_POST['tag-placeholder'];
		$data['priority'] = empty($_POST['tag-priority']) ? 10 : $_POST['tag-priority'];
		$data['required'] = empty($_POST['tag-required']) ? 0 : $_POST['tag-required'];
		$data['description'] = empty($_POST['tag-description']) ? '' : $_POST['tag-description'];
		$data['cfwjm_tag'] = empty($_POST['tag-cfwjm-tag']) ? 'cfwjm_tag' : $_POST['tag-cfwjm-tag'];
		return $data;
	}

	public function success_notice(){
		$msg = __('Add field success', $this->plugin_name);
		$msg_body = <<<msg
<div class="notice notice-success is-dismissible">
<p>$msg</p>
</div>
msg;
		return $msg_body;
	}
	public function error_notice($msg){		
		$msg_body = <<<msg
<div class="notice notice-error is-dismissible">
<p>$msg</p>
</div>
msg;
		return $msg_body;
	}
	public function menu_add_field(){
		include_once 'partials/cfwjm-admin-menu-add-field.php';
		// include_once 'partials/cfwjm-admin-display.php';
	}
}
