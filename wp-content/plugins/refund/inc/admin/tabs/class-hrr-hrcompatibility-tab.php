<?php
/* Shortcode Tab Settings */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('HR_Refund_Compatibility_Tab')) {

    /**
     * HR_Refund_Compatibility_Tab Class.
     */
    class HR_Refund_Compatibility_Tab {

        /**
         * HR_Refund_Compatibility_Tab Class initialization.
         */
        public static function init() {
            add_action('woocommerce_hr_refund_settings_hrcompatibility', array(__CLASS__, 'hr_refund_display_admin_settings'));
            add_action('woocommerce_admin_field_hr_refund_display_compatibility_sections', array(__CLASS__, 'hr_refund_display_compatibility_sections'));
        }

        /**
         * Prepare setting Array to display.
         */
        public static function hr_refund_prepare_admin_settings_array() {
            return apply_filters('woocommerce_hrrefundcompatibility_settings', array(
                array(
                    'type' => 'hr_refund_display_compatibility_sections'
                ),
                    ));
        }

        public static function hr_refund_display_compatibility_sections() {
            ?>
            <div class='hr_compatiblity_wrapper'>
                
                <h3>Refund Module is Compatible with</h3>
                <div class="hr_compatible" >
                    <div class="hr_compatible_img">
                        
                        <img src="<?php echo HR_REFUND_PLUGIN_URL . '/assets/images/wallet.png' ?>">
                    </div>
                
                <div class="hr_compatible_title">
                    <p>&nbsp&nbspWallet</p>
                </div>
                <div class="hr_compatible_buynow">
                    <a href="https://hoicker.com/product/wallet">Buy Now</a>
                </div>
                </div>
                 <?php echo do_action('hr_compatiblity_premium_info') ?>  
            </div>
            <?php
        }

        /**
         * Display tab Settings
         */
        public static function hr_refund_display_admin_settings() {
            woocommerce_admin_fields(HR_Refund_Compatibility_Tab::hr_refund_prepare_admin_settings_array());
        }

    }

    HR_Refund_Compatibility_Tab::init();
}