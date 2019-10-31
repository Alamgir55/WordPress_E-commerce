<?php
/**
 * Whole Refund button
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$whole_refund_label = apply_filters('hr_refund_whole_refund_label', get_option('hr_refund_full_order_button_label', 'Whole Refund'));
$url = wc_get_endpoint_url('hr-refund-request-form', $order->get_id(), wc_get_page_permalink('myaccount'));
?>

<p class="partial-refund">
    <a href="<?php echo esc_url($url); ?>" class="button"><?php echo $whole_refund_label; ?></a>
</p>
