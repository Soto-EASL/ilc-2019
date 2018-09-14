<?php
if (!defined('ABSPATH')) {
    die();
}
add_action('init', 'ilc_register_cpt_keydates', 0);
add_action('add_meta_boxes', 'ilc_key_dates_mbcb', 1);
add_action('save_post', 'ilc_save_key_dates_mbcb');

function ilc_register_cpt_keydates() {
    //Add custom type
    $labels = array(
        'name' => _x('Key Dates', 'post type general name', 'total'),
        'singular_name' => _x('Key Date', 'post type singular name', 'total'),
        'menu_name' => _x('Key Dates', 'admin menu', 'total'),
        'name_admin_bar' => _x('Key Date', 'add new on admin bar', 'total'),
        'add_new' => _x('Add New', 'Key Date', 'total'),
        'add_new_item' => __('Add New Key Date', 'total'),
        'new_item' => __('New Key Date', 'total'),
        'edit_item' => __('Edit Key Date', 'total'),
        'view_item' => __('View Key Date', 'total'),
        'all_items' => __('All Key Dates', 'total'),
        'search_items' => __('Search Key Dates', 'total'),
        'not_found' => __('No Key Dates found.', 'total'),
        'not_found_in_trash' => __('No Key Dates found in Trash.', 'total')
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'exclude_from_search' => true,
        'query_var' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'author',),
        'register_meta_box_cb' => 'ilc_key_dates_mbcb',
        'rewrite' => false,
            //'rewrite'            => array('slug' => 'treatment', 'with_front' => false)
    );
    register_post_type('key_dates', $args);
}

function ilc_key_dates_mbcb() {
    add_meta_box('ilc_key_dates_metafields', 'Key Dates Information', 'ilc_key_dates_metafields', 'key_dates', 'normal', 'high');
    add_action('admin_enqueue_scripts', 'ilc_key_dates_meta_scripts');
}

function ilc_key_dates_meta_scripts() {
    $curent_screen = get_current_screen();
    if (!isset($curent_screen->post_type) && ( 'key_dates' != $curent_screen->post_type)) {
        return false;
    }
    wp_enqueue_style('jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('ilc-key-dates-mb', get_stylesheet_directory_uri() . '/css/admin/key-dates.css');

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('ilc-key-dates-mb', get_stylesheet_directory_uri() . '/js/admin/key-dates.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'));
}

function ilc_key_dates_metafields($post) {
    $url = get_post_meta($post->ID, 'keydate_url', true);
    $date = get_post_meta($post->ID, 'keydate_date', true);
    $expiry_date = get_post_meta($post->ID, 'keydate_expiry_date', true);

    if ($date) {
        $date = date('Y-m-d', $date);
    }
    if ($expiry_date) {
        $expiry_date = date('Y-m-d', $expiry_date);
    }

    wp_nonce_field('_ilc_keydates_save', '_ilckd_nonce');
    echo '<input type="hidden" name="_ilc_keydates_sub" value="1" />'
    ?>
    <div class="key-date-mb-wrap">
        <div class="key-date-mb-field-wrap kdm-clearfix">
            <div class="key-date-mb-label"><label for="keydate_url">URL</label></div>
            <div class="key-date-mb-field"><input class="widefat" type="text" name="keydate_url" id="keydate_url" value="<?php echo esc_url($url); ?>"/></div>
        </div>
        <div class="key-date-mb-field-wrap kdm-clearfix">
            <div class="key-date-mb-label"><label for="keydate_date">Date</label></div>
            <div class="key-date-mb-field"><input class="ilc-key-date-datepicker" type="text" name="keydate_date" id="keydate_date" value="<?php echo esc_attr($date); ?>"/></div>
        </div>
        <div class="key-date-mb-field-wrap kdm-clearfix">
            <div class="key-date-mb-label"><label for="keydate_expiry_date">Expiry Date</label></div>
            <div class="key-date-mb-field"><input class="ilc-key-date-datepicker" type="text" name="keydate_expiry_date" id="keydate_expiry_date" value="<?php echo esc_attr($expiry_date); ?>"/></div>
        </div>
    </div>
    <?php
}

function ilc_save_key_dates_mbcb($post_id) {
    if (empty($_POST['_ilc_keydates_sub'])) {
        return false;
    }

    //verify nonce sd_day1_track_elements sd_day1_date
    if (empty($_POST['_ilckd_nonce']) || !wp_verify_nonce($_POST['_ilckd_nonce'], '_ilc_keydates_save')) {
        return false;
    }

    if (isset($_POST['keydate_url'])) {
        update_post_meta($post_id, 'keydate_url', $_POST['keydate_url']);
    } else {
        delete_post_meta($post_id, 'keydate_url');
    }
    if (!empty($_POST['keydate_date'])) {
        update_post_meta($post_id, 'keydate_date', strtotime($_POST['keydate_date']));
    } else {
        delete_post_meta($post_id, 'keydate_date');
    }
    if (!empty($_POST['keydate_expiry_date'])) {
        $exprira_date = strtotime($_POST['keydate_expiry_date']) + 86399;
        update_post_meta($post_id, 'keydate_expiry_date', $exprira_date);
    } else {
        delete_post_meta($post_id, 'keydate_expiry_date');
    }
}

function ilc_sc_key_dates($atts, $content = "") {
    extract(shortcode_atts(array(
        'title' => 'KEY DATES',
        'num' => '',
        'hide_expired' => 'no',
        'wrap_class' => '',
        ), $atts));

    $kd_args = array(
        'post_type' => 'key_dates',
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
                'icon' => '',
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