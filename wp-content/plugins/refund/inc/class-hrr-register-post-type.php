<?php

/**
 * Admin Custom Post Type.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Register_Post_Type')) {

    /**
     * HR_Refund_Register_Post_Type Class.
     */
    class HR_Refund_Register_Post_Type {

        /**
         * HR_Refund_Register_Post_Type Class initialization.
         */
        public static function init() {
            add_action('init', array(__CLASS__, 'hr_refund_register_post_types'), 5);
        }

        /**
         * Register All Custom Post types
         */
        public static function hr_refund_register_post_types() {
            $array = array(
                'hr_refund_request' => array('HR_Refund_Register_Post_Type', 'hr_refund_post_type_args'),
            );
            $array = apply_filters('hr_refund_add_custom_post_type', $array);

            if (hr_check_is_array($array)) {
                foreach ($array as $post_name => $args_function) {
                    $args = call_user_func_array($args_function, array());
                    if (!post_type_exists($post_name))
                        register_post_type($post_name, $args);
                }
            }
        }

        /**
         * Get refund request post type args
         */
        public static function hr_refund_post_type_args() {
            $args = apply_filters('hr_refund_post_type_args', array(
                'labels' => array(
                    'name' => __('Refund Requests', HR_REFUND_LOCALE),
                    'singular_name' => __('Refund Requests', HR_REFUND_LOCALE),
                    'menu_name' => _x('Refund Requests', 'admin menu', HR_REFUND_LOCALE),
                    'add_new' => __('Add New Refund Request', HR_REFUND_LOCALE),
                    'add_new_item' => __('Add New Refund Request', HR_REFUND_LOCALE),
                    'edit' => __('Edit Refund Request', HR_REFUND_LOCALE),
                    'edit_item' => __('View Refund Request', HR_REFUND_LOCALE),
                    'new_item' => __('New Refund Request', HR_REFUND_LOCALE),
                    'view' => __('View Refund Request', HR_REFUND_LOCALE),
                    'view_item' => __('View Refund Request', HR_REFUND_LOCALE),
                    'search_items' => __('Search Refund Request', HR_REFUND_LOCALE),
                    'not_found' => __('No Refund Request found', HR_REFUND_LOCALE),
                    'not_found_in_trash' => __('No Refund Request found in trash', HR_REFUND_LOCALE),
                ),
                'description' => __('Here you can able to see list of Refund Requests', HR_REFUND_LOCALE),
                'public' => false,
                'show_ui' => true,
                'capability_type' => 'post',
                'show_in_menu' => 'hr_refund',
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
                'show_in_nav_menus' => false,
                'capabilities' => array(
                    'publish_posts' => 'publish_posts',
                    'edit_posts' => 'edit_posts',
                    'edit_others_posts' => 'edit_others_posts',
                    'delete_posts' => 'delete_posts',
                    'delete_others_posts' => 'delete_others_posts',
                    'read_private_posts' => 'read_private_posts',
                    'edit_post' => 'edit_post',
                    'delete_post' => 'delete_post',
                    'read_post' => 'read_post',
                    'create_posts' => 'do_not_allow',
                ),
                'map_meta_cap' => true,
                    )
            );
            return $args;
        }

    }

    HR_Refund_Register_Post_Type::init();
}