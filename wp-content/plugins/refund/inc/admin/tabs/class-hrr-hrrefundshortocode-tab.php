<?php
/* Shortcode Tab Settings */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('HR_Refund_Shortcode_Tab')) {

    /**
     * HR_Refund_Shortcode_Tab Class.
     */
    class HR_Refund_Shortcode_Tab {

        /**
         * HR_Refund_Shortcode_Tab Class initialization.
         */
        public static function init() {
            add_action('woocommerce_hr_refund_settings_hrrefundshortocode', array(__CLASS__, 'hr_refund_display_admin_settings'));
            add_action('woocommerce_admin_field_hr_refund_display_shorcode_sections', array(__CLASS__, 'hr_refund_display_shortcode_sections'));
        }

        /**
         * Prepare setting Array to display.
         */
        public static function hr_refund_prepare_admin_settings_array() {
            return apply_filters('woocommerce_hrrefundshortocode_settings', array(
                array(
                    'type' => 'hr_refund_display_shorcode_sections'
                ),
                array(
                    'type' => 'hr_shortcode_premium_info_refund',
                ),
            ));
        }

        /**
         * Display Shortcode table Section.
         */
        public static function hr_refund_display_shortcode_sections() {
            $shortcodes_info = array(
                "{hr-refund.date}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays date", HR_REFUND_LOCALE)),
                "{hr-refund.time}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays time", HR_REFUND_LOCALE)),
                "{hr-refund.orderid}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays refund requested order id", HR_REFUND_LOCALE)),
                "{hr-refund.sitename}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays site name", HR_REFUND_LOCALE)),
                "{hr-refund.newstaus}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays updated refund status", HR_REFUND_LOCALE)),
                "{hr-refund.oldstaus}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays the refund status before updation", HR_REFUND_LOCALE)),
                "{hr-refund.requestid}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays refund request id", HR_REFUND_LOCALE)),
                "{hr-refund.customername}" => array("mail" => __("Email", HR_REFUND_LOCALE),
                    "usage" => __("Displays customer name", HR_REFUND_LOCALE)),
                "[hr_refund_requests]" => array("mail" => __("Pages", HR_REFUND_LOCALE),
                    "usage" => __("Display Refund Request Table", HR_REFUND_LOCALE)),
            );
            ?>
            <table class="hr_refund_shortcodes_info">
                <thead>
                    <tr>
                        <th>
                            <?php _e('Shortcodes', HR_REFUND_LOCALE); ?>
                        </th>
                        <th>
                            <?php _e('Context where Shortcode is Valid', HR_REFUND_LOCALE); ?>
                        </th>
                        <th>
                            <?php _e('Purpose', HR_REFUND_LOCALE); ?>
                        </th>
                    </tr>
                </thead>
                <?php
                if (hr_check_is_array($shortcodes_info)) {
                    foreach ($shortcodes_info as $shortcode => $s_info) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $shortcode; ?>
                            </td>
                            <td>
                                <?php echo $s_info['mail']; ?>
                            </td>
                            <td>
                                <?php echo $s_info['usage']; ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <style type="text/css">
                .hr_refund_shortcodes_info{
                    margin-top:20px;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    var count = $('.hr_refund_shortcodes_info tbody tr:not(.footable-filtered)').length;
                    $('.hr_refund_shortcodes_info').footable();
                    $('.hr_refund_shortcodes_info').data('page-size', count);
                    $('.hr_refund_shortcodes_info').trigger('footable_initialized');
                })
            </script>
            <?php
        }

        /**
         * Display tab Settings
         */
        public static function hr_refund_display_admin_settings() {
            woocommerce_admin_fields(HR_Refund_Shortcode_Tab::hr_refund_prepare_admin_settings_array());
        }

    }

    HR_Refund_Shortcode_Tab::init();
}