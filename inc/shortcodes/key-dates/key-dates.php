<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class ILC_VC_Key_Dates extends WPBakeryShortCode {
	/**
	 * Get array for comma sepaarted values
	 * @param $value
	 *
	 * @return array
	 */
	public function string_to_array( $value ) {

		// Return if value is empty
		if ( !$value ) {
			return array();
		}

		// Return if already an array
		if ( is_array( $value ) ) {
			return $value;
		}

		// Define output array
		$array = array();

		// Clean up value
		$items = preg_split( '/\,[\s]*/', $value );

		// Create array
		foreach ( $items as $item ) {
			if ( strlen( $item ) > 0 ) {
				$array[] = $item;
			}
		}

		// Return array
		return $array;
	}
	/**
	 * Suggest Key Date Categories for autocomplete
	 */
	public static function suggest_key_date_categories( $search_string ) {
		$categories = array();
		$get_terms  = get_terms(
			ILC_Key_Date_Config::get_category_slug(),
			array(
				'hide_empty' => false,
				'search'     => $search_string,
			) );
		if ( $get_terms ) {
			foreach ( $get_terms as $term ) {
				if ( $term->parent ) {
					$parent = get_term( $term->parent, ILC_Key_Date_Config::get_category_slug() );
					$label  = $term->name . ' (' . $parent->name . ')';
				} else {
					$label = $term->name;
				}
				$categories[] = array(
					'label' => $label,
					'value' => $term->term_id,
				);
			}
		}

		return $categories;
	}

	/**
	 * Renders Key Dates Categories for autocomplete
	 *
	 * @since 2.1.0
	 */
	function render_key_date_categories( $data ) {
		$value = $data['value'];
		$term  = get_term_by( 'term_id', intval( $value ), ILC_Key_Date_Config::get_category_slug() );
		if ( is_object( $term ) ) {
			if ( $term->parent ) {
				$parent = get_term( $term->parent, ILC_Key_Date_Config::get_category_slug() );
				$label  = $term->name . ' (' . $parent->name . ')';
			} else {
				$label = $term->name;
			}

			return array(
				'label' => $label,
				'value' => $value,
			);
		}

		return $data;
	}
}


if ( is_admin() ) {
	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_ilc_key_dates_include_categories_callback', array(
		'ILC_VC_Key_Dates',
		'suggest_key_date_categories'
	), 10, 1 );
	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_ilc_key_dates_include_categories_render', array(
		'ILC_VC_Key_Dates',
		'render_key_date_categories'
	), 10, 1 );
};


vc_lean_map( 'ilc_key_dates', null, get_theme_file_path( 'inc/shortcodes/key-dates/map.php' ) );
