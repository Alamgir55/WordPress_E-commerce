<?php

/*
 * Common functions for post type
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('hr_get_posts')) {

    function hr_get_posts($args) {
        $post = array();
        $query_post = new WP_Query($args);
        if (isset($query_post->posts)) {
            if (hr_check_is_array($query_post->posts)) {
                $post = $query_post->posts;
            }
        }
        return $post;
    }

}

if (!function_exists('hr_refund_get_post_status')) {

    function hr_refund_get_post_status($post_id) {
        $post_status = get_post_status($post_id);
        $status = hr_refund_post_status($post_status);

        return $status;
    }

}

if (!function_exists('hr_refund_post_status')) {

    function hr_refund_post_status($post_status) {
        $status = '';
        if ($post_status == 'hr-refund-new') {
            $status = 'New';
        } elseif ($post_status == 'hr-refund-accept') {
            $status = 'Accepted';
        } elseif ($post_status == 'hr-refund-reject') {
            $status = 'Rejected';
        } elseif ($post_status == 'trash') {
            $status = 'Trashed';
        } elseif ($post_status == 'hr-refund-processing') {
            $status = 'Processing';
        } elseif ($post_status == 'hr-refund-on-hold') {
            $status = 'On-Hold';
        }

        return $status;
    }

}

if (!function_exists('hr_insert_refund_request_post')) {

    function hr_insert_refund_request_post($meta_args, $post_args = array()) {

        $post_defaults = array(
            'post_type' => 'hr_refund_request',
            'post_status' => 'hr-refund-new'
        );


        $post_args = wp_parse_args($post_args, $post_defaults);
        //Insert Post 
        $post_id = wp_insert_post($post_args);

        $meta_defaults = array(
            'hr_refund_order_id' => false,
            'hr_refund_user_details' => false,
            'hr_refund_request_as' => false,
            'hr_refund_request_type' => false,
            'hr_refund_request_date' => false,
            'hr_refund_request_total' => false,
            'hr_refund_user_name' => false,
            'hr_refund_user_email' => false,
            'hr_refund_line_items' => false,
            'hr_refund_line_item_ids' => false,
            'hr_refund_current_language' => false,
            'hr_refund_request_old_status' => false,
            'hr_refund_staus_modified_date' => current_time('timestamp'),
        );

        $postmeta_args = wp_parse_args($meta_args, $meta_defaults);
        //update postmeta
        hr_refund_update_request_post_meta($postmeta_args, $post_id);

        return $post_id;
    }

}

if (!function_exists('hr_refund_update_request_post_meta')) {

    function hr_refund_update_request_post_meta($postmeta_args, $post_id) {
        $meta_defaults = array(
            'hr_refund_order_id' => false,
            'hr_refund_user_details' => false,
            'hr_refund_request_as' => false,
            'hr_refund_request_type' => false,
            'hr_refund_request_date' => false,
            'hr_refund_request_total' => false,
            'hr_refund_user_name' => false,
            'hr_refund_user_email' => false,
            'hr_refund_line_items' => false,
            'hr_refund_line_item_ids' => false,
            'hr_refund_current_language' => false,
            'hr_refund_request_old_status' => false,
            'hr_refund_staus_modified_date' => false,
        );
        $postmeta_args = wp_parse_args($postmeta_args, $meta_defaults);
        //Update postmeta
        if (hr_check_is_array($postmeta_args)) {
            foreach ($postmeta_args as $meta_name => $value) {
                if ($value !== false)
                    update_post_meta($post_id, $meta_name, $value);
            }
        }
    }

}

if (!function_exists('hr_refund_create_request_post_obj')) {

    function hr_refund_create_request_post_obj($post_id, $type = 'object') {
        $request_array = array(
            'order_id' => 'hr_refund_order_id',
            'user_id' => 'hr_refund_user_details',
            'request_as' => 'hr_refund_request_as',
            'type' => 'hr_refund_request_type',
            'date' => 'hr_refund_request_date',
            'line_items' => 'hr_refund_line_items',
            'line_item_ids' => 'hr_refund_line_item_ids',
            'current_language' => 'hr_refund_current_language',
            'user_request_total' => 'hr_refund_request_total',
            'old_status' => 'hr_refund_request_old_status',
            'staus_modified_date' => 'hr_refund_staus_modified_date',
        );
        $post = get_post($post_id);
        $request = hr_refund_create_post_array($post_id, $request_array);
        $request['id'] = $post_id;
        $request['status'] = $post->post_status;
        $request['reason'] = $post->post_content;
        $request['title'] = $post->post_title;
        $request['created_date'] = $post->post_date;
        $request = apply_filters('hr_refund_request_obj', $request, $post_id, $type);

        return $type == 'object' ? (object) $request : $request;
    }

}

if (!function_exists('hr_refund_create_post_array')) {

    function hr_refund_create_post_array($post_id, $array) {
        $post_array = array();
        if (hr_check_is_array($array)) {
            foreach ($array as $key => $value) {
                $post_array[$key] = get_post_meta($post_id, $value, true);
            }
        }
        return $post_array;
    }

}

if (!function_exists('hr_refund_request_children_posts')) {

    function hr_refund_request_children_posts($parent_id, $type = 'object') {

        $args = array(
            'post_parent' => $parent_id,
            'post_type' => HR_REFUND_POST_TYPE,
            'numberposts' => -1,
            'post_status' => array('hr-request-replied'),
            'orderby' => 'ID',
            'order' => 'ASC',
        );
        return get_children($args);
    }

}

if (!function_exists('hr_refund_insert_children_post')) {

    function hr_refund_insert_children_post($parent_id, $post_args) {
        $post_defaults = array(
            'post_parent' => $parent_id,
            'post_type' => HR_REFUND_POST_TYPE,
            'post_status' => 'hr-request-replied',
            'post_author' => get_current_user_id(),
        );


        $post_args = wp_parse_args($post_args, $post_defaults);
        //Insert Post 
        $post_id = wp_insert_post($post_args);

        return $post_id;
    }

}

if (!function_exists('hr_refund_get_currency_from_order')) {

    function hr_refund_get_currency_from_order($order, $args = false) {
        if (is_object($order)) {
            $order_obj = $order;
            $order_id = hr_get_order_obj($order, 'id');
        } else {
            $order_obj = hr_get_order_obj($order);
            $order_id = $order;
        }

        if (!get_post_status($order_id))
            return get_woocommerce_currency_symbol();

        $currency_code = hr_get_order_obj_function($order_obj, 'get_order_currency');

        if ($args)
            return array('currency' => $currency_code);

        return $currency_code;
    }

}