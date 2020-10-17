<?php

/**
 * Provide a pagination template for the plugin
 *
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}
?>

<div class="eap-container <?php echo esc_attr($args['class']); ?>" id="<?php echo esc_attr($args['id']); ?>">
    <?php echo wp_kses_post($args['pagination']); ?>
</div>
