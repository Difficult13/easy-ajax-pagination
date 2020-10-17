(function( $ ) {
	'use strict';

	const { __, _x, _n, _nx } = wp.i18n;

	$(document).ready(init);

	function init() {
		//Проверяем есть ли хотя бы один объект плагина
		if ( typeof eap_object === "undefined" ) {
			console.error( __( 'The plugin object was not found. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		//Инициализируем переменные
		let $eap_selector = '#'+eap_object.id;

		if ( !eap_object.ajaxurl ) {
			console.error( __( 'Url not passed for ajax. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( $($eap_selector).length === 0 ) {
			console.error( __( 'The plugin markup was not output correctly. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.mode ) {
			console.error( __( 'Plugin mode is not specified correctly. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.action ) {
			console.error( __( 'No handler passed for the ajax request. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.nonce ) {
			console.error( __( 'Not transferred nonce for the ajax request. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.current_page ) {
			console.error( __( 'The page number is set incorrectly. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.max_pages ) {
			console.error( __( 'Not transferred the maximum number of pages. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.query_vars ) {
			console.error( __( 'Current request parameters were not passed. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( !eap_object.template ) {
			console.error( __( 'Page template not passed. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
			return;
		}

		if ( $(eap_object.container).length === 0 ) {
			console.error( __( 'The container selector was not found on the current page. Please provide the correct selector for the container.', 'easy-ajax-pagination' ) );
			return;
		}

		//Запускам прослушку событий в зависимости от типа EAP
		switch (eap_object.mode) {
			case 'button':
				listen_button();
				break;
			case 'scroll':
				listen_scroll();
				break;
			case 'pagination':
				listen_pagination();
				break;
			case 'button-pagination':
				listen_button_pagination();
				break;
		}

		function listen_button() {

			let doing_ajax = false;

			$(document).on('click', $eap_selector, function () {

				if (doing_ajax) return;
				doing_ajax = true;

				toggle_button_animation($($eap_selector));

				let $data = {
					'action': eap_object.action,
					'nonce': eap_object.nonce,
					'page': eap_object.current_page,
					'query_vars': eap_object.query_vars,
					'template': eap_object.template
				};

				$.ajax({
					'method': 'POST',
					'url': 'eap_object.ajaxurl',
					'data': $data,
					'success': function(data){
						data = JSON.parse(data);

						console.log(data);

						if (data.errors !== false){
							console.error( data.message );
							return;
						}

						doing_ajax = false;

						eap_object.current_page++;

						let $content = $(data.html).find(eap_object.container);
						$content.find($eap_selector).remove();

						//if ($(eap_object.container).find())

						/*
						* Нужно правильно определить куда вставить контент, ведь кнопка может быть и не в контейнере)
						* */

						//if (eap_object.current_page === eap_object.max_pages){
						if (data.is_last_page){
							$($eap_selector).remove();
						} else {
							toggle_button_animation($($eap_selector));
						}
					}
				});
			});
		}

	}



	function ajax_load($url, $data, $success, $error){
		$.ajax({
			'method': 'POST',
			'url': $url,
			'data': $data,
			'success': $success,
			'error': $error
		});
	}

	function toggle_button_animation($elem){
		$elem
			.children()
			.each(function(){
				$(this).toggle();
			});
	}

})( jQuery );
