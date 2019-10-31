<?php

/**
 * Enqueue Front End Enqueue Files
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Frontend_Enqueue_Scripts')) {

    /**
     * HR_Refund_Frontend_Enqueue_Scripts Class.
     */
    class HR_Refund_Frontend_Enqueue_Scripts {

        /**
         * HR_Refund_Frontend_Enqueue_Scripts Class Initialization.
         */
        public static function init() {

            add_action('wp_enqueue_scripts', array(__CLASS__, 'hr_refund_frontend_external_js'), 99);
        }

        /**
         * Enqueue Front end required JS files
         */
        public static function hr_refund_frontend_external_js() {

            $enqueue_array = array(
                'hrrefund-footable' => array(
                    'callable' => array('HR_Refund_Frontend_Enqueue_Scripts', 'hr_refund_footable_enqueue_scripts'),
                    'restrict' => true,
                ),
                'hrrefund-form' => array(
                    'callable' => array('HR_Refund_Frontend_Enqueue_Scripts', 'hr_refund_form_enqueue_scripts'),
                    'restrict' => true,
                ),
            );

            $enqueue_array = apply_filters('hr_refund_frontend_enqueue_scripts', $enqueue_array);
            if (hr_check_is_array($enqueue_array)) {
                foreach ($enqueue_array as $key => $enqueue) {
                    if (hr_check_is_array($enqueue)) {
                        if ($enqueue['restrict'])
                            call_user_func_array($enqueue['callable'], array());
                    }
                }
            }
        }

        /**
         * Enqueue Footable Scripts 
         */
        public static function hr_refund_footable_enqueue_scripts() {
            //enqueue script
            wp_register_style('hrr_footable_css', HR_REFUND_PLUGIN_URL . '/assets/css/footable.core.css');
            wp_enqueue_style( 'hrr_frontend_css' , HR_REFUND_PLUGIN_URL . '/assets/css/frontend.css' ) ;
            wp_register_style('hrr_footablestand_css', HR_REFUND_PLUGIN_URL . '/assets/css/footable.standalone.css');
            wp_register_style('jquery_smoothness_ui', HR_REFUND_PLUGIN_URL . '/assets/css/jquery_smoothness_ui.css');
            wp_register_script('hrr_footable', HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.js', array('jquery'), HR_REFUND_VERSION);
            wp_register_script('hrr_footable_sorting', HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.sort.js', array('jquery'), HR_REFUND_VERSION);
            wp_register_script('hrr_footable_paginate', HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.paginate.js', array('jquery'), HR_REFUND_VERSION);
            wp_register_script('hrr_footable_filter', HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.filter.js', array('jquery'), HR_REFUND_VERSION);
        }

        /**
         * Enqueue Refund Request Form Scripts
         */
        public static function hr_refund_form_enqueue_scripts() {
            //enqueue script
            wp_register_script( 'hrr_refund_form' , HR_REFUND_PLUGIN_URL . '/assets/js/frontend/hr-refund-request-form.js' , array( 'jquery' , 'accounting' ) , HR_REFUND_VERSION ) ;
            wp_localize_script( 'hrr_refund_form' , 'hr_refund_request_form_params' , array(
                'request_form_security'  => wp_create_nonce( 'hr-refund-request' ) ,
                'ajax_url'               => HR_REFUND_ADMIN_AJAX_URL ,
                'reason_field_mandatory' => apply_filters( 'hrr_validate_refund_reason_field' , true ) ,
                'redirect_url'           => wc_get_endpoint_url( 'hr-refund-requests' ) ,
                'refund_request_message' => __( 'Are you sure you want to request for a refund?' , HR_REFUND_LOCALE ) ,
                'refund_reason_message'  => __( 'Please give reason in detail' , HR_REFUND_LOCALE ) ,
            ) ) ;
        }

    }

    HR_Refund_Frontend_Enqueue_Scripts::init();
}
