<?php
/**
 * Plugin Name: WooCommerce Address Autocomplete (Australia)
 * Description: Adds Google Places Autocomplete to WooCommerce checkout fields, biased to Australian addresses first.
 * Author: OnyxWM
 * Plugin URI: https://github.com/OnyxWM/woocommerce-address-autocomplete-au
 * Version: 1.1.2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_filter('woocommerce_get_settings_pages', 'bo_wc_autocomplete_add_settings_page');

function bo_wc_autocomplete_add_settings_page($pages)
{
    if (class_exists('WC_Settings_Page')) {
        require_once dirname(__FILE__) . '/class-woocommerce-address-autocomplete-settings.php';
        $pages[] = new BO_WC_Autocomplete_Settings();
    }
    return $pages;
}

add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_checkout') || !is_checkout()) {
        return;
    }

    $api_key = get_option('bo_wc_autocomplete_api_key', '');
    if (empty($api_key)) {
        return;
    }

    wp_enqueue_script(
        'google-maps-places',
        'https://maps.googleapis.com/maps/api/js?key=' .
        esc_attr($api_key) .
        '&libraries=places&region=AU',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'bo-woocommerce-autocomplete',
        plugin_dir_url(__FILE__) . 'woocommerce-autocomplete.js',
        ['google-maps-places'],
        '1.1.4',
        true
    );
});