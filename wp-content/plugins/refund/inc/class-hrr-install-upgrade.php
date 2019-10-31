<?php

/**
 * initialize the plugin.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HRR_Install')) {

    /**
     * HRR_Install Class.
     */
    class HRR_Install {

        /**
         * HRR_Install Class initialization.
         */
        public static function init() {
            add_action('plugins_loaded', array(__CLASS__, 'initialize_activation_upgrade'), 1);
        }

        /**
         * Initializing the activation and upgrade
         */
        public static function initialize_activation_upgrade() {
            //Upgrade
            include_once('upgrade/class-hr-activation-handler.php');
            include_once('upgrade/class-hr-plugin-update-checker.php');  

            $license_handler_obj = new HRR_License_Handler(HR_REFUND_VERSION, HR_REFUND_PLUGIN_BASE_NAME);

            $license_code = $license_handler_obj->license_key();

            new HRR_Plugin_Update_Checker(HR_REFUND_VERSION, HR_REFUND_PLUGIN_BASE_NAME, $license_code);
        }

    }

    HRR_Install::init();
}