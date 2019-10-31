<?php

/* Localization Tab Settings */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Localization_Tab')) {

    /**
     * HR_Refund_Localization_Tab Class.
     */
    class HR_Refund_Localization_Tab {

        /**
         * HR_Refund_Localization_Tab Class initialization.
         */
        public static function init() {
            add_action('woocommerce_hr_refund_settings_hrrefundlocalization', array(__CLASS__, 'hr_refund_display_admin_settings'));
        }

        /**
         * Prepare setting Array to display.
         */
        public static function hr_refund_prepare_admin_settings_array() {
            return apply_filters('woocommerce_hrrefundlocalization_settings', array(
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Button Label', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_button_label_settings'
                ),
                array(
                    'name' => __('Request Refund Button', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_full_order_button_label',
                    'newids' => 'hr_refund_full_order_button_label',
                    'default' => 'Request Refund',
                    'std' => 'Request Refund',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_button_label_settings'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Request Table Labels', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_table_label_settings'
                ),
                array(
                    'name' => __('Refund Requests Label', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_request_title_label',
                    'newids' => 'hr_refund_table_request_title_label',
                    'default' => __('Refund Requests',HR_REFUND_LOCALE),
                    'std' => __('Refund Requests',HR_REFUND_LOCALE),
                ),
                array(
                    'name' => __('ID', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_request_id_label',
                    'newids' => 'hr_refund_table_request_id_label',
                    'default' => 'ID',
                    'std' => 'ID',
                ),
                array(
                    'name' => __('Order Number', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_orderid_label',
                    'newids' => 'hr_refund_table_orderid_label',
                    'default' => 'Order Number',
                    'std' => 'Order Number',
                ),
                array(
                    'name' => __('Request Status', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_status_label',
                    'newids' => 'hr_refund_table_status_label',
                    'default' => 'Status',
                    'std' => 'Status',
                ),
                array(
                    'name' => __('Refund Type', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_type_label',
                    'newids' => 'hr_refund_table_type_label',
                    'default' => 'Type',
                    'std' => 'Type',
                ),
                array(
                    'name' => __('Refund Mode', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_request_as_label',
                    'newids' => 'hr_refund_table_request_as_label',
                    'default' => 'Mode',
                    'std' => 'Mode',
                ),
                array(
                    'name' => __('Refund Amount', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_user_total_label',
                    'newids' => 'hr_refund_table_user_total_label',
                    'default' => 'Amount',
                    'std' => 'Amount',
                ),
                array(
                    'name' => __('Date', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_date_label',
                    'newids' => 'hr_refund_table_date_label',
                    'default' => 'Date',
                    'std' => 'Date',
                ),
                array(
                    'name' => __('View', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_table_view_label',
                    'newids' => 'hr_refund_table_view_label',
                    'default' => 'View',
                    'std' => 'View',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_table_label_settings'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Form Labels', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_form_label_settings'
                ),
                array(
                    'name' => __('Reason for Refund', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_form_general_reason_label',
                    'newids' => 'hr_refund_form_general_reason_label',
                    'default' => 'Reason for Requesting Refund',
                    'std' => 'Reason for Requesting Refund',
                ),
                array(
                    'name' => __('Refund Mode', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_form_request_as_label',
                    'newids' => 'hr_refund_form_request_as_label',
                    'default' => 'Refund Mode',
                    'std' => 'Refund Mode',
                ),
                array(
                    'name' => __('Reason in Detail', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_form_details_label',
                    'newids' => 'hr_refund_form_details_label',
                    'default' => 'Reason in Detail',
                    'std' => 'Reason in Detail',
                ),
                array(
                    'name' => __('Submit Button', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_form_submit_button_label',
                    'newids' => 'hr_refund_form_submit_button_label',
                    'default' => 'Request Refund',
                    'std' => 'Request Refund',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_form_label_settings'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
            ));
        }

        /**
         * Display tab Settings
         */
        public static function hr_refund_display_admin_settings() {
            woocommerce_admin_fields(HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array());
        }

        /**
         * Update tab Settings Fields 
         */
        public static function hr_refund_update_settings_values() {
            woocommerce_update_options(HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array());
        }

        /**
         * Set Default values to tab Settings.
         */
        public static function hr_refund_add_default_settings_values() {
            $settings = HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings);
        }

        /**
         * Reset Default values to tab Settings.
         */
        public static function hr_refund_reset_default_settings_values() {
            $settings = HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings, false);
        }

    }

    HR_Refund_Localization_Tab::init();
}