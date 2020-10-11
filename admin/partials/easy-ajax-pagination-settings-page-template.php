<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}
?>
<div class="wrap">
    <form method="POST" action="options.php">
        <?php
        settings_fields( 'eap_group' );  // название группы опций - register_setting( $option_group )
        do_settings_sections( 'eap_group' ); // slug страницы на которой выводится форма
        submit_button();
        ?>
    </form>
</div>
