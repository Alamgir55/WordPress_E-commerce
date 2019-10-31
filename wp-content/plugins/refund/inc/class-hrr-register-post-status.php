<?php

/**
 * Admin Custom Post Status.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Register_Post_Status')) {

    /**
     * HR_Refund_Register_Post_Status Class.
     */
    class HR_Refund_Register_Post_Status {

        /**
         * HR_Refund_Register_Post_Status Class initialization.
         */
        public static function init() {
            add_action('init', array(__CLASS__, 'hr_refund_register_custom_post_status'));
        }

        /**
         * Register All Custom Post Status
         */
        public static function hr_refund_register_custom_post_status() {
            $array = array(
                'hr-refund-new' => array('HR_Refund_Register_Post_Status', 'hr_refund_request_new_post_status_args'),
                'hr-refund-accept' => array('HR_Refund_Register_Post_Status', 'hr_refund_request_accept_post_status_args'),
                'hr-refund-reject' => array('HR_Refund_Register_Post_Status', 'hr_refund_request_reject_post_status_args'),
                'hr-refund-processing' => array('HR_Refund_Register_Post_Status', 'hr_refund_request_processing_post_status_args'),
                'hr-refund-on-hold' => array('HR_Refund_Register_Post_Status', 'hr_refund_request_on_hold_post_status_args'),
                'hr-request-replied' => array('HR_Refund_Register_Post_Status', 'hr_refund_request_replied_post_status_args'),
            );
            $array = apply_filters('hr_refund_add_custom_post_status', $array);
            if (hr_check_is_array($array)) {
                foreach ($array as $post_name => $args_function) {
                    $args = call_user_func_array($args_function, array());
                    register_post_status($post_name, $args);
                }
            }
        }

        /**
         * Get refund New post Status args
         */
        public static function hr_refund_request_new_post_status_args() {
            $args = apply_filters('hr_refund_request_new_args', array(
                'label' => _x('New', HR_REFUND_LOCALE),
                'public' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('New <span class="count">(%s)</span>', 'New <span class="count">(%s)</span>'),
                    )
            );
            return $args;
        }

        /**
         * Get refund Accept post Status args
         */
        public static function hr_refund_request_accept_post_status_args() {
            $args = apply_filters('hr_refund_request_accept_args', array(
                'label' => _x('Accepted', HR_REFUND_LOCALE),
                'public' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Accept <span class="count">(%s)</span>', 'Accept <span class="count">(%s)</span>'),
                    )
            );
            return $args;
        }

        /**
         * Get refund Reject post Status args
         */
        public static function hr_refund_request_reject_post_status_args() {
            $args = apply_filters('hr_refund_request_reject_args', array(
                'label' => _x('Rejected', HR_REFUND_LOCALE),
                'public' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Reject <span class="count">(%s)</span>', 'Reject <span class="count">(%s)</span>'),
                    )
            );
            return $args;
        }

        /**
         * Get refund Processing post Status args
         */
        public static function hr_refund_request_processing_post_status_args() {
            $args = apply_filters('hr_refund_request_processing_args', array(
                'label' => _x('Processing', HR_REFUND_LOCALE),
                'public' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>'),
                    )
            );
            return $args;
        }

        /**
         * Get refund On Hold post Status args
         */
        public static function hr_refund_request_on_hold_post_status_args() {
            $args = apply_filters('hr_refund_request_on-hold_args', array(
                'label' => _x('On Hold', HR_REFUND_LOCALE),
                'public' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('On Hold <span class="count">(%s)</span>', 'On Hold <span class="count">(%s)</span>'),
                    )
            );
            return $args;
        }

        /**
         * Get refund Replied post Status args
         */
        public static function hr_refund_request_replied_post_status_args() {
            $args = apply_filters('hr_refund_request_replied_args', array(
                'label' => _x('Replied', HR_REFUND_LOCALE),
                'public' => false,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => false,
                'show_in_admin_status_list' => false,
                'label_count' => _n_noop('Replied <span class="count">(%s)</span>', 'Replied <span class="count">(%s)</span>'),
                    )
            );
            return $args;
        }

    }

    HR_Refund_Register_Post_Status::init();
}