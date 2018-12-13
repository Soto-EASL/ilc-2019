<?php
if (!defined('ABSPATH')) {
    die();
}

function ilc_sc_key_dates($atts, $content = "") {
    extract(shortcode_atts(array(
        'title' => 'KEY DATES',
        'num' => '',
        'hide_expired' => 'no',
        'wrap_class' => '',
        ), $atts));

    $kd_args = array(
        'post_type' => 'key_date',
        'posts_per_page' => -1,
        'meta_key' => 'keydate_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );

    if ($num) {
        $kd_args['posts_per_page'] = $num;
    }

    if ('yes' == $hide_expired) {
        $kd_args['meta_query'] = array(
            'key' => 'keydate_expiry_date',
            'value' => time(),
            'compare' => '>=',
            'type' => 'NUMERIC',
        );
    }

    $kd_query = new WP_Query($kd_args);
    if (!$kd_query->have_posts()) {
        return '';
    }
    // Get key date template
    $template = locate_template('partials/keydates/sc-keydates.php');
    if($template){
        ob_start();
        include $template;
        return trim(ob_get_clean());
    }
}

add_shortcode('ilc_key_dates', "ilc_sc_key_dates");

if (defined('WPB_VC_VERSION')) {
    vc_map(
            array(
                'base' => 'ilc_key_dates',
                'name' => __('Key Dates', 'total'),
                'class' => 'ilc-key-dates',
                'icon' => 'vcex-icon-box vcex-icon fa fa-calendar',
                'category' => __('ILC', 'total'),
                'description' => '',
                'show_settings_on_create' => true,
                'params' => array(
                    array(
                        'param_name' => 'title',
                        'heading' => __('Title', 'total'),
                        'type' => 'textfield',
                        'holder' => 'div',
                        'value' => __('KEY DATES', 'total')),
                    array(
                        'param_name' => 'num',
                        'heading' => __('Number of key dates', 'total'),
                        'type' => 'textfield',
                        'holder' => 'div',
                        'value' => -1),
                    array(
                        'param_name' => 'hide_expired',
                        'heading' => __('Hide Expired', 'total'),
                        'type' => 'checkbox',
                        'holder' => 'div',
                        'value' => array('Yes' => 'yes'),
                    ),
                    array(
                        'param_name' => 'wrap_class',
                        'heading' => __('Class', 'total'),
                        'description' => __('(Optional) Enter a unique class name.', 'total'),
                        'type' => 'textfield',
                        'holder' => 'div'
                    )
                )
            )
    );
}