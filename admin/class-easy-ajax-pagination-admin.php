<?php

namespace Difficult13\EasyAjaxPagination\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 */
class EasyAjaxPaginationAdmin {

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

    /**
     * Default options of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $defaults    Default options of this plugin.
     */
    private $defaults;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $defaults ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->defaults = $defaults;

	}

    /**
     * Get current parameters of plugin
     *
     * @since    1.0.0
     * @return array
     */
    private function get_options() {

        return wp_parse_args(get_option('eap_options'), $this->defaults);

    }

    /**
     * Register plugin's options
     *
     * @since    1.0.0
     */
    public function register_options() {
        register_setting( 'eap_group', 'eap_options', [
            'sanitize_callback' => [$this, 'sanitize_options']
        ]);

        $current_options = $this->get_options();

        add_settings_section(
            'eap_options_section',
            esc_html__('EAP Settings', 'easy-ajax-pagination'),
            '',
            'eap_group'
        );

        add_settings_field('eap_option_button_text', esc_html__('Button text', 'easy-ajax-pagination'), [$this, 'display_text_field'], 'eap_group', 'eap_options_section', [
            'id' => 'eap_option_button_text',
            'name' => 'eap_options[button_text]',
            'value' => !isset( $current_options['button_text']) ? 0 : sanitize_text_field($current_options['button_text']),
            'label_for' => 'eap_option_button_text'
        ]);

        add_settings_field('eap_option_loader', esc_html__('Loader img', 'easy-ajax-pagination'), [$this, 'display_text_field'], 'eap_group', 'eap_options_section', [
            'id' => 'eap_option_loader',
            'name' => 'eap_options[loader]',
            'value' => !isset( $current_options['loader']) ? 0 : sanitize_text_field($current_options['loader']),
            'label_for' => 'eap_option_loader'
        ]);

        add_settings_field('eap_option_remove_pt', esc_html__('Remove title of pagination?', 'easy-ajax-pagination'), [$this, 'display_checkbox_field'], 'eap_group', 'eap_options_section', [
            'id' => 'eap_option_remove_pt',
            'name' => 'eap_options[remove_pt]',
            'checked' => (isset($current_options['remove_pt']) && $current_options['remove_pt'] == 1) ? 'checked' : '',
            'label_for' => 'eap_option_remove_pt'
        ]);

    }

    /**
     * Add plugin page
     *
     * @since    1.0.0
     */
    public function add_plugin_page() {
        add_options_page(esc_html__('EAP Settings', 'easy-ajax-pagination'), esc_html__('Easy Ajax Pagination', 'easy-ajax-pagination'), 'manage_options', 'eap_group', [$this, 'display_settings_page']);
    }

    /**
     * Sanitize callback for options
     *
     * @since    1.0.0
     */
    public function sanitize_options( $options ) {
        $options['button_text'] = sanitize_text_field($options['button_text']);
        $options['loader'] = sanitize_text_field($options['loader']);
        $options['remove_pt'] = absint($options['remove_pt']);
        return $options;
    }

    /**
     * Display settings page
     *
     * @since    1.0.0
     */
    public function display_text_field( $args ) {
        $this->get_template_part('text-field', $args);
    }

    /**
     * Display text field
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        $this->get_template_part('settings-page');
    }

    /**
     * Display checkbox field
     *
     * @since    1.0.0
     */
    public function display_checkbox_field( $args ) {
        $this->get_template_part('checkbox-field', $args);
    }

    /**
     * Get the html template
     *
     * @since    1.0.0
     * @return string
     */
    private function get_template_part($part, $args = []) {
        $path = plugin_dir_path( dirname( __FILE__ ) ) . sprintf('admin/partials/%s-%s-template.php', $this->plugin_name, $part);
        if ( !file_exists($path)) return false;
        require $path;
    }

}


