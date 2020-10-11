<?php

namespace Difficult13\EasyAjaxPagination\Open;

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 */

class EasyAjaxPaginationPublic {

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
     * The unique identifier for plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $id    The unique identifier for plugin.
     */
    private $id;

    /**
     * The list of allowed modes of plugin
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $allowes_modes    The list of allowed modes
     */
    private $allowes_modes;

    /**
     * The list of default parameters for pagination
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $pagination_args    The list of default parameters for pagination
     */
    private $pagination_args;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $defaults ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->defaults = $defaults;

		$this->id = 'eap-ajax-controller-block';
		$this->allowes_modes = ['button', 'scroll', 'pagination', 'button-pagination'];
        $this->pagination_args = [
            'show_all'           => false,
            'end_size'           => 1,
            'mid_size'           => 1,
            'prev_next'          => true,
            'prev_text'          => __('« Previous', 'easy-ajax-pagination'),
            'next_text'          => __('Next »', 'easy-ajax-pagination'),
            'add_args'           => false,
            'add_fragment'       => '',
            'screen_reader_text' => __( 'Posts navigation', 'easy-ajax-pagination' ),
            'aria_label'         => __( 'Posts', 'easy-ajax-pagination' ),
            'class'              => 'pagination',
        ];

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        //Предусмотреть, чтобы стили и скрипты подключались только на страницах с шорткодом
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-ajax-pagination-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        //Предусмотреть, чтобы стили и скрипты подключались только на страницах с шорткодом
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-ajax-pagination-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Gets the html code depending on the plugin settings
     *
     * @since    1.0.0
     * @return string
     */
    private function get_html($mode, $class = '') {

        //Получает текущие настройки плагина
        $options = $this->get_options();

        $args = [
            'id' => $this->id,
            'class' => $class,
        ];

        switch ($mode) {
            case 'button':
                $args['button_text'] = $options['button_text'];
                $args['loader'] = $options['loader'];
                break;
            case 'infinite':
                $args['loader'] = $options['loader'];
                break;
            case 'pagination':
                $pagination_args = apply_filters('eap_pagination_args', $this->pagination_args);
                $args['pagination'] = get_the_posts_pagination($pagination_args);
                break;
            case 'button-pagination':
                $pagination_args = apply_filters('eap_pagination_args', $this->pagination_args);
                $args['pagination'] = get_the_posts_pagination($pagination_args);
                $args['button_text'] = $options['button_text'];
                $args['loader'] = $options['loader'];
                break;
        }
        ob_start();
        $this->get_template_part($mode, $args);
        return ob_get_clean();

    }

    /**
     * Get the html template
     *
     * @since    1.0.0
     * @return string
     */
    private function get_template_part($part, $args = []) {
        $path = plugin_dir_path( dirname( __FILE__ ) ) . sprintf('public/partials/%s-%s-template.php', $this->plugin_name, $part);
        if ( !file_exists($path)) return false;
        require $path;
    }

    /**
     * Add functional shortcodes
     *
     * @since    1.0.0
     */
    public function add_shortcodes() {

        add_shortcode('eap_controls', function( $atts ){

            global $wp_query;
            $total  = (int) (isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1);
            if ( $total < 2 ) return false;

            $atts = shortcode_atts( array(
                'container' => '#site-content',
                'mode' => 'button',
                'class' => ''
            ), $atts );

            $allowed_mods = $this->allowes_modes;

            $container = sanitize_text_field($atts['container']);
            $class = sanitize_text_field($atts['class']);
            $mode = $atts['mode'];

            if (!in_array($mode, $allowed_mods)) return false;

            //Получаем разметку для выбранного режима
            $html = $this->get_html($mode, $class);

            //Применяет фильтр к полученной верстке
            $html = apply_filters('eap_shortcode_html', $html);

            return do_shortcode($html);
        });

    }

    /**
     * Remove pagination title
     *
     * @since    1.0.0
     */
    public function remove_pagination_title($template){

        $options = $this->get_options();

        if (isset($options['remove_pt']) && $options['remove_pt'] == 1):
            $template = '
                <nav class="navigation %1$s" role="navigation">
                    <div class="nav-links">%3$s</div>
                </nav>
            ';
        endif;

        return $template;
    }



}
