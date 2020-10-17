<?php

namespace Difficult13\EasyAjaxPagination\Open;

use PHPMailer\PHPMailer\Exception;

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
     * The nonce for ajax.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $nonce    The nonce for ajax.
     */
    private $nonce;

    /**
     * The action for ajax.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $action    The action for ajax.
     */
    private $action;

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
     * The variable that indicates the existence of a shortcode
     *
     * @since    1.0.0
     * @access   private
     * @var      bool    $is_exist    The variable that indicates the existence of a shortcode
     */
    private $is_exist;

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

        $this->is_exist = false;
        $this->nonce = 'eap-ajax-load-security';
        $this->action = 'eap_load_ajax';

	}

    public function register_styles() {
        wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-ajax-pagination-public.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function register_scripts() {
        wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-ajax-pagination-public.js', array( 'jquery' ), $this->version, false );
        wp_set_script_translations( $this->plugin_name, 'easy-ajax-pagination' );
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
     * Gets the html code depending on the plugin settings
     *
     * @since    1.0.0
     * @return string
     */
    private function get_html($mode, $class = '' ) {

        //Получает текущие настройки плагина
        $options = $this->get_options();

        $args = [
            'id' => $this->id,
            'class' => $class
        ];

        switch ($mode) {
            case 'button':
                $args['button_text'] = $options['button_text'];
                $args['loader'] = $options['loader'];
                break;
            case 'scroll':
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

            if ($this->is_exist) return false;
            $this->is_exist = true;

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

            $current_page = get_query_var( 'paged' ) ? get_query_var('paged') : 1;
            $max_pages = (int) $wp_query->max_num_pages;
            $vars = json_encode($wp_query->query_vars);
            $nonce = wp_create_nonce($this->nonce);
            $action = $this->action;

            global $template;
            $template_name = str_replace(get_template_directory() . '/', '', $template);

            if (!in_array($mode, $allowed_mods)) return false;

            //Получаем разметку для выбранного режима
            $html = $this->get_html( $mode, $class );

            //Применяет фильтр к полученной верстке
            $html = apply_filters('eap_shortcode_html', $html);

            //Включаем стили и скрипты
            if (apply_filters( 'eap_stylesheets', true))
                wp_enqueue_style( $this->plugin_name);

            wp_enqueue_script( $this->plugin_name);
            wp_localize_script( $this->plugin_name, 'eap_object', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'id' => $this->id,
                'container' => $container,
                'mode' => $mode,
                'current_page' => $current_page,
                'max_pages' => $max_pages,
                'query_vars' => $vars,
                'template' => $template_name,
                'action' => $action,
                'nonce' => $nonce
            ] );

            return do_shortcode($html);
        });

    }

    /**
     * Load ajax handler
     *
     * @since    1.0.0
     */
    public function load_ajax() {
        /*
         * Получаем запрос, страницу и шаблон
         *
         * Отправляем все это в query_posts
         *
         * Подключаем отправленный шаблон (только перед этим надо убедится что он вп-шный, хотя get_template_part ищет только в theme, так что безоасно я думаю)
         *
         * Получаем разметку и возвращаем
         *
					'page': eap_object.current_page,
					'query_vars': eap_object.query_vars,
					'template': eap_object.template
         *
         * */

        $data = [
            'errors' => false,
            'message' => '',
            'html' => '',
            'is_last_page' => false
        ];

        try{

            if(  !wp_verify_nonce( $_POST['nonce'], $this->nonce ) ){
                throw new Exception(esc_html__( 'Invalid verification code.', 'easy-ajax-pagination' ));
            }

            if ( !isset($_POST['page']) || empty($_POST['page']) ){
                throw new Exception(esc_html__( 'The current page was not passed.', 'easy-ajax-pagination' ));
            }

            if ( !isset($_POST['query_vars']) || empty($_POST['query_vars']) ){
                throw new Exception(esc_html__( 'Request parameters were not passed', 'easy-ajax-pagination' ));
            }

            if ( !isset($_POST['template']) || empty($_POST['template']) ){
                throw new Exception(esc_html__( 'Page template not passed.', 'easy-ajax-pagination' ));
            }

            $page = (int) $_POST['page'];
            $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
            $template = sanitize_text_field($_POST['template']);

        }catch(Exception $e){
            $data['errors'] = true;
            $data['message'] = $e->getMessage();
        } finally {
            $data = json_encode($data);
            die($data);
        }



        die;
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
