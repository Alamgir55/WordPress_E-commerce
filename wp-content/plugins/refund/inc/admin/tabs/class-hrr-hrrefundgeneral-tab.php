<?php
/* General Tab Settings */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_General_Tab')) {

    /**
     * HR_Refund_General_Tab Class.
     */
    class HR_Refund_General_Tab {

        /**
         * HR_Refund_General_Tab Class initialization.
         */
        public static function init() {
            add_action('woocommerce_hr_refund_settings_hrrefundgeneral', array(__CLASS__, 'hr_refund_display_admin_settings'));
            add_action('woocommerce_admin_field_include_product_search', array(__CLASS__, 'hr_refund_include_product_search_function'));
            add_action('woocommerce_admin_field_include_user_search', array(__CLASS__, 'hr_refund_include_user_search_function'));
        }

        /**
         * Prepare setting Array to display.
         */
        public static function hr_refund_prepare_admin_settings_array() {
            $category_options = hr_get_category();
            $order_status = hr_get_order_status();
            $user_roles = hr_user_roles();
            return apply_filters('woocommerce_hrrefundgeneral_settings', array(
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Settings', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_general_setting'
                ),
                array(
                    'name' => __('Enable Refund', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_enable_refund_request',
                    'newids' => 'hr_refund_enable_refund_request',
                    'default' => 'yes',
                    'std' => 'yes',
                    'desc' => __('If enabled, customers can request refund from their "My Account" page under "Orders" menu', HR_REFUND_LOCALE),
                ),
                array(
                    'name' => __('Enable Partial Refund', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_enable_partial_refund_request',
                    'newids' => 'hr_refund_enable_partial_refund_request',
                    'class' => 'hr_premium_settings',
                    'default' => 'yes',
                    'std' => 'yes',
                    'desc' => __('If enabled, customers can request partial refund for their orders', HR_REFUND_LOCALE),
                ),
                array(
                    'name' => __('Enable Refund for Sale Products', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_enable_for_sale_items',
                    'newids' => 'hr_refund_enable_for_sale_items',
                    'class' => 'hr_premium_settings',
                    'default' => 'no',
                    'std' => 'no',
                    'desc' => __('If enabled, customers can request a refund for products with sale price', HR_REFUND_LOCALE),
                ),
                array(
                    'name' => __('Refund Tax Cost', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_include_tax',
                    'newids' => 'hr_refund_request_include_tax',
                    'default' => 'no',
                    'std' => 'no',
                    'desc' => __('If enabled, applicable tax cost will be refunded along with the product price', HR_REFUND_LOCALE),
                ),
                array(
                    'name' => __('Display Refund Button when Order Status becomes', HR_REFUND_LOCALE),
                    'type' => 'multiselect',
                    'options' => $order_status,
                    'id' => 'hr_refund_buttons_by_order_status',
                    'newids' => 'hr_refund_buttons_by_order_status',
                    'class' => 'hr_premium_settings',
                    'default' => array('completed'),
                    'std' => array('completed'),
                    'desc_tip' => true,
                    'desc' => __('Refund button will be displayed on the each order[My Account Page] when the order status reaches the status which are selected here', HR_REFUND_LOCALE),
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_general_setting'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Restriction Settings', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_restriction_setting'
                ),
                array(
                    'name' => __('Minimum Order Amount Required to Request a Refund', HR_REFUND_LOCALE),
                    'type' => 'number',
                    'id' => 'hr_refund_min_order_amount',
                    'newids' => 'hr_refund_min_order_amount',
                    'class' => 'hr_premium_settings',
                    'default' => '1',
                    'std' => '1',
                    'custom_attributes' => array('min' => '0.01', 'required' => 'required', 'step' => 'any'),
                ),
                array(
                    'name' => __('Refund Requesting Time', HR_REFUND_LOCALE),
                    'type' => 'select',
                    'options' => array('1' => __('No Limit', HR_REFUND_LOCALE), '2' => __('Limited Duration', HR_REFUND_LOCALE)),
                    'id' => 'hr_refund_request_time_period',
                    'newids' => 'hr_refund_request_time_period',
                    'class' => 'hr_premium_settings',
                    'default' => '1',
                    'std' => '1',
                ),
                array(
                    'name' => __('Allow Requesting Refund for', HR_REFUND_LOCALE),
                    'type' => 'number',
                    'id' => 'hr_refund_request_no_of_days',
                    'newids' => 'hr_refund_request_no_of_days',
                    'class' => 'hr_premium_settings',
                    'default' => '1',
                    'std' => '1',
                    'custom_attributes' => array('min' => '1', 'required' => 'required'),
                    'desc' => __('day(s) from the date of purchase', HR_REFUND_LOCALE),
                ),
                array(
                    'name' => __('Enable Refund for', HR_REFUND_LOCALE),
                    'type' => 'select',
                    'options' => array(
                        '1' => __('All Product (s)', HR_REFUND_LOCALE),
                        '2' => __('Include Product(s)', HR_REFUND_LOCALE),
                        '3' => __('All Categories', HR_REFUND_LOCALE),
                        '4' => __('Include Categories', HR_REFUND_LOCALE)
                    ),
                    'id' => 'hr_refund_prevent_refund_request',
                    'newids' => 'hr_refund_prevent_refund_request',
                    'class' => 'hr_premium_settings',
                    'default' => '1',
                    'std' => '1',
                ),
                array(
                    'name' => __('Include Categories', HR_REFUND_LOCALE),
                    'type' => 'multiselect',
                    'options' => $category_options,
                    'id' => 'hr_refund_include_categories_srch',
                    'newids' => 'hr_refund_include_categories_srch',
                    'default' => '',
                    'std' => '',
                ),
                array(
                    'type' => 'include_product_search',
                ),
                array(
                    'name' => __('Enable Refund for', HR_REFUND_LOCALE),
                    'type' => 'select',
                    'class' => 'hr_premium_settings',
                    'options' => array(
                        '1' => __('All user(s)', HR_REFUND_LOCALE),
                        '2' => __('Selected user(s)', HR_REFUND_LOCALE),
                        '3' => __('All User Role(s)', HR_REFUND_LOCALE),
                        '4' => __('Selected User Role(s)', HR_REFUND_LOCALE)
                    ),
                    'id' => 'hr_refund_request_prevent_users',
                    'newids' => 'hr_refund_request_prevent_users',
                ),
                array(
                    'name' => __('Include User Roles', HR_REFUND_LOCALE),
                    'type' => 'multiselect',
                    'options' => $user_roles,
                    'id' => 'hr_refund_include_user_role_srch',
                    'newids' => 'hr_refund_include_user_role_srch',
                    'default' => '',
                    'std' => '',
                ),
                array(
                    'type' => 'include_user_search',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_restriction_setting'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Request Form Settings', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_request_form_setting'
                ),
                array (
                    'name'    => __ ( 'Display Refund Mode' , HR_REFUND_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'id'      => 'hr_refund_enable_refund_method' ,
                    'newids'  => 'hr_refund_enable_refund_method' ,
                    'class'   => 'hr_premium_settings' ,
                    'default' => 'yes' ,
                    'std'     => 'yes' ,
                   
                ) ,
                array(
                    'name'     => __( 'Preloaded Reason for Refund' , HR_REFUND_LOCALE ) ,
                    'type'     => 'textarea' ,
                    'id'       => 'hr_refund_preloaded_reason' ,
                    'newids'   => 'hr_refund_preloaded_reason' ,
                    'default'  => "Incorrect Product,Incorrect Size,Incorrect Color,Product Damaged,Product did not match Description,Didn't meet the Expectation" ,
                    'std'      => "Incorrect Product,Incorrect Size,Incorrect Color,Product Damaged,Product did not match Description,Didn't meet the Expectation" ,
                    'desc'     => __( "Reasons separated by comma(',')" , HR_REFUND_LOCALE ) ,
                    'desc_tip' => true
                ) ,
                array(
                    'name'    => __( 'Dispaly Reason in Detail Field' , HR_REFUND_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'id'      => 'hr_refund_enable_refund_reason_field' ,
                    'newids'  => 'hr_refund_enable_refund_reason_field' ,
                    'desc'    => __( "If enabled, field to enter the refund reason in detail will be displayed" , HR_REFUND_LOCALE ) ,
                    'class'   => 'hr_premium_settings' ,
                    'default' => 'yes' ,
                    'std'     => 'yes' ,
                ) ,
                array(
                    'name'    => __( 'Reason in Detail Mandatory' , HR_REFUND_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'id'      => 'hr_refund_enable_refund_reason_field_mandatory' ,
                    'newids'  => 'hr_refund_enable_refund_reason_field_mandatory' ,
                    'class'   => 'hr_premium_settings' ,
                    'desc'    => __( "If enabled, the user must have to fill the reason in detail for requesting refund" , HR_REFUND_LOCALE ) ,
                    'default' => 'yes' ,
                    'std'     => 'yes' ,
                ) ,
                array(
                    'name'    => __( 'Enable Conversation' , HR_REFUND_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'id'      => 'hr_refund_enable_conversation' ,
                    'newids'  => 'hr_refund_enable_conversation' ,
                    'class'   => 'hr_premium_settings' ,
                    'default' => 'yes' ,
                    'std'     => 'yes' ,
                    'desc'    => __( 'If enabled, follow up conversation will be allowed for the customer after requesting refund' , HR_REFUND_LOCALE ) ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'hr_refund_request_form_setting'
                ) ,
                array(
                    'type' => 'hoicker_section_close' ,
                ) ,
                    ) ) ;
        }

        /**
         * Display tab Settings
         */
        public static function hr_refund_display_admin_settings() {
            woocommerce_admin_fields(HR_Refund_General_Tab::hr_refund_prepare_admin_settings_array());
        }

        /**
         * Update tab Settings Fields 
         */
        public static function hr_refund_update_settings_values() {
            //General Settinngs 
            woocommerce_update_options(HR_Refund_General_Tab::hr_refund_prepare_admin_settings_array());
            $include_user_search = isset($_POST['hr_refund_include_user_srch']) ? $_POST['hr_refund_include_user_srch'] : array();
            $include_product_search = isset($_POST['hr_refund_include_products_srch']) ? $_POST['hr_refund_include_products_srch'] : array();
            update_option('hr_refund_include_user_srch', $include_user_search);
            update_option('hr_refund_include_products_srch', $include_product_search);

            //Email Settings
            woocommerce_update_options(HR_Refund_Email_Tab::hr_refund_prepare_admin_settings_array());

            //Localization Settings
            woocommerce_update_options(HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array());
        }

        /**
         * Set Default values to tab Settings.
         */
        public static function hr_refund_add_default_settings_values() {
            $settings_general = HR_Refund_General_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings_general))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings_general);

            $settings_email = HR_Refund_Email_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings_email))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings_email);

            $settings_localoization = HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings_localoization))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings_localoization);
        }

        /**
         * Reset Default values to tab Settings.
         */
        public static function hr_refund_reset_default_settings_values() {
            $settings_general = HR_Refund_General_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings_general))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings_general, false);
            $settings_email = HR_Refund_Email_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings_email))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings_email, false);
            $settings_localization = HR_Refund_Localization_Tab::hr_refund_prepare_admin_settings_array();
            if (hr_check_is_array($settings_localization))
                HR_Refund_Settings::hr_refund_set_default_settings_values($settings_localization, false);
        }

        /**
         * Display Include product Search
         */
        public static function hr_refund_include_product_search_function() {
            ?><tr>
                <th class="titledesc" scope="row">
                    <label id="hr_refund_include_products_srch" for="hr_refund_include_products_srch"><?php _e('Include Products', HR_REFUND_LOCALE); ?></label>
                </th>
                <td>
                    <?php echo hr_function_for_search_products('hr_refund_include_products_srch', HR_REFUND_LOCALE); ?>
                </td>
            </tr><?php
        }

        /**
         * Display Include User Search
         */
        public static function hr_refund_include_user_search_function() {
            ?><tr>
                <th class="titledesc" scope="row">
                    <label id="hr_refund_include_user_srch" for="hr_refund_include_user_srch"><?php _e('Include Users', HR_REFUND_LOCALE); ?></label>
                </th>
                <td>
                    <?php echo hr_function_for_customer_search('hr_refund_include_user_srch', HR_REFUND_LOCALE); ?>
                </td>
            </tr><?php
        }

    }

    HR_Refund_General_Tab::init();
}