<?php

if ( class_exists( 'IP_Geo_Block_API' ) ) :

/**
 * Class for CloudFlare
 *
 * @see https://support.cloudflare.com/hc/en-us/articles/200170986-How-does-CloudFlare-handle-HTTP-Request-headers-
 * @see https://support.cloudflare.com/hc/en-us/articles/200170856-How-do-I-restore-original-visitor-IP-with-vBulletin-
 * @see https://support.cloudflare.com/hc/en-us/articles/200168236-What-does-CloudFlare-IP-Geolocation-do-
 * @see https://support.cloudflare.com/hc/en-us/articles/205072537-What-are-the-two-letter-country-codes-for-the-Access-Rules-
 */
class IP_Geo_Block_API_CloudFlare extends IP_Geo_Block_API {

    public function get_location( $ip, $args = array() ) {
        return array(
            'countryCode' => isset( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ? $_SERVER['HTTP_CF_IPCOUNTRY'] : 'ZZ',
        );
    }

    public function get_attribution() {
        return NULL;
    }

    public function download( &$db, $args ) {
        return FALSE;
    }

    public function add_settings_field( $field, $section, $option_slug, $option_name, $options, $callback, $str_path, $str_last ) {
    }

    static public function replace_ip( $ip ) {
        return isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Register API
 *
 */
IP_Geo_Block_Provider::register_addon( array(
    'CloudFlare' => array(
        'key'  => NULL,
        'type' => 'IPv4, IPv6',
        'link' => '<a href="https://support.cloudflare.com/hc/en-us/articles/200168236-What-does-CloudFlare-IP-Geolocation-do-" title="What does CloudFlare IP Geolocation do? &ndash; Cloudflare Support" rel=noreferrer target=_blank>What does CloudFlare IP Geolocation do?</a>&nbsp;(IPv4, IPv6)',
    ),
) );

/**
 * Restore original visitor IP
 *
 */
add_filter( 'ip-geo-block-ip-addr', array( 'IP_Geo_Block_API_CloudFlare', 'replace_ip' ) );

endif;
?>