<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tahir.codes/
 * @since      1.0.0
 *
 * @package    Idlcheckqs
 * @subpackage Idlcheckqs/includes
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
 * @package    Idlcheckqs
 * @subpackage Idlcheckqs/includes
 * @author     Tahir Iqbal <tahiriqbal09@gmail.com>
 */
class Idlcheckqs {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Idlcheckqs_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'IDLCHECKQS_VERSION' ) ) {
			$this->version = IDLCHECKQS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'idlcheckqs';

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
	 * - Idlcheckqs_Loader. Orchestrates the hooks of the plugin.
	 * - Idlcheckqs_i18n. Defines internationalization functionality.
	 * - Idlcheckqs_Admin. Defines all hooks for the admin area.
	 * - Idlcheckqs_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-idlcheckqs-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-idlcheckqs-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-idlcheckqs-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-idlcheckqs-public.php';

		$this->loader = new Idlcheckqs_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Idlcheckqs_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Idlcheckqs_i18n();

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

		$plugin_admin = new Idlcheckqs_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_admin, 'action_woocommerce_checkout_order_processed', 999 );
		$this->loader->add_action( 'product_cat_add_form_fields', $plugin_admin, 'idlpharmcheck_add_new_meta_field', 10, 1  );
		$this->loader->add_action( 'product_cat_edit_form_fields', $plugin_admin, 'idlpharmcheck_cat_edit_form_fields', 10, 1  );
		$this->loader->add_action( 'edited_product_cat', $plugin_admin, 'idlpharmcheck_save_taxonomy_custom_meta', 10, 1  );
		$this->loader->add_action( 'create_product_cat', $plugin_admin, 'idlpharmcheck_save_taxonomy_custom_meta', 10, 1  );

		$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_admin, 'custom_checkout_field', 999 );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_admin, 'customised_checkout_field_process', 999 );

		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_admin, 'custom_checkout_field_update_order_meta', 999 );

		/*$this->loader->add_action( 'woocommerce_review_order_before_payment', $plugin_admin, 'ts_review_order_before_submit', 999 );*/
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'page_init' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Idlcheckqs_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Idlcheckqs_Loader    Orchestrates the hooks of the plugin.
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
