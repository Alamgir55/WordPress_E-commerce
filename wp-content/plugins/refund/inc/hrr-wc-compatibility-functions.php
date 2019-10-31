<?php

/*
 * Common functions for Woocommerce compatibility
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('hr_wc_format_price')) {

    function hr_wc_format_price($price, $args = array()) {
        if (function_exists('wc_price')) {
            return wc_price($price, $args);
        } else {
            return woocommerce_price($price, $args);
        }
    }

}

if (!function_exists('hr_get_product')) {

    function hr_get_product($product_id) {
        if (function_exists('wc_get_product')) {
            return wc_get_product($product_id);
        } else {
            return get_product($product_id);
        }
    }

}


if (!function_exists('hr_get_order_obj_data')) {

    function hr_get_order_obj_data($order, $name) {
        if (version_compare(WC_VERSION, '3.0.0', '>=')) {
            $date = false;
            if ('modified_date' == $name) {
                $date = true;
                $get = 'get_date_modified';
            } elseif ('order_date' == $name) {
                $date = true;
                $get = 'get_date_created';
            } elseif ('order_total' == $name) {
                $get = 'get_total';
            } else {
                $get = 'get_' . $name;
            }
            $value = $order->$get();
            if ($date) {
                $value = wc_rest_prepare_date_response($value, false);
            }
            return $value;
        } else {
            return $order->$name;
        }
    }

}

if (!function_exists('hr_get_order_obj_function')) {

    function hr_get_order_obj_function($order, $name) {
        if (version_compare(WC_VERSION, '3.0.0', '>=')) {
            $date = false;
            if ('get_order_currency' == $name) {
                $get = 'get_currency';
            }

            $value = $order->$get();

            return $value;
        } else {
            return $order->$name();
        }
    }

}

if (!function_exists('hr_get_coupon_obj_data')) {

    function hr_get_coupon_obj_data($coupon, $name) {
        if (version_compare(WC_VERSION, '3.0.0', '>=')) {
            $date = false;
            if ('expiry_date' == $name) {
                $date = true;
                $get = 'get_date_expires';
            } else {
                $get = 'get_' . $name;
            }
            $value = $coupon->$get();
            if ($date) {
                $value = wc_rest_prepare_date_response($value, false);
            }
            return $value;
        } else {
            return $coupon->$name;
        }
    }

}

if (!function_exists('hr_get_product_obj_data')) {

    function hr_get_product_obj_data($product, $name) {
        if (version_compare(WC_VERSION, '3.0.0', '>=')) {
            if ('product_type' == $name) {
                $get = 'get_type';
            } else {
                $get = 'get_' . $name;
            }
            return $product->$get();
        } else {
            return $product->$name;
        }
    }

}

if (!function_exists('hr_get_order_obj')) {

    function hr_get_order_obj($order_id) {
        if (function_exists('wc_get_order')) {
            return wc_get_order($order_id);
        } else {
            if (($result = get_post_status($order_id)) != false) {
                $result = new WC_Order($order_id);
            }
            return $result;
        }
    }

}