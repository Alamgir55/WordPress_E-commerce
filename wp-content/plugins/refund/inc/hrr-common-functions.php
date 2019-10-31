<?php

/*
 * Common functions
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function hrr_post_slug_array() {
    $post_slug = array(
        'hr_refund_settings' => 'hr_refund_request',
    );
    return $post_slug;
}

if ( ! function_exists ( 'hr_is_refund_free_version' ) ) {
    function hr_is_refund_free_version() {
        $premium_folder = HR_REFUND_PLUGIN_PATH . '/premium' ;
        if ( ! is_dir ( $premium_folder ) )
            return true ;

        return false ;
    }
}

if (!function_exists('hr_check_is_array')) {
    /*
     * Check is Array and not empty.
     */

    function hr_check_is_array($array) {
        if (is_array($array) && !empty($array)) {
            return true;
        } else {
            return false;
        }
    }

}

if (!function_exists('hr_get_category')) {
    /*
     * all product Category.
     */

    function hr_get_category() {
        $categorylist = array();
        $categoryname = array();
        $categoryid = array();
        $particularcategory = get_terms('product_cat');
        if (!is_wp_error($particularcategory)) {
            if (!empty($particularcategory)) {
                if (is_array($particularcategory)) {
                    foreach ($particularcategory as $category) {
                        $categoryname[] = $category->name;
                        $categoryid[] = $category->term_id;
                    }
                }
                $categorylist = array_combine((array) $categoryid, (array) $categoryname);
            }
        }
        return $categorylist;
    }

}

if (!function_exists('hr_user_roles')) {
    /*
     * All User Roles.
     */

    function hr_user_roles($extra_role = false) {
        global $wp_roles;

        if (is_object($wp_roles)) {
            $role_names = $wp_roles->role_names;
            if (hr_check_is_array($role_names)) {
                foreach ($role_names as $key => $value) {
                    $userrole[] = $key;
                    $username[] = $value;
                }
            }
        }
        $user_role = array_combine((array) $userrole, (array) $username);
        if ($extra_role) {
            $user_role = array_merge($user_role, $extra_role);
        }
        return $user_role;
    }

}
if (!function_exists('hr_get_order_status')) {

    /**
     * Order Status
     */
    function hr_get_order_status() {
        if (function_exists('wc_get_order_statuses')) {
            $order_list_keys = array_keys(wc_get_order_statuses());
            $order_list_values = array_values(wc_get_order_statuses());
            $orderlist_replace = str_replace('wc-', '', $order_list_keys);
            $orderlist_combine = array_combine($orderlist_replace, $order_list_values);
        } else {
            $order_status = (array) get_terms('shop_order_status', array('hide_empty' => 0, 'orderby' => 'id'));
            if (hr_check_is_array($order_status)) {
                foreach ($order_status as $value) {
                    $status_name[] = $value->name;
                    $status_slug[] = $value->slug;
                }
            }
            $orderlist_combine = array_combine($status_slug, $status_name);
        }

        return $orderlist_combine;
    }

}

if (!function_exists('hr_format_date_time_by_wordpress')) {

    /**
     * Format Date Time based on Wordpress Date Time format
     */
    function hr_format_date_time_by_wordpress($date) {
        $formatted_date = hr_format_date_by_wordpress($date) . '/' . hr_format_time_by_wordpress($date);
        return $formatted_date;
    }

}


if (!function_exists('hr_format_date_by_wordpress')) {

    /**
     * Format Date based on Wordpress Date format
     */
    function hr_format_date_by_wordpress($date) {
        $formatted_date = date(get_option('date_format'), $date);
        return $formatted_date;
    }

}

if (!function_exists('hr_format_time_by_wordpress')) {

    /**
     * Format time based on Wordpress time format
     */
    function hr_format_time_by_wordpress($date) {
        $formatted_date = date(get_option('time_format'), $date);
        return $formatted_date;
    }

}