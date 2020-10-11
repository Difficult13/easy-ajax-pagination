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
     * The unique identifier for plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $id    The unique identifier for plugin.
     */
    private $id;

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
        $this->id = 'eap-ajax-controller-block';

	}

    /**
     * Get current parameters of plugin
     *
     * @since    1.0.0
     * @return array
     */
    private function get_options() {

        return get_option('eap_options');

    }

    /**
     * Gets the html code depending on the plugin settings
     *
     * @since    1.0.0
     * @return string
     */
    private function get_html($options, $classes = '', $id = '') {

        $type = $options['type'];
        $args = [
            'classes' => $classes,
            'id' => $id,

        ];

        /*if ($args['pagination']['delete_title']) */

        switch ($type){
            case 'button':
                $args['button_text'] = $options['button_text'];
                $args['button_loader_img'] = $options['button_loader_img'];
                break;
            case 'button_pagination':
                $args['button_text'] = $options['button_text'];
                $args['button_loader_img'] = $options['button_loader_img'];
                $args['pagination_opts'] = $options['pagination_opts'];
                break;
            case 'pagination':
                $args['pagination_opts'] = $options['pagination_opts'];
                break;
            case 'infinite':
                $args['infinite_loader_img'] = $options['infinite_loader_img'];
                break;
            default:
                $type = 'button';
                $args['button_text'] = $options['button_text'];
                $args['button_loader_img'] = $options['button_loader_img'];
                break;
        }



        return ;

    }

    /**
     * Add functional shortcodes
     *
     * @since    1.0.0
     */
    public function add_shortcodes() {

        add_shortcode('eap_ajax', function(){

            $html = '';

            //Получает текущие настройки плагина
            $options = $this->get_options();

            //Получает разметку + Устанавливает пользовательские классы контейнеру + Идентификатор
            $html = $this->get_html($options['type'], $options['classes'], $this->id);

            //Применяет фильтр к полученной верстке
            $html = apply_filters('eap_ajax_html', $html);

            return do_shortcode($html);
        });

    }

}


