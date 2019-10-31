<?php
if ( ! class_exists ( 'HR_Plugin_Splitup' ) ) {

    /**
     * HR_Plugin_Splitup Class.
     */
    class HR_Plugin_Splitup {
        

        /**
         * HR_Plugin_Splitup Class initialization.
         */
        public function __construct($version , $plugin_name  ) {
           $this->version = $version;
           $this->plugin_name = $plugin_name;
           
             /* Include once will help to avoid fatal error by load the files when you call init hook */
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;
            
            if ( $this->hr_check_if_woocommerce_is_active () ) {
                return false ;
            }
            $this->hrwc_define_constants () ;
            $this->hr_includes_splitup_files () ;
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

        public function hrwc_define_constants() {
            $this->hrw_define ( 'CUSTOM_PLUGIN_URL' , untrailingslashit ( plugins_url ( $this->plugin_name ) ) ) ;
            $this->hrw_define ( 'CUSTOM_VERSION' , $this->version ) ;
        }

        /**
         * Define constant if not already set.
         * 
         * @param string $name
         * @param string $value
         */
        public function hrw_define( $name , $value ) {
                if (!defined($name)) {
                    define ( $name , $value ) ;
                }
        }

        public function hr_includes_splitup_files() {
            include_once "class-hr-common-assets.php" ;
            include_once "class-hr-admin-settings-layout.php" ;
        }
    }
} 

if ( class_exists ( 'HR_Plugin_Splitup' ) ) {
    new HR_Plugin_Splitup('1.3' , 'refund'  ) ;
}