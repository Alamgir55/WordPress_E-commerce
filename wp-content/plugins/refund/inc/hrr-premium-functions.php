<?php

/*
 * Premium Compatibility Functions
 */


if (!function_exists('hrr_check_order_id_valid_to_refund')) {
    /*
     * check order id valid to display refund button
     */

    function hrr_check_order_id_valid_to_refund($order_id) {
        $already_send = get_post_meta($order_id, 'hr_refund_request_already_send', true);
        //check given order id already send request
        if (!empty($already_send))
            return false;

        return apply_filters('hrr_check_order_id_valid_to_refund', true, $order_id);
    }

}
if (!function_exists('hrr_request_mode_options')) {
    /*
     * Request Mode Options
     */

    function hrr_request_mode_options() {
        $options_array = array('Amount' => 'Amount');
        $options_array = apply_filters('hrr_request_mode_options', $options_array);
        return $options_array;
    }

}

if (!function_exists('hr_get_wpml_text')) {
    /*
     * Request Mode Options
     */

    function hr_get_wpml_text($option_name, $language, $message, $context = 'HR_REFUND') {
        $options_array = apply_filters('hrr_get_wpml_text', $message, $option_name, $language, $context);
        return $options_array;
    }

}

if (!function_exists('hrr_refund_request_type')) {
    /*
     * Refund Request Type
     */

    function hrr_refund_request_type($order, $item_count, $refund_item_count, $refund_amount) {
        $type = apply_filters('hrr_refund_request_type', 'Whole Order', $order, $item_count, $refund_item_count, $refund_amount);
        return $type;
    }

}