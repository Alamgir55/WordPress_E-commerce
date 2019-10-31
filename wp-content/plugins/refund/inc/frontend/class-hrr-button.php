<?php

/*
 * Refund Button
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Button')) {

    /**
     * HR_Refund_Button Class.
     */
    class HR_Refund_Button {

        /**
         * HR_Refund_Button Class initialization.
         */
        public static function init() {
            add_action('woocommerce_order_details_after_order_table', array(__CLASS__, 'add_refund_request_buttons_on_view_orders'), 20, 1);
            add_filter('woocommerce_my_account_my_orders_actions', array(__CLASS__, 'add_woocommerce_my_account_my_orders_actions'), 10, 2);
        }

        /**
         * Add Refund Action on My Account My Orders.
         * */
        public static function add_woocommerce_my_account_my_orders_actions($actions, $order) {
            if (!$order || !is_user_logged_in())
                return $actions;

            $order_id = hr_get_order_obj_data($order, 'id');
            $restrict = hrr_check_order_id_valid_to_refund($order_id);
            //Whole Order 
            if ((get_option('hr_refund_enable_refund_request', 'no') == 'yes') && $restrict) {
                $url = wc_get_endpoint_url('hr-refund-request-form', $order_id, wc_get_page_permalink('myaccount'));
                $actions['whole-refund'] = array(
                    'url' => $url,
                    'name' => get_option('hr_refund_full_order_button_label', 'Whole Refund')
                );
            }

            return $actions;
        }

        /**
         * Add Refund Button on view Orders.
         * */
        public static function add_refund_request_buttons_on_view_orders($order) {
            if (!$order || !is_user_logged_in())
                return;
            
            $order_id = hr_get_order_obj_data($order, 'id');
            $template_path = HRREFUND()->hr_refund_template_path();
            $restrict = hrr_check_order_id_valid_to_refund($order_id);
            //Whole Order Button
            if ((get_option('hr_refund_enable_refund_request', 'no') == 'yes') && $restrict)
                wc_get_template('button/whole-refund.php', array('order' => $order), HR_REFUND_FOLDER_NAME, $template_path);

        }

        /**
         * Add Refund Request View Button
         * */
        public static function display_refund_request_view_button($request_id) {
            if (!$request_id || !is_user_logged_in())
                return;

            $template_path = HRREFUND()->hr_refund_template_path();
            //View request Button
            wc_get_template('button/view-refund.php', array('request_id' => $request_id), HR_REFUND_FOLDER_NAME, $template_path);
        }

    }

    HR_Refund_Button::init();
}