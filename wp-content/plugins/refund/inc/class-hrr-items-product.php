<?php

/**
 * Order Items Product
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Items_Product')) {

    /**
     * HR_Refund_Items_Product Class.
     */
    class HR_Refund_Items_Product {

        /**
         * Get Product Image.
         * @param  Array $product
         * @return string $image
         */
        public static function get_product_image($product) {
            $productid = $product['product_id'];
            $imageurl = "";
            if ((get_post_thumbnail_id($product['variation_id']) != "") || (get_post_thumbnail_id($product['variation_id']) != 0)) {
                $image_urls = wp_get_attachment_image_src(get_post_thumbnail_id($product['variation_id']));
                $imageurl = $image_urls[0];
            }
            if ($imageurl == "") {
                if ((get_post_thumbnail_id($productid) != "") || (get_post_thumbnail_id($productid) != 0)) {
                    $image_urls = wp_get_attachment_image_src(get_post_thumbnail_id($productid));
                    $imageurl = $image_urls[0];
                } else {
                    $imageurl = esc_url(wc_placeholder_img_src());
                }
            }
            $image = '<img src="' . $imageurl . '" alt="' . get_the_title($productid) . '" height="90" width="90" />';
            return $image;
        }

        /**
         * Get Product Name.
         * @param  Array $product
         * @return string $product_name
         */
        public static function get_product_name($product) {
            $product_name = '';
            $post = get_post($product['product_id']);
            if (is_object($post)) {
                $product_name = $post->post_title;
                if (isset($product['variation_id']) && (!empty($product['variation_id'])))
                    $product_name = $product_name . '<br />' . self::get_formatted_variation($product);
            }
            return $product_name;
        }

        /**
         * Get the formatted Attribute variations.
         * @param  Array Variations.
         * @return String
         */
        public static function get_formatted_variation($variations) {
            $product_id = $variations['product_id'];
            $product = hr_get_product($variations['variation_id']);
            $formatted_attributes = '';
            $attributes = explode(',', wc_get_formatted_variation($product, true));
            if (hr_check_is_array($attributes)) {
                foreach ($attributes as $each_attribute) {
                    $explode_data = explode(':', $each_attribute);
                    if (isset($explode_data[0]) && isset($explode_data[1])) {
                        $formatted_attributes .= wc_attribute_label($explode_data[0], $product) . ':' . $explode_data[1] . '<br />';
                    }
                }
            }
            return $formatted_attributes;
        }

    }

}