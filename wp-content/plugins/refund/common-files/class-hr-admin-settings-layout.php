<?php
if (!class_exists('HRW_Admin_settings')) {

    /**
     * HRW_Admin_settings Class.
     */
    class HRW_Admin_settings {

        /**
         * HRW_Admin_settings Class initialization.
         */
        public static function init() {
            global $menu_slug;
            if (isset($_GET['page'])) {
                $menu_slug = $_GET['page'];
            }
            add_action('hr_display_save_reset_buttons', array(__CLASS__, 'hr_admin_save_reset_buttons'));
            add_action('woocommerce_admin_field_hoicker_section_start', array(__CLASS__, 'hoicker_section_html_start'));
            add_action('woocommerce_admin_field_hoicker_section_close', array(__CLASS__, 'hoicker_section_html_close'));
            add_action ( 'add_meta_boxes' , array ( __CLASS__ , 'hr_admin_settings_button' ) ) ;
        }
        
        public static function hr_admin_settings_button() {
         
            $post_type_array = array('hr_balance_log', 'hr_transactions_log','hr_refund_request','hr_sales_trigger');
            foreach($post_type_array as $post_type) {
                    add_meta_box ( 'hr_back_to_settings_btn' , __ ( 'Goto Settings' , CUSTOM_TEXT_DOMAIN ) , array ( __CLASS__ , 'hr_back_to_settings_btn' ) , $post_type , 'side' ) ;
            }
        }
        
        public static function hr_back_to_settings_btn($post) {
            if(!is_object($post)) {
                return ;
            }
            $post_type= $post->post_type;
            
            $post_slug_array = array('hr_balance_log', 'hr_transactions_log','hr_refund_request','hr_sales_trigger');
            
            $settings_slug = array_search($post_type,$post_slug_array)!=''?array_search($post_type,$post_slug_array):'';
           
            if($settings_slug=='') {
                return;
            }
            $module_url = admin_url('admin.php?page='.$settings_slug);
            wp_enqueue_style('hr_hoicker_admin_style', CUSTOM_PLUGIN_URL . '/assets/style/hr-hoicker-styles.css', '', CUSTOM_VERSION, '');
        ?>
                <a class="hr_go_to_settings_btn" href="<?php echo $module_url; ?>">Go to Settings</a>
        <?php
        }

        public static function hoicker_section_html_start() {
            ?>
            <div class="hr_section">
                <?php
            }

            public static function hoicker_section_html_close() {
                ?>
            </div><?php
        }

        /**
         * Display Hoicker Layout
         */
       

        public static function hr_admin_settings_layout() {
            global $menu_slug;
            $tabs = array();
            $hr_tab_array = apply_filters('hr_tabs_array_' . $menu_slug, $tabs);
            /* Save tab Settings */
            self::hr_save_tab_settings();
            /* Reset Settings Values */
            self::hr_reset_tab_settings();
                ?>
            <div class="wrap woocommerce hr_wrapper">
                <div class="warnning_msg">
                    <h2></h2>
                </div>
                <div class="hr_header">
                    <a href="https://hoicker.com/" target="blank"><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/hoicker-logo.png' ?>"></a>
                </div>
                <form method="post" id="mainform" action="" enctype="multipart/form-data">
                    <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br />
                    </div>
                    <div class="hr_main">
                        <div class="nav-tab-wrapper woo-nav-tab-wrapper hr_tab">
                            <?php
                            $i = 0;
                            echo self::hr_display_hoicker_menus($menu_slug);
                            ?>
                        </div>
                        <div class="hr_content">
                            <?php do_action('hr_premium_info_' . $menu_slug); ?>
                            <div class="hr_inner_content ">
                                <?php
                                /* Display Error or Warning Messages */
                                self::hr_display_tab_message();

                                foreach ($hr_tab_array as $name => $label) {

                                    if ($i == 0) {
                                        ?> 
                                        <div class=" hr_inner_tab_content hr_settings_<?php echo $name; ?>">
                                            <?php
                                            //current tab execute function.
                                            do_action('woocommerce_' . $menu_slug . '_' . $name);
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                //display buttons
                                do_action('hr_display_save_reset_buttons');
                                $i ++;
                                ?>

                            </div>
                            <?php do_action('hr_premium_info_' . $menu_slug); ?>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }

        /**
         * Display Hoicker Menus
         */
        public static function hr_display_hoicker_menus($menu_slug) {
            ob_start();
            $tabs = array();
            $hoicker_menus = apply_filters('hoicker_modules_menus', $tabs);
            $classes_array = apply_filters($menu_slug . "_additional_classes", $tabs);
            $tabs_global = apply_filters('woocommerce_hoicker_modules_settings_tabs_array', $tabs);

            echo "<ul id='hr_modules_settings'>";
            if (is_array($tabs_global)) {
                foreach ($tabs_global as $name => $label)
                    if ($name == 'hr-hoicker-global-modules') {
                        echo '<li class="' . $name . '" id="' . $name . '" ><a id="' . $name . '" href="?page=hr_hoicker_module_slug#hr_a_' . $name . '" class="nav-tab ' . $name . '">' . $label . '</a></li>';
                    }
            }

            foreach ($hoicker_menus as $menu_key => $menus_array) {
                $module_url = admin_url('admin.php?page=' . $menu_key . '');

                $outer_class_datas = isset($classes_array["$menu_key"]) ? $classes_array["$menu_key"] : '';

                    if ($menu_key != $menu_slug) {
                        ?>
                        <li class="hr_first_nested_tab_li"><a  href="<?php echo $module_url; ?>" class="nav-tab hr_first_nested_inner_tab_a <?php echo $menu_key; ?>"><?php echo $menus_array["$menu_key"]; ?></a></li>
                        <?php
                    }
                    if ($menu_key == $menu_slug) {
                        $i = 0;
                        foreach ($hoicker_menus["$menu_slug"] as $inner_menu_key => $menu_label) {
                            if (post_type_exists($inner_menu_key)) {
                                $module_url = add_query_arg(array('post_type' => "$inner_menu_key"), admin_url('edit.php'));
                            } else {
                                $module_url = "#hr_a_" . $inner_menu_key;
                            }

                            $inner_class_datas = isset($classes_array["$inner_menu_key"]) ? $classes_array["$inner_menu_key"] : '';
                            if ($i == '0') {
                                ?>
                                <li class="hr_first_nested_tab_li"><a  href="<?php echo $module_url; ?>" class="nav-tab  hr_first_nested_inner_tab_a <?php echo $inner_menu_key; ?>"><?php echo $menu_label; ?></a></li>
                            <?php } else {
                                ?>
                                <li class="hr_second_nested_tab_li <?php echo $inner_class_datas; ?>"><a id= "<?php echo $inner_menu_key; ?>" href="<?php echo $module_url; ?>" class="hoicker_inner_tab nav-tab hr_second_nested_inner_tab_a <?php echo $inner_menu_key; ?>"><?php echo $menu_label; ?></a></li>
                                <?php
                            }
                            $i ++;
                        }
                    }
            }
            if (is_array($tabs_global)) {
                foreach ($tabs_global as $name => $label)
                    if ($name != 'hr-hoicker-global-modules') {
                        echo '<li class="' . $name . '" id="' . $name . '" ><a id="' . $name . '" href="?page=hr_hoicker_module_slug#hr_a_' . $name . '" class="nav-tab ' . $name . '">' . $label . '</a></li>';
                    }
            }
            echo "</ul>";
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }

        /**
         * Display Reset and Save Button
         */
        public static function hr_admin_save_reset_buttons() {
            global $menu_slug;
            if ($menu_slug == '') {
                return;
            }
            ?> <p class = 'submit'>
                <?php if (!isset($GLOBALS['hide_save_button'])) :
                    ?>
                    <input name='save_<?php echo $menu_slug; ?>' class='hr_hoicker_settings_button button-primary hr_save' type='submit' value="<?php _e('Save', CUSTOM_TEXT_DOMAIN); ?>" />

                <?php endif; ?>
                <input type='hidden' name='subtab' id='last_tab' />
                <?php wp_nonce_field('woocommerce-hr_settings', '_wpnonce', true, true); ?>
            </p>
            </form>
            <form method='post' id='mainforms' action='' enctype='multipart/form-data' style="float: left; margin-top: -52px; margin-left: 100px;">
                <input id='reset' name='reset_<?php echo $menu_slug; ?>' class='hr_hoicker_settings_button button-secondary hr_reset' type='submit' value="<?php _e('Reset', CUSTOM_TEXT_DOMAIN); ?>"/>
                <?php
                wp_nonce_field('woocommerce-reset_hr_settings', '_wpnonce', true, true);
            }

            /**
             * update Tab settings
             */
            public static function hr_save_tab_settings() {
                global $menu_slug;
                if (!empty($_POST['save_' . $menu_slug])) {

                    if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-hr_settings'))
                        die(__('Action failed. Please refresh the page and retry'));
                    // update Page values
                    do_action('woocommerce_' . $menu_slug . '_global_save');

                    do_action('woocommerce_update_options');
                    // Clear any unwanted data
                    delete_transient('woocommerce_cache_excluded_uris');

                    $redirect = esc_url_raw(add_query_arg(array('saved' => 'true')));
                    if (isset($_POST['subtab'])) {
                        wp_safe_redirect($redirect);
                        exit;
                    }
                }
            }

            /**
             * Reset Response functionality for tab
             */
            public static function hr_reset_tab_settings() {
                global $menu_slug;
                if (!empty($_POST['reset_' . $menu_slug])) {
                    if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-reset_hr_settings'))
                        die(__('Action failed. Please refresh the page and retry'));
                    do_action('woocommerce_' . $menu_slug . '_global_reset');
                    delete_transient('woocommerce_cache_excluded_uris');
                    $redirect = esc_url_raw(add_query_arg(array('resetted' => 'true')));
                    wp_safe_redirect($redirect);
                    exit;
                }
            }

            /**
             * Display Error Message When Reset or Save Settings
             */
            public static function hr_display_tab_message() {
                // Get any returned messages
                $error = ( empty($_GET['wc_error']) ) ? '' : urldecode(stripslashes($_GET['wc_error']));
                $message = ( empty($_GET['wc_message']) ) ? '' : urldecode(stripslashes($_GET['wc_message']));

                if ($error || $message) {

                    if ($error) {
                        echo '<div id="message" class="error fade"><p><strong>' . esc_html($error) . '</strong></p></div>';
                    } else {
                        echo '<div id="message" class="updated fade"><p><strong>' . esc_html($message) . '</strong></p></div>';
                    }
                } elseif (!empty($_GET['saved'])) {
                    echo '<div id="message" class="hr-save"><p>' . __('Your settings have been saved.', CUSTOM_TEXT_DOMAIN) . '<img src="' . CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/cancel-16.png"></p></div>';
                } elseif (!empty($_GET['resetted'])) {
                    echo '<div id="message" class="hr-reset"><p>' . __('Your settings have been Restored.', CUSTOM_TEXT_DOMAIN) . '<img src="' . CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/cancel-16.png"></p></div>';
                }
            }

        }

        HRW_Admin_settings::init();
    }