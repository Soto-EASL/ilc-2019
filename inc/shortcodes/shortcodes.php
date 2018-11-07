<?php
if (!defined('ABSPATH')) die('-1');

function ilc_map_vc_shortcodes() {
    require_once get_theme_file_path('inc/shortcodes/sitemap/sitemap.php');
}
add_action('vc_after_mapping', 'ilc_map_vc_shortcodes');