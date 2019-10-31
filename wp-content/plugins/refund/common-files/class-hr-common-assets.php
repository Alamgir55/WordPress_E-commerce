<?php

if ( ! defined ( 'ABSPATH' ) ) {
    exit ;
}

if ( ! class_exists ( 'HR_Common_Assets' , false ) ) {

    /**
     * HR_Hoicker_Assets Class.
     */
    class HR_Common_Assets {

        /**
         * Hook in tabs.
         */
        public static function init() {
                if ( isset ( $_GET[ 'page' ] )  ) {
                    add_action ( 'admin_enqueue_scripts' , array ( __CLASS__ , 'hr_common_style_enqueues' ) ) ;
                    add_action ( 'admin_enqueue_scripts' , array ( __CLASS__ , 'hr_common_js_enqueues' ) ) ;
                }
        }
        
        public static function hr_common_style_enqueues() {
            wp_enqueue_style ( 'hr_hoicker_admin_style' , CUSTOM_PLUGIN_URL . '/common-files/assets/style/admin-layout/hr-admin-styles-layout.css' ) ;
            wp_enqueue_style ( 'hr_hoicker_upgrade_style' , CUSTOM_PLUGIN_URL . '/common-files/assets/style/premium-info/hr-hoicker-upgrade_message_styles.css' ) ;
            wp_enqueue_style ( 'hr_hoicker_runthrough_style' , CUSTOM_PLUGIN_URL . '/common-files/assets/style/run-through/hr-hoicker-run-through-styles.css' ) ;
            wp_enqueue_style ( 'hr_hoicker_welcome_page_style' , CUSTOM_PLUGIN_URL . '/common-files/assets/style/welcome-page/hoicker_welcome_page_design.css' ) ;
        }
        
        public static function hr_common_js_enqueues() {
            wp_enqueue_script ( 'hr_hoicker_runthrough' , CUSTOM_PLUGIN_URL . '/common-files/assets/js/run-through/jQuery-global-run-through-modules.js' , array ( 'jquery' , 'jquery-blockui' ) , CUSTOM_VERSION ) ;
          }
    }
    HR_Common_Assets::init () ;
}