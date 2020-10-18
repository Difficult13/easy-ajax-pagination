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

            if (wp_doing_ajax())
                return false;

            if ($this->is_exist)
                return false;
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
            $nonce = wp_create_nonce($this->nonce);
            $action = $this->action;
            $page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

            global $template;
            $template_name = str_replace('.php', '', str_replace(get_template_directory() . '/', '', $template) );
            $query_vars = $wp_query->query_vars;
            $max_num_pages = $wp_query->max_num_pages;

            if (!in_array($mode, $allowed_mods)) return false;

            //Записываем параметры текущего запроса в transient
            if (get_transient( $this->id ) !== false)
                delete_transient($this->id );
            $transient = [
                'query_vars' => $query_vars,
                'template' => $template_name,
                'max_num_pages' => $max_num_pages
            ];
            set_transient( $this->id, $transient );

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
                'action' => $action,
                'nonce' => $nonce,
                'page' => $page
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

        $data = [
            'errors' => false,
            'message' => '',
            'html' => '',
            'is_last_page' => false,
            'debug' => ''
        ];

        try{

            if(  !wp_verify_nonce( $_POST['nonce'], $this->nonce ) )
                throw new \Exception(esc_html__( 'Invalid verification code.', 'easy-ajax-pagination' ));

            if ( !isset($_POST['page']) || empty($_POST['page']) )
                throw new \Exception(esc_html__( 'Next page were not passed', 'easy-ajax-pagination' ));

            $page = (int) $_POST['page'];
            if ( empty($_POST['page']) )
                throw new \Exception(esc_html__( 'Invalid next page', 'easy-ajax-pagination' ));

            $transient = get_transient( $this->id );
            if ( $transient === false)
                throw new \Exception(esc_html__( 'Not found the temporary cache.', 'easy-ajax-pagination' ));


            if ( !isset($transient['query_vars']) || empty($transient['query_vars']) )
                throw new \Exception(esc_html__( 'Request parameters were not passed', 'easy-ajax-pagination' ));


            if ( !isset($transient['template']) || empty($transient['template']) )
                throw new \Exception(esc_html__( 'Page template not passed.', 'easy-ajax-pagination' ));

            if ( !isset($transient['max_num_pages']) || empty($transient['max_num_pages']) )
                throw new \Exception(esc_html__( 'Maximum number of pages not passed.', 'easy-ajax-pagination' ));


            $query_vars = $transient['query_vars'];
            $template = $transient['template'];
            $max_num_pages = $transient['max_num_pages'];

            $real_path = get_template_directory() . '/';

            if ( !file_exists( $real_path . $template . '.php' ) )
                throw new \Exception(esc_html__( 'Page template not found.', 'easy-ajax-pagination' ));

            if ( !isset($query_vars['paged']) )
                throw new \Exception(esc_html__( 'Paged param not found.', 'easy-ajax-pagination' ));

            $query_vars['paged'] = $page;

            if ($page == $max_num_pages)
                $data['is_last_page'] = true;

            query_posts($query_vars);
            ob_start();
            get_template_part($template);
            $data['html'] = ob_get_clean();
            wp_reset_query();
        }catch(\Exception $e){
            $data['errors'] = true;
            $data['message'] = $e->getMessage();
        } finally {
            $data = json_encode($data);
        }

        die($data);
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
