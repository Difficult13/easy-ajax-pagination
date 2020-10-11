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
<button class="<?php echo esc_attr($args['class']); ?>" id="<?php echo esc_attr($args['id']); ?>" class="eap-show-more-button">
    <?php echo esc_html($args['button_text']); ?>
    <img class="eap-loader" src="<?php echo esc_url($args['loader']); ?>">
</button>
