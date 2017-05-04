<?php

if ( class_exists( 'IP_Geo_Block_API' ) ) :

/**
 * Class for CloudFront
 *
 * @see http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/georestrictions.html
 * @see http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/RequestAndResponseBehaviorCustomOrigin.html#RequestCustomIPAddresses
 * @see http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/RequestAndResponseBehaviorCustomOrigin.html#request-custom-headers-behavior
 * @see http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/header-caching.html#header-caching-web-location
 */
class IP_Geo_Block_API_CloudFront extends IP_Geo_Block_API {

    public function get_location( $ip, $args = array() ) {
        return array(
            'countryCode' => isset( $_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'] ) ? $_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'] : 'ZZ',
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
        if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $pos = strrchr( $_SERVER['HTTP_X_FORWARDED_FOR'], ',' );
            return substr( $_SERVER['HTTP_X_FORWARDED_FOR'], FALSE !== $pos ? $pos + 1 : 0 );
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}

/**
 * Register API
 *
 */
IP_Geo_Block_Provider::register_addon( array(
    'CloudFront' => array(
        'key'  => NULL,
        'type' => 'IPv4, IPv6',
        'link' => '<a href="http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/header-caching.html#header-caching-web-location" title="Configuring CloudFront to Cache Objects Based on Request Headers - Amazon CloudFront" rel=noreferrer target=_blank>Configuring CloudFront to Cache Objects Based on the Location of the Viewer</a>&nbsp;(IPv4, IPv6)',
    ),
) );

/**
 * Restore original visitor IP
 *
 */
add_filter( 'ip-geo-block-ip-addr', array( 'IP_Geo_Block_API_CloudFront', 'replace_ip' ) );

endif;
?>