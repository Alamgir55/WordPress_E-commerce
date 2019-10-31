<?php
/**
 * View Refund button
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$view_refund_label = apply_filters('hr_refund_partial_refund_label', __('View', HR_REFUND_LOCALE));
$url = wc_get_endpoint_url('hr-refund-request-view', $request_id, wc_get_page_permalink('myaccount'));
?>

<p class="view-refund">
    <a href="<?php echo esc_url($url); ?>" class="button"><?php echo $view_refund_label; ?></a>
</p>
