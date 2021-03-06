<?php
/*
 * Plugin Name: WordPress Auto Updater
 * Version: 2.0
 */

if (!class_exists('HRR_License_Handler')) {

    /**
     * HRR_License_Handler Class
     * */
    class HRR_License_Handler {
        /*
         * Plugin Version Number
         */

        protected $version;
        /*
         * Plugin Directory Slug
         */
        protected $dir_slug;
        /*
         * Secret Key
         */
        protected $secret_key = '8b1a9953c4611296a827abf8c47804d7';

        /*
         * Site Url 
         */
        protected $update_path = 'https://hoicker.com/';
        
        /*
         * Item Key Name
         */
        protected $item_key_name = 'refund-free';

        /**
         * Option name
         */
        private $license_option = 'hrrf_products_license_activation';

        /**
         * Key option name
         */
        private $license_key_option = 'hrrf_products_license_activation_key';

        /**
         * HR_Plugin_Update_Checker Class Initialization
         * */
        public function __construct($plugin_version, $plugin_slug) {
            $this->version = $plugin_version;
            $this->dir_slug = $plugin_slug;
            add_action('wp_ajax_hrr_activate_handler', array($this, 'activation_handler'));

        }

        /**
         * Include activation page template
         */
        public function show_activation_panel($notice = '') {
            $purchase_code = $this->license_key();
            $name = ($purchase_code) ? 'Deactivate' : 'Activate';
            $handler = ($purchase_code) ? 'deactivate' : 'activate';
           ?>
            <form id='hr_license_activation_form' method='POST'>
                <div class='hr_license_label'>
                    <label><?php echo __('Purchase Code'); ?></label>
                </div>
                <div class='hr_license_activation'>
                    <input type='text' id='hr_license_key' name='hr_license_key'/>
                    <input type='hidden' id='hr_activate_handler_value' name='hr_activate_handler_value' value='<?php echo $handler; ?>'/>
                    <input type="submit" id='hr_activate_handler_button' name="hr_activate_handler_button" value="<?php echo $name; ?>" class="button button-primary"/>
                    <p class='hr_activation_messages hr_activation_error'></p>
                    <h4>Where can I find my License Key?</h4>
                    <ul>
                        <li><a href="https://hoicker.com/my-account/" target="blank"> Login</a> to Hoicker</li>
                        <li>Go to My Account page</li>
                        <li>In Orders section you will find your License Key</li>
                    </ul>
                </div>
                <div class='clear'></div>
            </form>
            <?php
        }

        /**
         * Activate or Deactive License Key
         * */
        public function activation_handler() {

            check_ajax_referer('hr-license-security', 'hr_security');

            if (!isset($_POST))
                throw new exception(__('Invalid Request', HR_REFUND_LOCALE));
            try {
                $license_key = $_POST['license_key'];
                $activation_handler = $_POST['handler'];
                if ($activation_handler == 'deactivate') {
                    $this->deactivate($license_key);
                } elseif ($activation_handler == 'activate') {
                    $this->activate($license_key);
                }
            } catch (Exception $ex) {
                wp_send_json_error(array('error' => $ex->getMessage()));
            }
        }

        /**
         * Verify data from API Endpoint
         * */
        protected function verify_activate_data($action, $license_key) {
            $necessary_data = array(
                'action' => $action,
                'license_key' => $license_key,
                'current_site' => site_url(),
                'plugin_version' => $this->version,
                'slug' => $this->dir_slug,
                'secret_key' => $this->secret_key,
                'item_key_name' => $this->item_key_name ,
                'free' => hr_is_refund_free_version(),
                'wc_version' => WC_VERSION,
                'wp_version' => get_bloginfo('version'),
            );

            $request = wp_remote_post($this->query_arg_url(), array('body' => $necessary_data));

            return $request;
        }

        /**
         * Activate license key for this site
         * */
        protected function activate($license_key) {
            try {
                $response_data = array();
                $activated_response = $this->verify_activate_data('activate_licensekey', $license_key);
                if (!is_wp_error($activated_response) || wp_remote_retrieve_response_code($activated_response) === 200) {
                    $response = json_decode(wp_remote_retrieve_body($activated_response));
                    if (is_object($response) && $response->success) {
                        update_option($this->license_option, $response);
                        update_option($this->license_key_option, $response->license_key);
                        $response_data['success_msg'] = __('Activated Successfully', HR_REFUND_LOCALE);
                    } else {
                        throw new Exception($this->error_messages($response->errorcode));
                    }
                } else {
                    throw new Exception($activated_response->get_error_message());
                }
                wp_send_json_success($response_data);
            } catch (Exception $ex) {
                wp_send_json_error(array('error_msg' => $ex->getMessage()));
            }
        }

        /**
         * Activate license key for this site
         * */
        protected function deactivate($license_key) {

            try {
                $saved_license_key = $this->license_key();
                if ($license_key != $saved_license_key)
                    throw new Exception(__('Please Provide Activated License Key', HR_REFUND_LOCALE));

                $response_data = array();
                $deactivated_response = $this->verify_activate_data('deactivate_licensekey', $license_key);
                if (!is_wp_error($deactivated_response) || wp_remote_retrieve_response_code($deactivated_response) === 200) {
                    $response = json_decode(wp_remote_retrieve_body($deactivated_response));
                    if (is_object($response) && $response->success) {
                        delete_option($this->license_option);
                        delete_option($this->license_key_option);
                        $response_data['success_msg'] = __('Deactivated Successfully', HR_REFUND_LOCALE);
                    } else {
                        throw new Exception($this->error_messages($response->errorcode));
                    }
                } else {
                    throw new Exception($deactivated_response->get_error_message());
                }
                wp_send_json_success($response_data);
            } catch (Exception $ex) {
                wp_send_json_error(array('error_msg' => $ex->getMessage()));
            }
        }

        /**
         * License Key
         * */
        public function license_key() {

            return get_option($this->license_key_option);
        }

        /**
         * API Endpoint Url
         * */
        protected function query_arg_url() {
            $api = 'wc-api';
            $terminology = 'hr_autoupdater';
            $url = esc_url(add_query_arg(array($api => $terminology), $this->update_path));
            return $url;
        }

        /**
         * Error Codes
         * */
        protected function error_messages($error_code) {

            $error_messages_array = array(
                '5001' => __('Invalid License Key', HR_REFUND_LOCALE),
                '5002' => __('Already Verified License Key', HR_REFUND_LOCALE),
                '5003' => __('Support Expired', HR_REFUND_LOCALE),
                '5004' => __('License Key not Verified', HR_REFUND_LOCALE),
                '5005' => __('Invalid Credentials', HR_REFUND_LOCALE),
                '5006' => __('license Count exist', HR_REFUND_LOCALE),
                '5007' => __('Incorrect Product', HR_REFUND_LOCALE),
            );

            $error_message = isset($error_messages_array[$error_code]) ? $error_messages_array[$error_code] : '';

            return $error_message;
        }

    }

}

