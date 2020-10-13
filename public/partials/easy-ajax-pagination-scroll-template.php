<?php

/**
 * Provide a button template for the plugin
 *
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}
?>
<div data-container="<?php echo esc_attr($args['container']); ?>" class="eap-container <?php echo esc_attr($args['class']); ?>" id="<?php echo esc_attr($args['id']); ?>">
    <img class="eap-loader" src="<?php echo esc_url($args['loader']); ?>">
</div>

