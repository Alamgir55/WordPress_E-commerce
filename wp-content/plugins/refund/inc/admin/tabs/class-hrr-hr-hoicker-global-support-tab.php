<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('HR_Hoicker_Support_Tab_Refund')) {

    /**
     * HR_Hoicker_Support_Tab Class.
     */
    class HR_Hoicker_Support_Tab_Refund {

        public static function init() {
           
            add_action('woocommerce_hr_refund_settings_hr-hoicker-global-support', array(__CLASS__, 'hr_hoicker_admin_setting_support'));
            add_action('woocommerce_admin_field_hoicker_support_display', array(__CLASS__, 'hoicker_support_display_function'));
        }

        public static function hoicker_support_display_function() {
            
              $hoicker_welcome_page_url = admin_url ( 'admin.php?page=hoicker-welcome-page' ) ;
            ?>
          <div class="hr_support">
            <h3>Welcome Page</h3>
            <p> For more information on Hoicker please check the  <a href="<?php echo $hoicker_welcome_page_url; ?>" >Welcome page</a></p>
            <h3>Documentation</h3>
            <p> Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.</p>
            
            <?php  if (!class_exists('HR_Wallet_Premium')) { ?>
                    <h3>Contact Support</h3>
            <p id="fp_support_content">You can Report Bugs <a href="https://hoicker.com/support" target="_blank">Here</a></p>
            
           <?php } else { ?>
                <h3>Contact Support</h3>
            <p id="fp_support_content"> For support, feature request or any help, please <a href="https://hoicker.com/support" target="_blank">register and open a support ticket on our site</a></p>
            
           <?php } ?>
            <?php
            }

            public static function hr_hoicker_menu_options_messages() {
                return apply_filters('woocommerce_hr_hoicker_global_support_settings', array(
                    array(
                        'type' => 'title',
                        'id' => 'hoicker_global_support'
                    ),
                    array(
                        'type' => 'hoicker_support_display',
                    ),
                    array('type' => 'sectionend', 'id' => 'hoicker_global_support'),
                        ));
            }

            public static function hr_hoicker_admin_setting_support() {
                woocommerce_admin_fields(HR_Hoicker_Support_Tab_Refund::hr_hoicker_menu_options_messages());
            }
        }

        HR_Hoicker_Support_Tab_Refund::init();
    }