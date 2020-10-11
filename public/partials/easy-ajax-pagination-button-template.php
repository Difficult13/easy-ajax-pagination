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
<button class="eap-show-more-button">
    <?php echo esc_html($args['button_text']); ?>
    <img src="<?php echo esc_url($args['button_loader_img']); ?>">
</button>
