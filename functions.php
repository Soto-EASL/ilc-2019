<?php
require_once get_stylesheet_directory() . '/inc/post-types/post-types.php';
require_once get_stylesheet_directory() . '/inc/customizer.php';
require_once get_stylesheet_directory() . '/inc/total-extend.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/shortcodes.php';

function ilc_theme_setup(){
	load_theme_textdomain('total-child');
	// Register navigation menu
	register_nav_menu( 'sponsors-exhibition', 'Sponsorship & Exhibition Menu.' );
	register_nav_menu( 'sponsors-exhibition-2', 'Sponsorship & Exhibition Menu 2.' );
}
add_action( 'after_setup_theme', 'ilc_theme_setup' );
function total_child_enqueue_parent_theme_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'Total' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', array(), $version );

}
add_action( 'wp_enqueue_scripts', 'total_child_enqueue_parent_theme_style' );
function ilc_remove_parents_action(){
    // Remove logo from navigation row
    remove_action( 'wpex_hook_header_inner', 'wpex_header_logo' );
}

add_action('template_redirect', 'ilc_remove_parents_action');

function ilc_custom_scripts(){
    if(wpex_get_mod('topbar_countdown_enable')){
        wp_enqueue_script('countdown', get_stylesheet_directory_uri() . '/js/jquery.countdown.min.js', array('jquery'), '2.1.0', true);
    }
    wp_enqueue_script('ilc-custom', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), null, true);
	$ssl_scheme = is_ssl() ? 'https' : 'http';
	$fornt_end_data = array(
		'ajaxUrl' => admin_url('admin-ajax.php', $ssl_scheme),
	);
	wp_localize_script( 'ilc-custom', 'ILC', $fornt_end_data );
}
add_action('wp_enqueue_scripts', 'ilc_custom_scripts');
function ilc_admin_custom_scripts() {
	wp_enqueue_style('ilc-admin-common', get_stylesheet_directory_uri() . '/css/admin/common.css');
}
add_action('admin_enqueue_scripts', 'ilc_admin_custom_scripts');

function ilc_template_parts($parts){
    $parts['topbar_countdown'] = 'partials/topbar/topbar-countdown';
    
    return $parts;
}

add_filter('wpex_template_parts', 'ilc_template_parts');

// DVgallery
function ilc_dv_gallery_overrides(){
	if(!function_exists('dvgalleries')){
		return false;
	}
	remove_shortcode('dvgalleries');
	remove_shortcode('dvgallery');
	add_shortcode('dvgalleries', 'ilc_sco_dvgalleries');
	add_shortcode('dvgallery', 'ilc_sco_dvgallery');
}
add_action('after_setup_theme', 'ilc_dv_gallery_overrides', 100);
/**
 * Gallery shortcodes handle
 * @param type $atts
 * @return type
 */
function ilc_sco_dvgalleries($atts){
	extract(shortcode_atts(array(
		"max" => 'max',
        "categoryid" => 'categoryid',
        "vertical" => 'vertical'
	), $atts));
    ob_start();
    include(get_stylesheet_directory() . '/dvgallery/gallery.php');
    return ob_get_clean();;
}

function ilc_sco_dvgallery($atts){
	extract(shortcode_atts(array(
		"id" => 'id',
        "vertical" => 'vertical'
	), $atts));
    ob_start();
    include(get_stylesheet_directory() . '/dvgallery/singlegallery.php');
    return ob_get_clean();;
}
// Handle gallery download request
if(isset($_REQUEST['_ilcdv_gallery_id'])){
	add_action('init', 'ilc_dv_handle_gallery_download');
}
function ilc_dv_handle_gallery_download(){
	if(empty($_REQUEST['_ilcdv_gallery_id']) || empty($_REQUEST['_ilcdv_nonce']) || !wp_verify_nonce($_REQUEST['_ilcdv_nonce'], 'ilc_download_gallery'.$_REQUEST['_ilcdv_gallery_id'])){
		return;
	}
	$galleryimages = get_post_meta( $_REQUEST['_ilcdv_gallery_id'], 'dvgalleryimages', true );
	$images_path = array();
	foreach($galleryimages as $img_id=>$img_url){
		$full_path = get_attached_file($img_id);
		if($full_path){
			$images_path[] = $full_path;
		}
	}
	if(count($images_path)<1){
		return;
	}
	unset($galleryimages);
	unset($full_path);
	unset($img_id);
	unset($img_url);
	// allow a long script run for pulling together lots of images
	@set_time_limit(HOUR_IN_SECONDS);

	// stop/clear any output buffering
	while (ob_get_level()) {
		ob_end_clean();
	}

	// turn off compression on the server
	if (function_exists('apache_setenv'))
		@apache_setenv('no-gzip', 1);
	@ini_set('zlib.output_compression', 'Off');

	if (!class_exists('PclZip')) {
		require ABSPATH . 'wp-admin/includes/class-pclzip.php';
	}

	$filename = tempnam(get_temp_dir(), 'zip');
	$zip = new PclZip($filename);
	$preAddCallback = '__return_true';
	// create the Zip archive, without paths or compression (images are generally already compressed)
	$properties = $zip->create($images_path, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_NO_COMPRESSION, PCLZIP_CB_PRE_ADD, $preAddCallback);
	if (!is_array($properties)) {
		wp_die($zip->errorInfo(true));
	}
	unset($zip);
	
	// send the Zip archive to the browser
	$zipName = sanitize_file_name(strtr(  get_the_title($_REQUEST['_ilcdv_gallery_id']), ',', '-')) . '.zip';

	header('Content-Description: File Transfer');
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename=' . $zipName);
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($filename));

	$chunksize = 512 * 1024;
	$file = @fopen($filename, 'rb');
	while (!feof($file)) {
		echo @fread($file, $chunksize);
		flush();
	}
	fclose($file);

	// check for bug in some old PHP versions, close a second time!
	if (is_resource($file))
		@fclose($file);

	// delete the temporary file
	@unlink($filename);

	exit;
}

/**
 * Make the main menu displayable for the mobile menu
 * Stripping all column alias and classes.
 */
add_filter('wp_nav_menu_objects', 'ilc_nav_menu_objs', 11, 2);
function ilc_nav_menu_objs($sorted_menu_items, $args){
    if(empty($args->theme_location)){
        return $sorted_menu_items;
    }
    
    $current_col = $cols_parent = $hide_parent = false;
    foreach($sorted_menu_items as $k=>$item){
        if(!empty($hide_parent) && in_array($item->menu_item_parent, $hide_parent)){
            $hide_parent[] = $item->ID;
            unset($sorted_menu_items[$k]);
        }
        if(is_array($item->classes) && in_array('ilc-hide-menu-item', $item->classes)){
            $hide_parent[] = $item->ID;
            unset($sorted_menu_items[$k]);
        }
        if('mobile_menu_alt' == $args->theme_location){
            if(!empty($current_col) && ($item->menu_item_parent == $current_col)){
                $item->menu_item_parent = $cols_parent;
            }
            if(is_array($item->classes) && in_array('megamenu', $item->classes)){
                $sorted_menu_items[$k]->classes = array_diff($item->classes, array('megamenu', 'col-1', 'col-2', 'col-3', 'col-4'));
            }
            if(!is_array($item->classes) || !in_array('ilc-hide-link', $item->classes)){
                continue;
            }
            $cols_parent = $item->menu_item_parent;
            $current_col = $item->ID;
            unset($sorted_menu_items[$k]);
        }
        
    }
    if($current_col){
        $sorted_menu_items = array_values($sorted_menu_items);
    }
    return $sorted_menu_items;
}

// Hide link text
add_filter('walker_nav_menu_start_el', 'ilc_walker_nav_menu_start_el', 11, 4);
function ilc_walker_nav_menu_start_el($item_output, $item, $depth, $args){
    if(is_array($item->classes) && in_array('ilc-hide-link', $item->classes)){
        return '';
    }
    return $item_output;
}
add_shortcode('soto_year', 'soto_sc_year');
function soto_sc_year() {
    $year = date('Y');
    return $year;
}

function ilc_page_content_top(){
	global $wp_query;
	if( !is_page() ){
		return;
	}
	$page_id = $wp_query->get_queried_object_id();
	if(4931 == $page_id){
		?> 
		<script type='text/javascript'>
			var axel = Math.random()+"";
			var a = axel * 10000000000000;
			document.write('<img src="https://pubads.g.doubleclick.net/activity;xsp=224439;ord=1;num='+ a +'?" width=1 height=1 border=0/>');
		</script>
		<?php
	}
}
add_action('wpex_hook_content_top', 'ilc_page_content_top');

remove_action( 'wpex_outer_wrap_before', 'wpex_skip_to_content_link' );


function ilc_page_heder_class($classes){
	if(!wpex_page_header_subheading_content()){
		return $classes;
	}
	$classes[] = 'ilc-page-subheading';
	return $classes;
}
add_filter('wpex_page_header_classes', 'ilc_page_heder_class');

function ilc_sticky_footer(){
	$enabled = wpex_get_mod('enable_footer_sticky_msg');
	$sticky_message = wpex_get_mod('footer_stikcy_message');
	$closed_for_IP = ilc_footer_message_is_closed();
	//if(isset($_GET['ilc_sfsm'])){
	//	$closed_for_IP = false;
	//}
	if(!$enabled || !$sticky_message || $closed_for_IP){
		return '';
	}
	$template = locate_template('partials/footer/stikcy-message.php');
	if(!$template){
		return '';
	}
	include $template;
}

function ilc_footer_message_is_closed(){
	$ips = get_option('ilc_footer_message_closed_ip');
	if(!is_array( $ips )){
		return false;
	}
	$current_ip = ilc_get_visitorIP();
	if(!$current_ip){
		return false;
	}
	if(in_array($current_ip, $ips)){
		return true;
	}
	return false;
}

function ilc_footer_message_save_closed(){
	$ips = get_option('ilc_footer_message_closed_ip');
	if(!is_array( $ips )){
		$ips = array();
	}
	$current_ip = ilc_get_visitorIP();
	if(!$current_ip){
		return false;
	}
	if(in_array($current_ip, $ips)){
		return true;
	}
	$ips[] = $current_ip;
	update_option('ilc_footer_message_closed_ip', $ips);
	return true;
}

function ilc_get_visitorIP(){
	$ip = '';
	if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
            $ip = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ip[0]);
        } else {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    else {
        $ip =  $_SERVER['REMOTE_ADDR'];
    }
	if(filter_var($ip, FILTER_VALIDATE_IP)){
		return $ip;
	}
	return false;
}

add_action('wp_ajax_ilc_save_closed_footer_message', 'ilc_save_closed_footer_message');
add_action('wp_ajax_nopriv_ilc_save_closed_footer_message', 'ilc_save_closed_footer_message');

function ilc_save_closed_footer_message(){
	$result = ilc_footer_message_save_closed();
	if($result){
		wp_send_json(array('status' => 'OK'));
	}
	wp_send_json(array('status' => 'NO'));
}

add_filter( 'wpb_widget_title', 'easl_override_widget_title', 10, 2 );
function easl_override_widget_title( $output = '', $params = array( '' ) ) {
	$extraclass = ( isset( $params['extraclass'] ) ) ? " " . $params['extraclass'] : "";

	return '<h1 class="entry-title' . $extraclass . '">' . $params['title'] . '</h1>';
}