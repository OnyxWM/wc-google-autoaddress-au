<?php
if (!defined('ABSPATH')) {
    exit;
}

class OXWM_WC_Autocomplete_Settings extends WC_Settings_Page
{
    public function __construct()
    {
        $this->id    = 'oxwm_wc_autocomplete';
        $this->label = __('Address Autocomplete', 'oxwm_wc_autocomplete');
        parent::__construct();
    }

    /**
     * Render settings fields
     */
    public function get_settings()
    {
        $settings = [
            [
                'title' => __('Google Address Autocomplete', 'oxwm_wc_autocomplete'),
                'type'  => 'title',
                'id'    => 'oxwm_wc_autocomplete_title',
                'desc'  => __(
                    'Configure Google Maps address autocomplete for WooCommerce checkout. Use a valid API key with Places API enabled.',
                    'oxwm_wc_autocomplete'
                ),
            ],
            [
                'title'    => __('Google Maps API Key', 'oxwm_wc_autocomplete'),
                'id'       => 'oxwm_wc_autocomplete_api_key',
                'type'     => 'text',
                'css'      => 'min-width:300px;',
                'default'  => '',
                'desc'     => __('Your Google Maps JavaScript API key.', 'oxwm_wc_autocomplete'),
                'desc_tip' => true,
            ],
            [
                'type' => 'sectionend',
                'id'   => 'oxwm_wc_autocomplete_title',
            ],
        ];

        return apply_filters('oxwm_wc_autocomplete_settings', $settings);
    }

    /**
     * Save our settings when the form is submitted.
     */
    public function save()
    {
        WC_Admin_Settings::save_fields($this->get_settings());
    }
}