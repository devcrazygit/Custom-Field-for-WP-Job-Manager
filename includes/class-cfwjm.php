<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://devcrazygit.github.io/
 * @since      1.0.0
 *
 * @package    Cfwjm
 * @subpackage Cfwjm/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cfwjm
 * @subpackage Cfwjm/includes
 * @author     Devcrazy <devcrazy@hotmail.com>
 */
class Cfwjm {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cfwjm_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $custom_routes = [];

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CFWJM_VERSION' ) ) {
			$this->version = CFWJM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cfwjm';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cfwjm_Loader. Orchestrates the hooks of the plugin.
	 * - Cfwjm_i18n. Defines internationalization functionality.
	 * - Cfwjm_Admin. Defines all hooks for the admin area.
	 * - Cfwjm_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cfwjm-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cfwjm-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cfwjm-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cfwjm-public.php';

		$this->loader = new Cfwjm_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cfwjm_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cfwjm_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Cfwjm_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );			
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_menu', 12);
		
		$this->loader->add_action('cfwjm_fields_list', $plugin_admin, 'cfwjm_fields_list', 1);
		$this->loader->add_action('cfwjm_fields_form', $plugin_admin, 'cfwjm_fields_form');
		$this->loader->add_action('cfwjm_fields_edit_form', $plugin_admin, 'cfwjm_fields_edit_form');
		$this->loader->add_action('admin_post_cfwjm_add_field', $plugin_admin, 'cfwjm_add_field');
		$this->loader->add_action('admin_post_cfwjm_edit_field', $plugin_admin, 'cfwjm_edit_field');		
		$this->loader->add_action('job_manager_input_tags', $plugin_admin, 'cfwjm_checkbox_tags_input', 10, 2);
		$this->loader->add_action('job_manager_input_checkbox_group', $plugin_admin, 'cfwjm_checkbox_checkbox_group', 10, 2);

		// WP Job Manager hook
		$this->loader->add_filter( 'job_manager_job_listing_data_fields', $plugin_admin, 'cfwjm_render' ); // #
		$this->loader->add_filter( 'submit_job_form_fields', $plugin_admin, 'cfwjm_submit_form_fields'); // #	
		// $this->loader->add_filter( 'manage_job_listing_posts_custom_column', $plugin_admin, 'display_columns' );			
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Cfwjm_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );		
		// Dashboard: Job Listings > Jobs filters
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cfwjm_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
