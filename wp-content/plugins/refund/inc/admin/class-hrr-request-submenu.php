<?php
/**
 * Admin Refund Request Custom Post Type.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Request_Table')) {

    /**
     * HR_Refund_Request_Table Class.
     */
    class HR_Refund_Request_Table {

        /**
         * HR_Refund_Request_Table Class initialization.
         */
        public static function init() {

            add_action('views_edit-hr_refund_request', array(__CLASS__, 'remove_post_type_views'));
            add_action('restrict_manage_posts', array(__CLASS__, 'hr_refund_add_extra_filter_option'));
            add_action('posts_where', array(__CLASS__, 'hr_refund_request_filter_functionality'), 10, 2);
            add_action('posts_orderby', array(__CLASS__, 'hr_refund_request_orderby_functionality'), 10, 2);
            add_action('manage_hr_refund_request_posts_custom_column', array(__CLASS__, 'hr_refund_display_refund_request_table_data'), 10, 2);

            add_filter('posts_search', array(__CLASS__, 'hr_refund_request_search_fields'));
            add_filter('parse_query', array(__CLASS__, 'hr_refund_request_order_filters_query'));
            add_filter('post_row_actions', array(__CLASS__, 'hr_refund_request_post_row_actions'), 10, 2);
            add_filter('disable_months_dropdown', array(__CLASS__, 'hr_refund_remove_month_dropdown'), 10, 2);
            add_filter('manage_hr_refund_request_posts_columns', array(__CLASS__, 'hr_refund_request_columns'));
            add_filter('bulk_actions-edit-hr_refund_request', array(__CLASS__, 'hr_refund_request_bulk_post_actions'), 10, 1);
            add_filter('manage_edit-hr_refund_request_sortable_columns', array(__CLASS__, 'hr_refund_request_sortable_columns'));
        }

        /**
         * Initialization of columns in Refund Request table
         */
        public static function hr_refund_request_columns($columns) {
            $columns = array(
                'cb' => $columns['cb'],
                'hr_refund_id' => __('ID', HR_REFUND_LOCALE),
                'hr_refund_user_name' => __('User Name/Email', HR_REFUND_LOCALE),
                'hr_refund_order_id' => __('Order ID', HR_REFUND_LOCALE),
                'hr_refund_request_as' => __('Refund Request as', HR_REFUND_LOCALE),
                'hr_refund_request_status' => __('Refund Request Status', HR_REFUND_LOCALE),
                'hr_refund_request_type' => __('Refund Type', HR_REFUND_LOCALE),
                'hr_refund_request_reason' => __('Refund Reason', HR_REFUND_LOCALE),
                'hr_refund_request_date' => __('Refund Requested Date', HR_REFUND_LOCALE),
            );
            return $columns;
        }

        /**
         * Initialization of sortable columns in Refund Request table
         */
        public static function hr_refund_request_sortable_columns($columns) {
            $array = array(
                'hr_refund_id' => 'hr_refund_id',
                'hr_refund_user_name' => 'hr_refund_user_name',
                'hr_refund_order_id' => 'hr_refund_order_id',
                'hr_refund_request_as' => 'hr_refund_request_as',
                'hr_refund_request_status' => 'hr_refund_request_status',
                'hr_refund_request_type' => 'hr_refund_request_type',
                'hr_refund_request_date' => 'hr_refund_request_date',
            );
            return wp_parse_args($array, $columns);
        }

        /**
         * Modify a row post actions
         */
        public static function hr_refund_request_post_row_actions($actions, $post) {
            if ($post->post_type == 'hr_refund_request') {
                unset($actions['inline hide-if-no-js']);
                $url = add_query_arg(array('post' => $post->ID, 'action' => 'edit'), admin_url('post.php'));
                $actions['edit'] = '<a href="' . $url . '">' . __('View Request', HR_REFUND_LOCALE) . '</a>';
            }
            return $actions;
        }

        /**
         * Modify Bulk post actions
         */
        public static function hr_refund_request_bulk_post_actions($actions) {
            global $post;
            if (isset($post->post_type) && ($post->post_type == 'hr_refund_request')) {
                unset($actions['edit']);
            }
            return $actions;
        }

        /*
         * Remove Custom Post Type Views
         */

        public static function remove_post_type_views($views) {
            unset($views['mine']);
            return $views;
        }

        /**
         * Remove month dropdown 
         */
        public static function hr_refund_remove_month_dropdown($bool, $post_type) {
            return $post_type == 'hr_refund_request' ? true : $bool;
        }

        /**
         * Adding extra filter in table
         */
        public static function hr_refund_add_extra_filter_option($post_type) {
            if ($post_type == 'hr_refund_request') {
                //display date filter for Recovered Order table 
                $fromdate = '';
                $todate = '';
                if (isset($_REQUEST['filter_action'])) {
                    $fromdate = isset($_REQUEST['hr_refund_request_fromdate']) ? $_REQUEST['hr_refund_request_fromdate'] : "";
                    $todate = isset($_REQUEST['hr_refund_request_todate']) ? $_REQUEST['hr_refund_request_todate'] : "";
                }
                ?>
                <input id='hr_refund_request_fromdate' type='text' placeholder="<?php _e('From Date', HR_REFUND_LOCALE); ?>"  name='hr_refund_request_fromdate' value="<?php echo $fromdate; ?>"/>
                <input id='hr_refund_request_todate' type='text' name='hr_refund_request_todate' value="<?php echo $todate; ?>" placeholder="<?php _e('To Date', HR_REFUND_LOCALE); ?>"/>
                <?php
            }
        }

        /**
         * Display each column data in Refund Request table
         */
        public static function hr_refund_display_refund_request_table_data($column, $postid) {
            switch ($column) {
                case 'hr_refund_id':
                    echo "<a href=" . admin_url('post.php?post=' . $postid . '&action=edit') . ">#" . $postid . "</a>";
                    break;
                case 'hr_refund_user_name':
                    $user_id = get_post_meta($postid, 'hr_refund_user_details', true);
                    $user = get_userdata($user_id);
                    if (is_object($user)) {
                        echo $user->display_name, ' (';
                        echo $user->user_email . ')';
                    } else {
                        _e('user details not available');
                    }
                    break;
                case 'hr_refund_order_id':
                    $order_id = get_post_meta($postid, 'hr_refund_order_id', true);
                    echo "<a href=" . admin_url('post.php?post=' . $order_id . '&action=edit') . ">#" . $order_id . "</a>";
                    break;
                case 'hr_refund_request_as':
                    echo get_post_meta($postid, 'hr_refund_request_as', true);
                    break;
                case 'hr_refund_request_status':
                    echo hr_refund_get_post_status($postid);
                    break;
                case 'hr_refund_request_type':
                    echo get_post_meta($postid, 'hr_refund_request_type', true);
                    break;
                case 'hr_refund_request_reason':
                    $post = get_post($postid);
                    $message = $post->post_title . '-' . $post->post_content;
                    if (strlen($message) > 80) {
                        echo substr($message, 0, 80);
                        echo '.....';
                    } else {
                        echo $message;
                    }
                    break;
                case 'hr_refund_request_date':
                    $date = get_post_meta($postid, 'hr_refund_request_date', true);
                    echo hr_format_date_time_by_wordpress($date);
                    break;
            }
        }

        /**
         * Searching Functionality
         */
        public static function hr_refund_request_search_fields($where) {
            global $pagenow, $wpdb, $wp;

            if ('edit.php' != $pagenow || !is_search() || !isset($wp->query_vars['s']) || 'hr_refund_request' != $wp->query_vars['post_type'])
                return $where;

            $search_ids = array();
            $terms = explode(',', $wp->query_vars['s']);

            foreach ($terms as $term) {
                $term = $wpdb->esc_like(wc_clean($term));
                $meta_array = array(
                    'hr_refund_order_id',
                    'hr_refund_request_as',
                    'hr_refund_request_type',
                    'hr_refund_user_name',
                    'hr_refund_user_email'
                );
                $implode_array = implode("','", $meta_array);
                if (isset($_GET['post_status']) && $_GET['post_status'] != 'all') {
                    $post_status = $_GET['post_status'];
                } else {
                    $post_status_array = array(
                        'hr-refund-new',
                        'hr-refund-accept',
                        'hr-refund-reject',
                        'hr-refund-processing',
                        'hr-refund-on-hold'
                    );
                    $post_status = implode("','", $post_status_array);
                }

                $search_ids = $wpdb->get_col($wpdb->prepare(
                                "SELECT DISTINCT ID FROM "
                                . "{$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as pm "
                                . "ON p.ID = pm.post_id "
                                . "WHERE (p.post_status IN ('$post_status')) AND (p.ID LIKE %s "
                                . "OR p.post_title LIKE %s "
                                . "OR p.post_content LIKE %s "
                                . "OR (pm.meta_key IN ('$implode_array') "
                                . "AND pm.meta_value LIKE %s))", '%' . $term . '%', '%' . $term . '%', '%' . $term . '%', '%' . $term . '%'));
            }
            $search_ids = array_filter(array_unique(array_map('absint', $search_ids)));
            if (sizeof($search_ids) > 0) {
                $where = str_replace('AND (((', "AND ( ({$wpdb->posts}.ID IN (" . implode(',', $search_ids) . ")) OR ((", $where);
            }

            return $where;
        }

        /**
         * Filter Functionality
         */
        public static function hr_refund_request_order_filters_query($query) {
            global $typenow;
            if (isset($query->query['post_type']) && $query->query['post_type'] == 'hr_refund_request') {
                if ('hr_refund_request' == $typenow) {
                    if (isset($_GET['orderby'])) {
                        $excerpt_array = array('hr_refund_id');
                        if (!in_array($_GET['orderby'], $excerpt_array))
                            $query->query_vars['meta_key'] = $_GET['orderby'];
                    }
                }
            }
        }

        /**
         * Date Filter Functionality
         */
        public static function hr_refund_request_filter_functionality($where, $wp_query) {
            global $wpdb;

            if (isset($wp_query->query['post_type']) && $wp_query->query['post_type'] != 'hr_refund_request')
                return $where;

            if (isset($_REQUEST['filter_action']) && isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'hr_refund_request') {
                $fromdate = isset($_REQUEST['hr_refund_request_fromdate']) ? $_REQUEST['hr_refund_request_fromdate'] : null;
                $todate = isset($_REQUEST['hr_refund_request_todate']) ? $_REQUEST['hr_refund_request_todate'] : null;

                if ($fromdate) {
                    $from_strtotime = strtotime($fromdate);
                    $fromdate = date('Y-m-d', $from_strtotime) . " 00:00:00";
                    $where .= " AND $wpdb->posts.post_date >= '$fromdate'";
                }
                if ($todate) {
                    $to_strtotime = strtotime($todate);
                    $todate = date('Y-m-d', $to_strtotime) . " 23:59:59";
                    $where .= " AND $wpdb->posts.post_date <= '$todate'";
                }
            }
            return $where;
        }

        /**
         * Order By  Functionality
         */
        public static function hr_refund_request_orderby_functionality($order_by, $wp_query) {

            if (isset($wp_query->query['post_type']) && $wp_query->query['post_type'] != 'hr_refund_request')
                return $order_by;

            if (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'hr_refund_request') {
                global $wpdb;
                if (!isset($_REQUEST['order']) && !isset($_REQUEST['orderby'])) {
                    $order = get_user_option('hr_refund_request_asc_desc');
                    if ($order)
                        $order_by = "{$wpdb->posts}.ID " . $order;
                } else {
                    $decimal_column = array(
                        'hr_refund_order_id',
                        'hr_refund_request_date'
                    );
                    if (in_array($_REQUEST['orderby'], $decimal_column)) {
                        $order_by = "CAST({$wpdb->postmeta}.meta_value AS DECIMAL) " . $_REQUEST['order'];
                    }
                }
            }
            return $order_by;
        }

    }

    HR_Refund_Request_Table::init();
}