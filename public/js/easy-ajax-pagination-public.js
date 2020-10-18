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

		if ( !eap_object.page ) {
			console.error( __( 'Not transferred current page. Please, report this error to the plugin developer.', 'easy-ajax-pagination' ) );
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

				eap_object.page++;

				let $data = {
					'action': eap_object.action,
					'nonce': eap_object.nonce,
					'page' : eap_object.page
				};

				$.ajax({
					'method': 'POST',
					'url': eap_object.ajaxurl,
					'data': $data,
					'success': function(data){
 						try{
							data = JSON.parse(data);
						}catch(err){
							console.error( __( 'Invalid json response.', 'easy-ajax-pagination' ) );
 							return;
						}

						if (data.errors !== false){
							console.error( 'Easy Ajax Pagination error: ' + data.message );
							return;
						}

						doing_ajax = false;

						let $html = $($.parseHTML( data.html ));
						let $content = $html.filter('#site-content').children();

						$content.find($eap_selector).remove();

						if ( $(eap_object.container).children(':last')[0] === $($eap_selector)[0] ){
							$($eap_selector).before($content);
						} else {
							$(eap_object.container).append($content);
						}

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
