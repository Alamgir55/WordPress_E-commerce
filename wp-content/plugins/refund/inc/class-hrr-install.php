<?php

/**
 * initialize the plugin.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Install')) {

    /**
     * HR_Refund_Install Class.
     */
    class HR_Refund_Install {

        /**
         * HR_Refund_Install Class initialization.
         */
        public static function init() {
            add_action('admin_init', array(__CLASS__, 'install'));
            add_action('admin_head', array(__CLASS__, 'hr_refund_remove_submenu'));
            add_filter('woocommerce_screen_ids', array(__CLASS__, 'hr_refund_access_woo_script'), 9, 1);
        }

        /**
         * Install RAC.
         */
        public static function install() {
            $transient = get_transient('hr_refund_set_default_values_transient');
            if (!$transient)
                return;

            delete_transient('hr_refund_set_default_values_transient');

            self::hr_refund_set_default_values();
        }

        /**
         * Remove Submenu
         */
        public static function hr_refund_remove_submenu() {
            remove_submenu_page('hr_hoicker_module_slug', 'hr_refund_settings');
        }

        /**
         * Initializing the screen id. 
         */
        public static function hr_refund_access_woo_script($array_screens) {
            $newscreenids = get_current_screen();
            $screen_ids = array(
                'toplevel_page_hr_refund_settings',
                'hr_refund_request',
            );
            $screenid = str_replace('edit-', '', $newscreenids->id);
            
            if (in_array($screenid, $screen_ids))
                $array_screens[] = $newscreenids->id;
            
            return $array_screens;
        }

        /**
         * Initializing set transients. 
         */
        public static function hr_refund_set_default_values() {
            $tabs = array(
                'hrrefundgeneral',
                'hrrefundemail',
                'hrrefundlocalization',
            );

            $tabs = apply_filters('hr_refund_set_default_value_tabs', $tabs);
            if (hr_check_is_array($tabs)) {
                foreach ($tabs as $tab) {
                    //include current page functionality.
                    include_once(HR_REFUND_PLUGIN_PATH . '/inc/admin/tabs/class-hrr-' . $tab . '-tab.php');
                }
            }
            if (hr_check_is_array($tabs)) {
                foreach ($tabs as $tab) {
                    //include current page functionality.
                    do_action('hr_refund_default_settings_' . $tab);
                }
            }
        }

    }

    HR_Refund_Install::init();
}