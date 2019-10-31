<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('HR_Hoicker_License_Tab_Refund')) {

    /**
     * HR_Hoicker_License_Tab Class.
     */
    class HR_Hoicker_License_Tab_Refund {

        public static function init() {
            add_action('woocommerce_hr_refund_settings_hr-hoicker-global-license', array(__CLASS__, 'display_admin_settings'));
            add_action('woocommerce_admin_field_hr_license_information', array(__CLASS__, 'display_license_information'));
        }

        public static function display_license_information() {
            ?>
        <div class="hr_license_wrapper">
            <div class="hr_license_information" >
                <h2><?php echo __('License Information', HR_REFUND_LOCALE); ?></h2>     
                <?php
                $license_handler_obj = new HRR_License_Handler(HR_REFUND_VERSION, HR_REFUND_PLUGIN_BASE_NAME);

                echo $license_handler_obj->show_activation_panel();
                ?>
            </div>
        </div>    
            <?php
        }

        public static function prepare_settings_array() {
            return apply_filters('woocommerce_hr_hoicker_global_license_settings', array(
                array(
                    'type' => 'title',
                    'id' => 'hr_license_information'
                ),
                array(
                    'type' => 'hr_license_information',
                ),
                array('type' => 'sectionend', 'id' => 'hr_license_information'),
            ));
        }

        public static function display_admin_settings() {
            woocommerce_admin_fields(HR_Hoicker_License_Tab_Refund::prepare_settings_array());
        }

    }

    HR_Hoicker_License_Tab_Refund::init();
}