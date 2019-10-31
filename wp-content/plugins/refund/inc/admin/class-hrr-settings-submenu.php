<?php
/*
 * Settings Sub Menu
 */

if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HR_Refund_Settings' ) ) {

    /**
     * HR_Refund_Settings Class.
     */
    class HR_Refund_Settings {

        /**
         * HR_Refund_Settings Class initialization.
         */
        public static function init() {
            add_filter ( 'plugin_action_links_' . HR_REFUND_PLUGIN_BASE_NAME , array ( __CLASS__ , 'hr_plugin_extra_action' ) ) ;
            add_action ( 'admin_menu' , array ( __CLASS__ , 'hr_refund_add_submenu_page' ) , 20 ) ;
            if ( isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'hr_refund_settings' ) {
                add_action ( 'init' , array ( __CLASS__ , 'hrr_init_incude' ));
                add_action ( 'wp_print_scripts' , array ( __CLASS__ , 'dequeue_scripts_refund' ) , 100 ) ;
                add_filter ( 'hoicker_modules_menus' , array ( __CLASS__ , 'hr_module_menus' ) , 10 , 1 ) ;
                add_filter ( 'screen_settings' , array ( __CLASS__ , 'hr_refund_sorting_table_option' ) , 10 , 2 ) ;
                add_filter ( 'init' , array ( __CLASS__ , 'hr_refund_set_screen_option_value' ) ) ;
                add_filter ( 'hr_tabs_array_hr_refund_settings' , array ( __CLASS__ , 'hr_refund_add_settings_menu_page_tabs' ) ) ;
                //Placing Goto settings button
                add_action ( 'manage_posts_extra_tablenav' , array ( __CLASS__ , 'manage_posts_extra_table' ) ) ;
                //Settings update reset save 
                add_action ( 'woocommerce_hr_refund_settings_global_save' , array ( __CLASS__ , 'hr_refund_update_settings_values' ) ) ;
                add_action ( 'woocommerce_hr_refund_settings_global_reset' , array ( __CLASS__ , 'hr_refund_reset_default_settings_values' ) ) ;
                add_action ( 'woocommerce_update_options_refund_global_save' , array ( __CLASS__ , 'hr_refund_add_default_settings_values' ) ) ;
            }
        }
        
        public static function hrr_init_incude() {
            $include_tabs_array = self::hr_refund_add_settings_menu_page_tabs ( '' ) ;

            foreach ( $include_tabs_array as $include_tab_keys => $include_tabs ) {
                /* Include current page functionality */
                include_once(HR_REFUND_PLUGIN_PATH . '/inc/admin/tabs/class-hrr-' . $include_tab_keys . '-tab.php') ;
            }
        }
        
                /**
         * Show action links on the plugin screen.
         *
         * @param	mixed $links Plugin Action links
         * @return	array
         */
        public static function hr_plugin_extra_action( $links ) {
            if ( hr_is_refund_free_version () ) {
                $action_links = array (
                    'hr_hoicker_settings' => '<a href="' . admin_url ( 'admin.php?page=hr_refund_settings' ) . '" aria-label="' . esc_attr__ ( 'Settings' , HR_REFUND_LOCALE ) . '">' . esc_attr__ ( 'Settings' , HR_REFUND_LOCALE ) . '</a>' ,
                    'hr_premium_upgrade'  => '<a href="https://hoicker.com/refund" aria-label="' . esc_attr__ ( 'Upgrade to Premium' , HR_REFUND_LOCALE ) . '">' . esc_attr__ ( 'Upgrade to Premium' , HR_REFUND_LOCALE ) . '</a>' ,
                    'hr_live_demo'        => '<a href="https://demo.hoicker.com" aria-label="' . esc_attr__ ( 'Live demo' , HR_REFUND_LOCALE ) . '">' . esc_attr__ ( 'Live demo' , HR_REFUND_LOCALE ) . '</a>' ,
                        ) ;
                return array_merge ( $links , $action_links ) ;
            } else {
                $action_links = array (
                    'hr_hoicker_settings' => '<a href="' . admin_url ( 'admin.php?page=hr_refund_settings' ) . '" aria-label="' . esc_attr__ ( 'Settings' , HR_REFUND_LOCALE ) . '">' . esc_attr__ ( 'Settings' , HR_REFUND_LOCALE ) . '</a>' ,
                    ) ;
                return array_merge ( $links , $action_links ) ;
                
            }
            return $links ;
        }
        
         public static function hr_inner_refund_module_premium_info_message() {
            ob_start();
            ?>
            <div class="hr_update_inner_wrapper">
                <div class="hr_update_inner_container">
                    <div class="hr_update_inner_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png' ; ?>"> Refund Premium Version Plugin Features</h3>
                    </div>
                    <div class="hr_update_inner_button">
                        <a href="https://hoicker.com/product/refund">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_update_inner_features_container">
                   <div class="hr_update_inner_features_column"> 
                        <ul>
                            <li>Partial Refund</li>
                            <li>Refund Restrictions</li>
                            <li>Refund Conversations</li>
                            <li>Access to Shortcodes</li>
                        </ul> 
                    </div>
                    <div class="hr_update_inner_features_column" style="width:45%;"> 
                        <ul>
                            <li>Refund directly to Hoicker Wallet</li>
                            <li>Compatibility with other Modules and Plugins</li>
                            <li>And More</li>
                        </ul> 
                    </div>
                </div>
            </div> 
            <?php
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }
        
                public static function hr_compatiblity_premium_info_message() {
            ob_start();
            ?>
            <div class="compatiblity_pro_msg">
                <p>Compatiblity is available in <a href="https://hoicker.com/product/refund">Refund Premium Version</a> </p>
            </div>  
            <?php
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }
        
        public static function hr_shortcode_premium_info_message() {
            ob_start();
            ?>
            <div class="compatiblity_pro_msg">
                <p>Shortcodes are available in <a href="https://hoicker.com/product/refund">Refund Premium Version</a> </p>
            </div>  
            <?php
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }
        
        public static function hr_premium_scripts_enqueues() {
            if ( isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'hr_refund_settings' ) {
                wp_enqueue_style('hrr_premium_info_style', CUSTOM_PLUGIN_URL . '/common-files/assets/style/premium-info/hr-hoicker-upgrade_message_styles.css', '', HR_REFUND_VERSION, '');
            wp_register_script('hrr_premium_info_js', CUSTOM_PLUGIN_URL . '/common-files/assets/js/admin-tabs/premium-info/jQuery-global-premium-info.js', array('jquery'), HR_REFUND_VERSION);
            wp_localize_script('hrr_premium_info_js', 'hr_premium_args', array(
                'hr_premium_info_html' => '<p>This feature is  available in <a href="https://hoicker.com/product/refund">Refund Premium Version</a></p>',
            ));
            wp_enqueue_script('hrr_premium_info_js');
            }
        }
        
        
        public static function dequeue_scripts_refund() {
            if( ! hr_is_refund_free_version () && isset($_GET['page']) && $_GET['page'] == 'hr_refund_settings') {
                wp_dequeue_style ( 'hr_premium_info_style' ) ;
                wp_dequeue_script ( 'hr_premium_info_js' ) ;
            }
        }

        public static function hr_module_menus( $filter_array ) {
            
            $tabs                                 = array () ;
            $module_menu_label                    = array (
                'hr_refund_settings' => 'Refund' ,
                    ) ;
            $post_types                           = array (
                'hr_refund_request' => 'Refund Requests' ,
            ) ;
            $module_menus                         = apply_filters ( 'hr_tabs_array_hr_refund_settings' , $tabs ) ;
            $merged_array                         = array_merge_recursive ( $module_menu_label , $post_types , $module_menus ) ;
            $filter_array[ 'hr_refund_settings' ] = $merged_array ;
            return $filter_array ;
        }

        public static function manage_posts_extra_table( $which ) {
            global $post ;
            if ( $which == 'top' && (((is_object ( $post ) && ($post->post_type == 'hr_refund_request' ))) || (isset ( $_REQUEST[ 'post_type' ] ) && $_REQUEST[ 'post_type' ] == 'hr_refund_request' )) ) {
                $refund_plugin_url = admin_url ( 'admin.php?page=hr_refund_settings' ) ;
                ?>
                <a class="hr_go_to_settings_btn" href="<?php echo $refund_plugin_url ; ?>">Go to Settings</a>
                <?php
            }
        }

        /**
         * Add new Sub Menu Page
         */
        public static function hr_refund_add_submenu_page() {
           
            
            if (  class_exists ( 'HR_Hoicker' ) ) {
                    add_submenu_page ( 'hr_hoicker_module_slug' , __ ( 'Refund_Settings' , HR_REFUND_LOCALE ) , __ ( 'Settings' , HR_REFUND_LOCALE ) , 'manage_woocommerce' , 'hr_refund_settings' , array ( 'HRW_Admin_settings' , 'hr_admin_settings_layout' ) ) ;
             } else if ( ! class_exists ( 'HR_Hoicker' ) ) {
                 $menu_label = hr_is_refund_free_version () ?  'Refund' : 'Refund Premium';
                $icon_url = HR_REFUND_PLUGIN_URL . '/assets/images/refund-icon16.png' ;
                add_menu_page ( __ ( "$menu_label" , HR_REFUND_LOCALE ) , __ ( "$menu_label" , HR_REFUND_LOCALE ) , 'manage_woocommerce' , 'hr_refund_settings' , array ( 'HRW_Admin_settings' , 'hr_admin_settings_layout' ) , $icon_url ) ;
             }
            
        }

        /**
         * Extra Screen Option
         */
        public static function hr_refund_sorting_table_option( $screen_settings , $screen_object ) {
            if ( isset ( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] == 'hr_refund_request' ) {
                $option_name     = 'hr_refund_request_asc_desc' ;
                $option_value    = get_user_option ( $option_name ) ;
                ob_start () ;
                ?><fieldset class="screen-options">
                    <legend><?php _e ( 'Sorting' , HR_REFUND_LOCALE ) ; ?></legend>
                    <label for=""><?php _e ( 'Display Table in' , HR_REFUND_LOCALE ) ; ?></label>
                    <select id="<?php echo $option_name ; ?>" name="hr_refund_screen_extra_options[value]">
                        <option value="desc" <?php selected ( $option_value , 'desc' ) ?>><?php _e ( 'Descending Order' , HR_REFUND_LOCALE ) ; ?></option>
                        <option value="asc" <?php selected ( $option_value , 'asc' ) ?>><?php _e ( 'Ascending Order' , HR_REFUND_LOCALE ) ; ?></option>
                    </select>
                    <input type="hidden" name="hr_refund_screen_extra_options[option]" value="<?php echo $option_name ; ?>" />
                </fieldset><?php
                $extra_settings  = ob_get_clean () ;
                $screen_settings .= $extra_settings ;
            }
            return $screen_settings ;
        }

        /**
         * Save value of no of Item per page
         */
        public static function hr_refund_set_screen_option_value() {
            if ( isset ( $_POST[ 'hr_refund_screen_extra_options' ] ) && is_array ( $_POST[ 'hr_refund_screen_extra_options' ] ) ) {

                if ( ! $user = wp_get_current_user () )
                    return ;

                $option_name  = $_POST[ 'hr_refund_screen_extra_options' ][ 'option' ] ;
                $option_value = $_POST[ 'hr_refund_screen_extra_options' ][ 'value' ] ;
                update_user_meta ( $user->ID , $option_name , $option_value ) ;
            }
        }

        /**
         * Add Tabs for Sub Menu
         */
        public static function hr_refund_add_settings_menu_page_tabs( $tabs ) {
            if ( ! is_array ( $tabs ) ) {
                $tabs = ( array ) $tabs ;
            }
            $tabs[ 'hrrefundgeneral' ]      = __ ( 'General' , HR_REFUND_LOCALE ) ;
            $tabs[ 'hrrefundemail' ]        = __ ( 'Email' , HR_REFUND_LOCALE ) ;
            $tabs[ 'hrrefundlocalization' ] = __ ( 'Localization' , HR_REFUND_LOCALE ) ;
            $tabs[ 'hrcompatibility' ]      = __ ( 'Compatibility' , HR_REFUND_LOCALE ) ;
            $tabs[ 'hrrefundshortocode' ]   = __ ( 'Shortcode' , HR_REFUND_LOCALE ) ;
            $tabs[ 'hr-hoicker-global-license' ]    = __ ( 'License Verification' , HR_REFUND_LOCALE ) ;
            $tabs[ 'hr-hoicker-global-support' ]    = __ ( 'Support' , HR_REFUND_LOCALE ) ;
            return array_filter ( $tabs ) ;
        }
    
            /**
             * set default values for settings
             */
            public static function hr_refund_set_default_settings_values( $settings , $reset = true ) {
                if ( hr_check_is_array ( $settings ) ) {
                    foreach ( $settings as $setting ) {
                        if ( (isset ( $setting[ 'newids' ] )) && (isset ( $setting[ 'std' ] )) ) {
                            if ( $reset ) {
                                if ( get_option ( $setting[ 'newids' ] ) == false )
                                    add_option ( $setting[ 'newids' ] , $setting[ 'std' ] ) ;
                            }else {
                                update_option ( $setting[ 'newids' ] , $setting[ 'std' ] ) ;
                            }
                        }
                    }
                }
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
            
            
            
            

        }

        HR_Refund_Settings::init () ;
    }