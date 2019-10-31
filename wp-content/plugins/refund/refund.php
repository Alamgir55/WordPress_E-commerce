<?php

/**
 * Plugin Name: Refund
 * Description: Refund is a comprehensive WooCommerce Refund System which allows you to Handle Refund Requests from your Buyers and Process the Refund Requests within the Site.
 * Plugin URI:
 * Version: 1.5
 * Author: Hoicker
 * Author URI: https://hoicker.com
 *
 */
/*
 * Refund Module
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HR_Refund' ) ) {

    /**
     * Main HR_Refund Class.
     * */
    final class HR_Refund {

        /**
         * HR_Refund Version
         * */
        public $version = '1.5' ;

        /**
         * The single instance of the class.
         * */
        protected static $_instance = null ;

        /**
         * Load HR_Refund Class in Single Instance
         */
        public static function instance() {
            if ( is_null ( self::$_instance ) )
                self::$_instance = new self() ;

            return self::$_instance ;
        }

        /* Cloning has been forbidden */

        public function __clone() {
            _doing_it_wrong ( __FUNCTION__ , 'You are not allowed to perform this action!!!' , $this->version ) ;
        }

        /**
         * Unserialize the class data has been forbidden
         * */
        public function __wakeup() {
            _doing_it_wrong ( __FUNCTION__ , 'You are not allowed to perform this action!!!' , $this->version ) ;
        }

        /**
         * HR_Refund Class Constructor
         * */
        public function __construct() {
            /* Include once will help to avoid fatal error by load the files when you call init hook */
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;
            if ( $this->hr_check_if_woocommerce_is_active () ) {
                return false ;
            }
            $this->hrw_header_already_sent_problem () ;
            $this->hrr_define_constants () ;
            $this->hrr_translate_file () ;
            $this->hrr_include_files () ;
        }

        /**
         * Function to Prevent Header Error that says You have already sent the header.
         */
        private function hrw_header_already_sent_problem() {
            ob_start () ;
        }

        /**
         * Function to check wheather Woocommerce is active or not
         */
        private function hr_check_if_woocommerce_is_active() {

            if ( is_multisite () ) {
                // This Condition is for Multi Site WooCommerce Installation
                if ( ! is_plugin_active_for_network ( 'woocommerce/woocommerce.php' ) && ( ! is_plugin_active ( 'woocommerce/woocommerce.php' )) ) {
                    if ( is_admin () ) {
                        add_action ( 'init' , array ( $this , 'hr_display_warning_messages' ) ) ;
                    }
                    return true ;
                }
            } else {
                // This Condition is for Single Site WooCommerce Installation
                if ( ! is_plugin_active ( 'woocommerce/woocommerce.php' ) ) {
                    if ( is_admin () ) {
                        add_action ( 'init' , array ( $this , 'hr_display_warning_messages' ) ) ;
                    }
                    return true ;
                }
            }
            return false ;
        }

        public static function hr_display_warning_messages() {
            $variable = "<div class='error'><p> Refund Plugin will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>" ;
            echo $variable ;
        }

        /**
         * Initialize the Translate Files.
         * */
        private function hrr_translate_file() {
            load_plugin_textdomain ( HR_REFUND_LOCALE , false , dirname ( plugin_basename ( __FILE__ ) ) . '/languages' ) ;
        }

        /**
         * Prepare the Constants value array.
         * */
        private function hrr_define_constants() {
            global $wpdb ;
            $constant_array = array (
                'HR_REFUND_VERSION'          => $this->version ,
                'HR_REFUND_PLUGIN_FILE'      => __FILE__ ,
                'HR_REFUND_LOCALE'           => 'hrrefund' ,
                'HR_REFUND_FOLDER_NAME'      => 'refund/' ,
                'HR_REFUND_POST_TYPE'        => 'hr_refund_request' ,
                'HR_REFUND_ADMIN_URL'        => admin_url ( 'admin.php' ) ,
                'HR_REFUND_ADMIN_AJAX_URL'   => admin_url ( 'admin-ajax.php' ) ,
                'HR_REFUND_PLUGIN_BASE_NAME' => plugin_basename ( __FILE__ ) ,
                'HR_REFUND_PLUGIN_PATH'      => untrailingslashit ( plugin_dir_path ( __FILE__ ) ) ,
                'HR_REFUND_PLUGIN_URL'       => untrailingslashit ( plugins_url ( '/' , __FILE__ ) ) ,
                'CUSTOM_TEXT_DOMAIN'         => 'hrrefund' ,
                    ) ;
            $constant_array = apply_filters ( 'hrr_define_constants' , $constant_array ) ;

            if ( is_array ( $constant_array ) && ! empty ( $constant_array ) ) {
                foreach ( $constant_array as $name => $value )
                    $this->hrr_define_constant ( $name , $value ) ;
            }
        }

        /**
         * Define the Constants value.
         * */
        private function hrr_define_constant( $name , $value ) {
            if ( ! defined ( $name ) )
                define ( $name , $value ) ;
        }

        /**
         * Include required files
         * */
        private function hrr_include_files() {

            //Function related Files

            include_once('inc/class-hrr-install-upgrade.php') ;
            include_once('inc/hrr-common-functions.php') ;
            include_once('inc/hrr-premium-functions.php') ;
            include_once('inc/hrr-custom-post-type-functions.php') ;
            include_once('inc/hrr-wc-compatibility-functions.php') ;

            include_once('inc/class-hrr-install.php') ;
            include_once('inc/class-hrr-items-product.php') ;
            include_once('inc/class-hrr-register-post-type.php') ;
            include_once('inc/class-hrr-register-post-status.php') ;

            //Email related Files
            include_once('inc/email/class-hrr-emails.php') ;


            if ( is_admin () )
                $this->include_admin_files () ;

            if ( ! is_admin () || defined ( 'DOING_AJAX' ) )
                $this->include_frontend_files () ;
        }

        /**
         * Include Admin End files
         * */
        private function include_admin_files() {

            if ( ! class_exists ( 'HR_Hoicker' ) ) {
                include_once "common-files/class-hr-common-files.php" ;
                $free = hr_is_refund_free_version () ;
                if ( ! $free ) {

                    include_once HR_REFUND_PLUGIN_PATH . "/premium/refund/refund-premium.php" ;
                }
                if ( $free ) {
                    include_once('inc/class-hrr-premium-info-hooks.php') ;
                }
                include_once "inc/upgrade/class-hr-activation-handler.php" ;
                include_once "inc/upgrade/class-hr-plugin-update-checker.php" ;
            }

            include_once('inc/admin/class-hrr-request-view.php') ;
            include_once('inc/admin/class-hrr-request-submenu.php') ;
            include_once('inc/admin/class-hrr-settings-submenu.php') ;
            include_once('inc/api/hrr-function-for-multi-select-search.php') ;
            include_once('inc/admin/class-hrr-admin-enqueue-scripts-file.php') ;
        }

        /**
         * Include Front End files
         * */
        private function include_frontend_files() {
            include_once('inc/frontend/class-hrr-button.php') ;
            include_once('inc/frontend/class-hrr-request-page.php') ;
            include_once('inc/frontend/class-hrr-enqueue-scripts-file.php') ;
        }

        /**
         * Define the hooks
         * 
         */
        public function hr_refund_template_path() {
            return apply_filters ( 'hr_refund_template_path' , HR_REFUND_PLUGIN_PATH . '/inc/templates/' ) ;
        }

    }

}

if ( ! function_exists ( 'HRREFUND' ) ) {

    function HRREFUND() {
        if ( class_exists ( 'HR_Refund' ) )
            return HR_Refund::instance () ;

        return false ;
    }

}

//initialize the plugin. 
HRREFUND () ;
