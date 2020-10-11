<?php

namespace Difficult13\EasyAjaxPagination\Includes;

/**
 * Fired during plugin deactivation
 * @since      1.0.0
 */

class EasyAjaxPaginationDeactivator {

	/**
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        unregister_setting('eap-options', 'eap_options');
	}

}
