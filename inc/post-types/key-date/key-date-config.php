<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// Include helper functions first so we can use them in the class and other key dates related functions
require_once get_theme_file_path( 'inc/post-types/key-date/key-date-helper.php' );

class ILC_Key_Date_Config {
	protected static $slugs = array(
		'type'     => 'key_date',
		'category' => 'key_date_category',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		// Adds the portfolio post type
		add_action( 'init', array( 'ILC_Key_Date_Config', 'register_post_type' ), 0 );
		// Register event topics
		add_action( 'init', array( 'ILC_Key_Date_Config', 'register_category' ), 0 );
		if ( is_admin() ) {
			add_filter( 'wpex_main_metaboxes_post_types', array( 'ILC_Key_Date_Config', 'add_total_metabox_support' ) );
			// Add meta fields for key date
			add_filter( 'wpex_metabox_array', array( 'ILC_Key_Date_Config', 'meta_array' ), 100, 2 );
		}
	}

	/**
	 * Get post type slug
	 * @return string
	 */
	public static function get_type_slug() {
		return self::$slugs['type'];
	}
	/**
	 * Get category slug
	 * @return string
	 */
	public static function get_category_slug() {
		return self::$slugs['category'];
	}

	/**
	 * Register post type.
	 *
	 * @since 2.0.0
	 */
	public static function register_post_type() {
		register_post_type( self::get_type_slug(), array(
			'labels'              => array(
				'name'               => __( 'Key Date', 'total-child' ),
				'singular_name'      => __( 'Key Date', 'total-child' ),
				'add_new'            => __( 'Add New', 'total-child' ),
				'add_new_item'       => __( 'Add New Key Date', 'total-child' ),
				'edit_item'          => __( 'Edit Key Date', 'total-child' ),
				'new_item'           => __( 'Add New Key Date', 'total-child' ),
				'view_item'          => __( 'View Key Date', 'total-child' ),
				'search_items'       => __( 'Search Key Dates', 'total-child' ),
				'not_found'          => __( 'No Key Dates Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Key Dates Found In Trash', 'total-child' )
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'query_var'           => false,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array(
				'title',
				'author',
			),
			'rewrite'             => false,
		) );
	}

	/**
	 * Register Key Date Category.
	 *
	 * @since 2.0.0
	 */
	public static function register_category() {
		$args = array(
			'labels' => array(
				'name' => __( 'Category', 'total' ),
				'singular_name' => __( 'Category', 'total' ),
				'menu_name' => __( 'Category', 'total' ),
				'search_items' => __( 'Search','total' ),
				'popular_items' => __( 'Popular', 'total' ),
				'all_items' => __( 'All', 'total' ),
				'parent_item' => __( 'Parent', 'total' ),
				'parent_item_colon' => __( 'Parent', 'total' ),
				'edit_item' => __( 'Edit', 'total' ),
				'update_item' => __( 'Update', 'total' ),
				'add_new_item' => __( 'Add New', 'total' ),
				'new_item_name' => __( 'New', 'total' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
				'add_or_remove_items' => __( 'Add or remove', 'total' ),
				'choose_from_most_used' => __( 'Choose from the most used', 'total' ),
			),
			'public' => false,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_admin_column' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => false,
			'query_var' => false,
		);

		register_taxonomy( self::get_category_slug(), array( self::get_type_slug() ), $args );
	}

	public static function add_total_metabox_support( $post_types ) {
		$post_types[ self::get_type_slug() ] = self::get_type_slug();

		return $post_types;
	}

	/**
	 *
	 * @param type $array
	 * @param type $post
	 *
	 * @return type
	 */
	public static function meta_array( $array, $post ) {
		if ( self::get_type_slug() != get_post_type( $post ) ) {
			return $array;
		}
		$prefix = 'keydate_';
		$array = array();
		$array['key_date_data'] = array(
			'title'    => esc_html__( 'Key Date Data', 'total-child' ),
			'settings' => array(
				'url'         => array(
					'title'       => esc_html__( 'URL', 'total-child' ),
					'id'          => $prefix . 'url',
					'type'        => 'text',
					'description' => esc_html__( 'Enter the url of this key date.', 'total-child' ),
				),
				'start_date'        => array(
					'title'       => esc_html__( 'Start Date', 'total-child' ),
					'id'          => $prefix . 'date',
					'type'        => 'date',
					'description' => esc_html__( 'Enter the date of the key date.', 'total-child' ),
				),
				'start_time'         => array(
					'title'       => esc_html__( 'Start Time', 'total-child' ),
					'id'          => $prefix . 'start_time',
					'type'        => 'text',
					'description' => esc_html__( 'Enter the start time of this key date. If filled this will be displayed with End Time, eg. 04 February 2019 (10:00CET)', 'total-child' ),
				),
				'expiry_date' => array(
					'title'       => esc_html__( 'End Date', 'total-child' ),
					'id'          => $prefix . 'expiry_date',
					'type'        => 'date',
					'description' => esc_html__( 'Enter the end date of the key date.', 'total-child' ),
				),
				'end_time'         => array(
					'title'       => esc_html__( 'End Time', 'total-child' ),
					'id'          => $prefix . 'end_time',
					'type'        => 'text',
					'description' => esc_html__( 'Enter the end time of this key date. If filled this will be displayed with End Time, eg. 13 February 2019 (23:59CET)', 'total-child' ),
				),
				'alt_title'         => array(
					'title'       => esc_html__( 'Alternative Title', 'total-child' ),
					'id'          => $prefix . 'alt_title',
					'type'        => 'editor',
					'tiny'         => true,
					'row'          => 5,
					'media_buttons'=> false,
					'description' => esc_html__( 'Enter alternative title. Useful to add custom links to a word or few words. Leave empty to use main title. Allowed tags: a, span, em, strong.', 'total-child' ),
				),

			)
		);

		return $array;
	}
}

new ILC_Key_Date_Config();