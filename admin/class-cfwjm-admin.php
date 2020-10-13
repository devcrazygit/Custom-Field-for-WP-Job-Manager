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

	const PLUGIN_SLUG = "edit.php?post_type=job_listing";

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

	private $backlink_page = "";

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
		$this->backlink_page = self::PLUGIN_SLUG . '&page=cfjm_menu_add_field';
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
			self::PLUGIN_SLUG,
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
			$action = 'cfwjm_add_field';
			$nounce_name = "cfwjm_add_message";
			$val = [];
			$val['field_key'] = '';
			$val['label'] = "";
			$val['type'] = 'text';
			$val['meta_1'] = '';
			$val['placeholder'] = '';
			$val['priority'] = 10;
			$val['required'] = 0;
			$val['description'] = '';
			$val['cfwjm_tag'] = 'cfwjm_tag';
			
			include_once 'partials/cfwjm-admin-add-form.php';
		}
	}
	public function cfwjm_fields_edit_form(){
		if(current_user_can('administrator')){
			$action = 'cfwjm_edit_field';
			$nounce_name = "cfwjm_edit_message";			
			$id = $_REQUEST['id'];
			$val = Cfwjm_Db::get($id);
			if(empty($val)){
				wp_die(__("No such a field", $this->plugin_name));
			}			
			include_once 'partials/cfwjm-admin-add-form.php';
		}
	}
	public function cfwjm_edit_field(){
		if ( ! isset( $_POST['cfwjm_edit_message'] ) 
			|| ! wp_verify_nonce( $_POST['cfwjm_edit_message'], 'cfwjm_edit_field') || empty($_REQUEST['id'])){
			wp_die( 
				__( 'Invalid nonce specified', $this->plugin_name ),
				__( 'Error', $this->plugin_name ), [
					'response' 	=> 403,
					'back_link' => $this->backlink_page
				]);
		}else{
			$data = $this->validate();
			if(empty($data)){
				return;
			}
			$id = $_REQUEST['id'];
			$res = Cfwjm_Db::updateField($data, $id);
			if($res === false){
				$_SESSION['cfwjm_msg'] = $this->error_notice(__("Update failed", $this->plugin_name));
			}
			$_SESSION['cfwjm_msg'] = $this->success_notice(__("Successfully updated", $this->plugin_name));
			wp_redirect($this->backlink_page);
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
					'back_link' => $this->backlink_page
				]);
		} else {
			$data = $this->validate();
			if(empty($data)){
				return;
			}
			
			$res = Cfwjm_Db::insertField($data);
			if($res === false){
				$error_msg = __("Db insert failed", $this->plugin_name);
				$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
				wp_redirect($this->backlink_page);
				return;
			}
			$_SESSION['cfwjm_msg'] = $this->success_notice();
			wp_redirect($this->backlink_page);
		}
	}
	public function post_data(){
		$data = [];
		$data['field_key'] = empty($_POST['tag-field-key']) ? null : $_POST['tag-field-key'];
		$data['label'] = empty($_POST['tag-label']) ? null : $_POST['tag-label'];
		$data['type'] = empty($_POST['tag-type']) ? null : $_POST['tag-type'];
		// if($data['type'] === 'radio' || $data['type'] === 'select' || $data['type'] === 'checkbox'){
		$data['meta_1'] = empty($_POST['tag-meta']) ? '' : $_POST['tag-meta'];
		// }
		$data['placeholder'] = empty($_POST['tag-placeholder']) ? '' : $_POST['tag-placeholder'];
		$data['priority'] = empty($_POST['tag-priority']) ? 10 : $_POST['tag-priority'];
		$data['required'] = empty($_POST['tag-required']) ? 0 : $_POST['tag-required'];
		$data['description'] = empty($_POST['tag-description']) ? '' : $_POST['tag-description'];
		$data['cfwjm_tag'] = empty($_POST['tag-cfwjm-tag']) ? 'cfwjm_tag' : $_POST['tag-cfwjm-tag'];
		
		return $data;
	}

	public function validate(){
		$data = $this->post_data();
		if(empty($data['label'])){
			$error_msg = sprintf(__("%s is required", $this->plugin_name), __("Label", $this->plugin_name));
			$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
			wp_redirect($this->backlink_page);
			return null;
		}
		if(empty($data['field_key'])){
			$error_msg = sprintf(__("%s is required", $this->plugin_name), __("Key", $this->plugin_name));
			$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
			wp_redirect($this->backlink_page);
			return null;
		}
		if(empty($data['type'])){
			$error_msg = sprintf(__("%s is required", $this->plugin_name), __("Type", $this->plugin_name));
			$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
			wp_redirect($this->backlink_page);
			return null;
		}
		if($data['type'] === 'radio' || $data['type'] === 'select'){
			if(empty($data['meta_1'])){					
				$error_msg = sprintf(__("%s must be specified.", $this->plugin_name), __("Items", $this->plugin_name));					
				$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);					
				wp_redirect($this->backlink_page); 
				return null;
			}
		}
		$existing = Cfwjm_Db::getWhere(['label' => $data['label'], 'field_key' => $data['field_key']], "or");
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : -1;			
		if(!empty($existing) && $existing['id'] != $id){			
			$error_msg = __("Label and Key Name must be unique", $this->plugin_name);
			$_SESSION['cfwjm_msg'] = $this->error_notice($error_msg);
			wp_redirect($this->backlink_page);
			return null;
		}
		return $data;
	}

	public function success_notice($msg = null){
		if(empty($msg)){
			$msg = __('Add field success', $this->plugin_name);
		}
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
		if( isset($_REQUEST['act']) && $_REQUEST['act'] === 'edit'){
			include_once 'partials/cfwjm-admin-edit-page.php';
			return;
		}

		include_once 'partials/cfwjm-admin-menu-add-field.php';
		// include_once 'partials/cfwjm-admin-display.php';
	}
	public function cfwjm_render($fields){
		$cfields = Cfwjm_Db::getAll();
		if(count($cfields) === 0){
			return $fields;
		}
		// $fields['_test_key'] = [
		// 	'label'	=>	'test_label',
		// 	'type'	=>	'text',
		// 	'placeholder'	=>	'test_placeholder',
		// 	'description'	=>	'test_descrtiption',
		// 	'classes'	=>	['job-manager-datepicker']
		// ];
		foreach($cfields as $field){
			$fields['_' . $field['field_key']] = [
				'label'	=>	$field['label'],
				'type'	=>	$field['type'],
				'placeholder'	=>	$field['placeholder'],
				'description'	=>	$field['description']
			];
			if(!empty($field['meta_1'])){
				$meta1 = $field['meta_1'];
				$options = explode(",", $meta1);

				$field_options= [];				
				foreach($options as $option){
					$field_options[$option] = $option;
				}
				$fields['_' . $field['field_key']]['options'] = $field_options;
				$fields['_' . $field['field_key']]['default'] = $option;				
			}

			switch($field['type']){
				case 'date':
					$fields['_' . $field['field_key']]['classes'] = ['job-manager-datepicker'];
					$fields['_' . $field['field_key']]['type'] = 'text';				
				break;
			}
		}
		
		  return $fields;
	}
	function cfwjm_checkbox_tags_input($key, $field){
		if ( ! empty( $field['name'] ) ) {
			$name = $field['name'];
		} else {
			$name = $key;
		}
		if ( ! empty( $field['classes'] ) ) {
			$classes = implode( ' ', is_array( $field['classes'] ) ? $field['classes'] : [ $field['classes'] ] );
		} else {
			$classes = '';
		}		
			?>			
			<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( wp_strip_all_tags( $field['label'] ) ); ?>:
			<?php if ( ! empty( $field['description'] ) ) : ?>
				<span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span>
			<?php endif; ?>
			</label>
				<input name="<?php echo esc_attr( $name ); ?>" type="text" 
					class="<?php echo esc_attr( $classes ); ?>"
					id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>"
					data-role="tagsinput"/>				
			</p>
			<?php
	}
	function retrieve_columns($columns){
		$cfields = Cfwjm_Db::getAll();
		if(count($cfields) === 0){
			return $columns;
		}
		foreach($cfields as $field){
			$columns[$field['field_key']] = $field['label'];
		}
		// $columns['job_salary']         = __( 'Salary', 'wpjm-extra-fields' );
		return $columns;	
	}
	// public function display_columns($column){
	// 	global $post;

	// 	$cfields = Cfwjm_Db::getAll();

	// 	switch ($column) {
	// 		case 'job_salary':
			
	// 		$salary = get_post_meta( $post->ID, '_job_salary', true );
			
	// 		if ( !empty($salary)) {
	// 			echo $salary;
	// 		} else {
	// 			echo '-';
			
	// 		}
	// 		break;
	// 	}

	// 	return $column;
	// }
}
