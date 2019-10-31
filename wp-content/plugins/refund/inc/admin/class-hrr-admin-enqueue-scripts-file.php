<?php

/**
 * Enqueue Admin Enqueue Files
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists ( 'HR_Refund_Admin_Enqueue_Scripts' ) ) {

    /**
     * HR_Refund_Admin_Enqueue_Scripts Class.
     */
    class HR_Refund_Admin_Enqueue_Scripts {

        /**
         * HR_Refund_Admin_Enqueue_Scripts Class Initialization.
         */
        public static function init() {
            //jQuery-backend
            add_action ( 'admin_enqueue_scripts' , array ( __CLASS__ , 'hr_refund_common_js' ) , 99 ) ;
        }
        
        public static function hr_refund_common_js() {
            $newscreenids = get_current_screen () ;
            $screen_ids   = array (
                'toplevel_page_hr_refund_settings' ,
                'hr_refund_request' ,
                    ) ;
            $screenid     = str_replace ( 'edit-' , '' , $newscreenids->id ) ;

            if ( in_array ( $screenid , $screen_ids ) ) {
                self::hr_refund_admin_external_js () ;
                self::hr_refund_admin_hoicker_js () ;
                self::hr_refund_settings_css () ;
            }
        }

        public static function hr_refund_settings_css() {
            wp_enqueue_style ( 'hr_refund_settings_style' , HR_REFUND_PLUGIN_URL . '/assets/css/settings-styles/hr-refund-styles.css' ) ;
        }

        /**
         * Enqueue Admin end required JS files
         */
        public static function hr_refund_admin_external_js() {

            $enqueue_array = array (
                'hrrefund-general'    => array (
                    'callable' => array ( 'HR_Refund_Admin_Enqueue_Scripts' , 'hr_refund_general_tab_enqueue_scripts' ) ,
                    'restrict' => isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'hr_refund_settings' ,
                ) ,
                'hrrefund-email'      => array (
                    'callable' => array ( 'HR_Refund_Admin_Enqueue_Scripts' , 'hr_refund_email_tab_enqueue_scripts' ) ,
                    'restrict' => isset ( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'hrrefundemail' ,
                ) ,
                'hrrefund-common'     => array (
                    'callable' => array ( 'HR_Refund_Admin_Enqueue_Scripts' , 'hr_refund_common_tab_enqueue_scripts' ) ,
                    'restrict' => isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'hr_refund_settings' ,
                ) ,
                'hrrefund-button'     => array (
                    'callable' => array ( 'HR_Refund_Admin_Enqueue_Scripts' , 'hr_refund_button_enqueue_scripts' ) ,
                    'restrict' => true ,
                ) ,
                'hrrefund-datepicker' => array (
                    'callable' => array ( 'HR_Refund_Admin_Enqueue_Scripts' , 'hr_refund_datepicker_enqueue_scripts' ) ,
                    'restrict' => isset ( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] == 'hr_refund_request' ,
                ) ,
                'hrrefund-footable'   => array (
                    'callable' => array ( 'HR_Refund_Admin_Enqueue_Scripts' , 'hr_refund_footable_enqueue_scripts' ) ,
                    'restrict' => isset ( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'hrrefundshortocode' ,
                ) ,
                    ) ;

            $enqueue_array = apply_filters ( 'hr_refund_admin_enqueue_scripts' , $enqueue_array ) ;
            if ( hr_check_is_array ( $enqueue_array ) ) {
                foreach ( $enqueue_array as $key => $enqueue ) {
                    if ( hr_check_is_array ( $enqueue ) ) {
                        call_user_func_array ( $enqueue[ 'callable' ] , array () ) ;
                    }
                }
            }
        }

        /**
         * Enqueue General Tab 
         */
        public static function hr_refund_general_tab_enqueue_scripts() {
            //enqueue script
            wp_enqueue_script ( 'hr_refund_general_tab' , HR_REFUND_PLUGIN_URL . '/assets/js/hr-refund-general-tab.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
        }

        /**
         * Enqueue Email Tab 
         */
        public static function hr_refund_email_tab_enqueue_scripts() {
            //enqueue script
            wp_enqueue_script ( 'hr_refund_email_tab' , HR_REFUND_PLUGIN_URL . '/assets/js/hr-refund-email-tab.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
        }

        /**
         * Enqueue Common Functions 
         */
        public static function hr_refund_common_tab_enqueue_scripts() {
            global $woocommerce ;
            //register script
            wp_register_script ( 'hr_refund_common' , HR_REFUND_PLUGIN_URL . '/assets/js/hr-refund-common-function.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
            //localize script
            wp_localize_script ( 'hr_refund_common' , 'hr_refund_common_obj' , array (
                'hr_refund_wc_version' => ( float ) $woocommerce->version ,
            ) ) ;
            //enqueue script
            wp_enqueue_script ( 'hr_refund_common' ) ;
        }

        /**
         * Enqueue Refund Request Button Scripts
         */
        public static function hr_refund_button_enqueue_scripts() {

            //enqueue script
            wp_register_script ( 'hrr_refund_request' , HR_REFUND_PLUGIN_URL . '/assets/js/hr-refund-request.js' , array ( 'jquery' , 'jquery-blockui' , 'jquery-tiptip' , 'accounting' ) , HR_REFUND_VERSION ) ;

            //localize script
            wp_localize_script ( 'hrr_refund_request' , 'hr_refund_request_params' , array (
                'mon_decimal_point'       => wc_get_price_decimal_separator () ,
                'request_status_security' => wp_create_nonce ( 'hr-refund-status' ) ,
                'request_button_security' => wp_create_nonce ( 'hr-refund-button' ) ,
                'refund_product_message'  => __ ( 'Please select atleast one product' , HR_REFUND_LOCALE ) ,
                'refund_request_message'  => __ ( 'Are you sure you wish to process this refund? This action cannot be undone.' , HR_REFUND_LOCALE ) ,
            ) ) ;
        }

        /**
         * Enqueue Refund Request Datepicker Scripts
         */
        public static function hr_refund_datepicker_enqueue_scripts() {
            wp_enqueue_script ( 'jquery-ui-datepicker' ) ;
            wp_enqueue_script ( 'hrr_refund_datepicker' , HR_REFUND_PLUGIN_URL . '/assets/js/hr-datepicker.js' , array ( 'jquery' , 'jquery-ui-datepicker' ) , HR_REFUND_VERSION ) ;
        }

        /**
         * Enqueue Footable Scripts 
         */
        public static function hr_refund_footable_enqueue_scripts() {
            //enqueue script
            wp_register_style ( 'hrr_footable_css' , HR_REFUND_PLUGIN_URL . '/assets/css/footable.core.css' ) ;
            wp_register_style ( 'hrr_footablestand_css' , HR_REFUND_PLUGIN_URL . '/assets/css/footable.standalone.css' ) ;
            wp_register_style ( 'jquery_smoothness_ui' , HR_REFUND_PLUGIN_URL . '/assets/css/jquery_smoothness_ui.css' ) ;
            wp_register_script ( 'hrr_footable' , HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
            wp_register_script ( 'hrr_footable_sorting' , HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.sort.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
            wp_register_script ( 'hrr_footable_paginate' , HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.paginate.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
            wp_register_script ( 'hrr_footable_filter' , HR_REFUND_PLUGIN_URL . '/assets/js/footable/footable.filter.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
            wp_enqueue_style ( 'hrr_footable_css' ) ;
            wp_enqueue_style ( 'hrr_footablestand_css' ) ;
            wp_enqueue_style ( 'jquery_smoothness_ui' ) ;
            wp_enqueue_script ( 'hrr_footable' ) ;
            wp_enqueue_script ( 'hrr_footable_sorting' ) ;
            wp_enqueue_script ( 'hrr_footable_paginate' ) ;
            wp_enqueue_script ( 'hrr_footable_filter' ) ;
        }

        public static function hr_refund_admin_hoicker_js() {
            wp_register_script ( 'hr_hoicker_global_license' , HR_REFUND_PLUGIN_URL . '/assets/js/jQuery-global-license.js' , array ( 'jquery' , 'jquery-blockui' ) , HR_REFUND_VERSION ) ;
            wp_localize_script ( 'hr_hoicker_global_license' , 'hr_license_params' , array (
                'license_security'      => wp_create_nonce ( 'hr-license-security' ) ,
                'license_empty_message' => __ ( 'Please Enter the License Key' ) ,
            ) ) ;
            wp_enqueue_script ( 'hr_hoicker_global_license' ) ;
            //enqueue script
            wp_enqueue_script ( 'hr_refund_hoicker_js' , HR_REFUND_PLUGIN_URL . '/assets/js/hr-refund-hoicker-js.js' , array ( 'jquery' ) , HR_REFUND_VERSION ) ;
        }

    }

    HR_Refund_Admin_Enqueue_Scripts::init () ;
}
