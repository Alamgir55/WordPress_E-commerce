<?php
if (!class_exists('HRR_Premium_Info_Hooks')) {

    /**
     * HR_Premium_Info Class.
     */
    class HRR_Premium_Info_Hooks {

        /**
         * HR_Premium_Info Class initialization.
         */
        public static function init() {
            if ( isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'hr_refund_settings' ) {
                add_action ( 'admin_enqueue_scripts' , array ( 'HR_Refund_Settings' , 'hr_premium_scripts_enqueues' ) ) ;
                add_action ( 'hr_compatiblity_premium_info' , array ( 'HR_Refund_Settings' , 'hr_compatiblity_premium_info_message' ) ) ;
                add_action ( 'hr_shortcode_premium_info' , array ( 'HR_Refund_Settings' , 'hr_shortcode_premium_info_message' ) ) ;
                add_action ( 'hr_premium_info_hr_refund_settings' , array ( 'HR_Refund_Settings' , 'hr_inner_refund_module_premium_info_message' ) ) ;
                add_action ( 'woocommerce_admin_field_hr_shortcode_premium_info_refund' , array ( 'HR_Refund_Settings' , 'hr_shortcode_premium_info_message' ) ) ;
            }
        }
    }
     HRR_Premium_Info_Hooks::init();
}