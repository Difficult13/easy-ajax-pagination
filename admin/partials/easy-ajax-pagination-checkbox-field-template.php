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
<input
    type="checkbox"
    id="<?php echo esc_attr($args['id']); ?>"
    name="<?php echo esc_attr($args['name']); ?>"
    value="1"
    <?php echo esc_attr($args['checked']); ?>
/>
