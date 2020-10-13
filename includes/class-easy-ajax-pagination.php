<?php

namespace Difficult13\EasyAjaxPagination\Includes;

use Difficult13\EasyAjaxPagination\Includes\EasyAjaxPaginationLoader;
use Difficult13\EasyAjaxPagination\Includes\EasyAjaxPaginationI18n;
use Difficult13\EasyAjaxPagination\Admin\EasyAjaxPaginationAdmin;
use Difficult13\EasyAjaxPagination\Open\EasyAjaxPaginationPublic;

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
 */
class EasyAjaxPagination {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      EasyAjaxPaginationLoader    $loader    Maintains and registers all hooks for the plugin.
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
     * Default options of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $defaults    Default options of this plugin.
     */
    private $defaults;

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
		if ( defined( 'EAP_VERSION' ) ) {
			$this->version = EAP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'easy-ajax-pagination';

        $this->defaults = [
            'button_text' => esc_html__('Show more', 'easy-ajax-pagination'),
            'loader' => esc_url(plugins_url( $this->plugin_name . '/public/images/loader.gif' )),
            'remove_pt' => 0,
        ];

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-easy-ajax-pagination-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-easy-ajax-pagination-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-easy-ajax-pagination-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-easy-ajax-pagination-public.php';

		$this->loader = new EasyAjaxPaginationLoader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the EasyAjaxPaginationI18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new EasyAjaxPaginationI18n();

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

		$plugin_admin = new EasyAjaxPaginationAdmin( $this->get_plugin_name(), $this->get_version(), $this->get_defaults() );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_page' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_options' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new EasyAjaxPaginationPublic( $this->get_plugin_name(), $this->get_version(), $this->get_defaults() );

        $this->loader->add_action( 'init', $plugin_public, 'add_shortcodes' );
        $this->loader->add_filter( 'navigation_markup_template', $plugin_public, 'remove_pagination_title', 10, 1 );

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
     *
     * Get default options of plugin
     *
     * @since     1.0.0
     * @return    array    The name of the plugin.
     */
    public function get_defaults() {
        return $this->defaults;
    }

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    EasyAjaxPaginationLoader    Orchestrates the hooks of the plugin.
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
