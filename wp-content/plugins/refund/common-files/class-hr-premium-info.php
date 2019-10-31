<?php
if (!class_exists('HR_Premium_Info')) {

    /**
     * HR_Premium_Info Class.
     */
    class HR_Premium_Info {

        /**
         * HR_Premium_Info Class initialization.
         */
        public static function init() {
        }
        
        public static function hr_buttons_shortcode_premium_message() { 
             echo do_action('hr_shortcode_premium_info');  
        }

        

        public static function hr_welcome_page_premium_info_message() {
            global $hr_menu_slug;
            ob_start();
            ?>
            <div class="hr_welcome_update_wrapper">
                <div class="hr_welcome_update__container">
                    <div class="hr_welcome_update_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/image/white-logo.png' ?>">  Premium Version</h3>
                    </div>
                    <div class="hr_welcome_update_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_welcome_update_features_container">
                   <div class="hr_welcome_update_features_column"> 
                       <img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/image/list-rich.png' ?>">
                       <h3>Access Complete Features</h3>
                    </div>
                   <div class="hr_welcome_update_features_column"> 
                        <img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/image/lifebuoy.png' ?>">
                        <h3>Premium Support</h3>
                    </div>
                   <div class="hr_welcome_update_features_column"> 
                        <img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/image/bar-chart-3.png' ?>">
                        <h3>And More</h3>
                    </div>
                </div>
            </div>    
            <?php
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }
        
        
        public static function hr_global_premium_info_message() {
            global $hr_menu_slug;
            ob_start();
            ?>
            <div class="hr_update_wrapper">
                <div class="hr_update__container">
                    <div class="hr_update_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png' ?>">  Premium Version</h3>
                    </div>
                    <div class="hr_update_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_update_features_container">
                   <div class="hr_update_features_column"> 
                       <img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/list-rich.png' ?>">
                       <h3>Access Complete Features</h3>
                    </div>
                   <div class="hr_update_features_column"> 
                        <img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/lifebuoy.png' ?>">
                        <h3>Premium Support</h3>
                    </div>
                   <div class="hr_update_features_column"> 
                        <img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/bar-chart-3.png' ?>">
                        <h3>And More</h3>
                    </div>
                </div>
            </div>  
            <?php
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }

        public static function hr_inner_wallet_module_premium_info_message() {
            ob_start();
            ?>
            <div class="hr_update_inner_wrapper">
                <div class="hr_update_inner_container">
                    <div class="hr_update_inner_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png' ?>">  Premium Version Wallet Module Features</h3>
                    </div>
                    <div class="hr_update_inner_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_update_inner_features_container">
                   <div class="hr_update_inner_features_column"> 
                        <ul>
                            <li>Partial Payments using Wallet</li>
                            <li>Wallet Funds Usage Restrictions</li>
                            <li>More Notification Emails</li>
                        </ul> 
                    </div>
                    <div class="hr_update_inner_features_column"> 
                        <ul>
                            <li>Access to Shortcodes</li>
                            <li>Warning for Low Wallet Funds Threshold</li>
                            <li>Compatibility with other Modules And Plugins</li>
                        </ul> 
                    </div>
                    <div class="hr_update_inner_features_column"> 
                        <ul>
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
        public static function hr_inner_refund_module_premium_info_message() {
            ob_start();
            ?>
            <div class="hr_update_inner_wrapper">
                <div class="hr_update_inner_container">
                    <div class="hr_update_inner_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png' ; ?>">  Premium Version Refund Module Features</h3>
                    </div>
                    <div class="hr_update_inner_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
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
         public static function hr_inner_buttons_module_premium_info_message() {
            ob_start();
            ?>
            <div class="hr_update_inner_wrapper">
                <div class="hr_update_inner_container">
                    <div class="hr_update_inner_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png'; ?>">  Premium Version Multi-Purpose Buttons Module Features</h3>
                    </div>
                    <div class="hr_update_inner_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_update_inner_features_container">
                   <div class="hr_update_inner_features_column"> 
                        <ul>
                            <li>More Button Positions</li>
                            <li>Custom CSS</li>
                            <li>Access to Shortcodes</li>
                            
                            
                        </ul> 
                    </div>
                    <div class="hr_update_inner_features_column"> 
                        <ul>
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
        public static function hr_inner_hrst_module_premium_info_message() {
            ob_start();
            ?>
            <div class="hr_update_inner_wrapper">
                <div class="hr_update_inner_container">
                    <div class="hr_update_inner_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png'; ?>">  Premium Version Sales Triggers Module Features</h3>
                    </div>
                    <div class="hr_update_inner_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_update_inner_features_container">
                   <div class="hr_update_inner_features_column" style="width:45%;"> 
                        <ul>
                            <li>Display triggers for specific products</li>
                            <li>More Display Positions for triggers</li>
                            <li>Adding Unlimited Guarantee Information</li>
                        </ul> 
                    </div>
                    <div class="hr_update_inner_features_column" style="width:45%;"> 
                        <ul>
                            <li>Option to Hide Best Review Trigger when the Rating Percentage is low</li>
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
        
        public static function hr_inner_social_share_module_premium_info_message() {
            ob_start();
            ?>
            <div class="hr_update_inner_wrapper">
                <div class="hr_update_inner_container">
                    <div class="hr_update_inner_content">
                        <h3><img src="<?php echo CUSTOM_PLUGIN_URL . '/common-files/assets/images/admin-layout/white-logo.png'; ?>">  Premium Version Social Share Module Features</h3>
                    </div>
                    <div class="hr_update_inner_button">
                        <a href="https://hoicker.com/">Upgrade to Premium Version</a>
                    </div>
                </div>
                <div class="hr_update_inner_features_container">
                   <div class="hr_update_inner_features_column" style="width:40%;"> 
                        <ul>
                            <li>More Customizable Target URLs</li>
                            <li>More Button Postitions</li>
                            <li>Social Share Button Restrictions</li>
                        </ul> 
                    </div>
                    <div class="hr_update_inner_features_column"> 
                        <ul>
                            <li>Custom CSS</li>
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
                <p>Compatiblity is available in <a href="https://hoicker.com/">Hoicker Premium Version</a> </p>
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
                <p>Shortcodes are available in <a href="https://hoicker.com/">Hoicker Premium Version</a> </p>
            </div>  
            <?php
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }
    }

    HR_Premium_Info::init();
}