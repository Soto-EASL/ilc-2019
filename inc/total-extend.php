<?php

function ilc_extended_meta($array, $post){
	
	if( 'templates/sponsorship-exhibition.php' == get_page_template_slug($post) ) {
		$array['ilc-sponsors-exhibition'] = array(
			'title'     => esc_html__( 'Sponsorship & Exhibition Settings', 'total' ),
			'post_type' => array( 'page' ),
			'settings'  => array(
				'enable_introduction_text' => array(
					'title'       => esc_html__( 'Enable Introduction Text', 'total' ),
					'id'          => 'ilc_spex_enable_intro_text',
					'type'        => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options'     => array(
						''   => esc_html__( 'Disable', 'total' ),
						'on' => esc_html__( 'Enable', 'total' ),
					),
				),
				'introduction_text'        => array(
					'title'       => esc_html__( 'Introduction Text', 'total' ),
					'id'          => 'ilc_spex_intro_text',
					'type'        => 'editor',
					'rows'        => '5',
					'description' => esc_html__( 'Add introduction text.', 'total' ),
				),
			),
		);
	}
	if('post' == get_post_type($post)){
		$array['media']['settings']['ilc_display_featured_image'] = array(
			'title' => esc_html__( "Display Featured Image on Single Page", 'total' ),
			'id' =>  'ilc_display_featured_image',
			'type' => 'select',
			'description' => esc_html__( 'Hide/Display featured image on single post page.', 'total' ),
			'options' => array(
				'' => esc_html__( 'Yes', 'total' ),
				'no' => esc_html__( 'No', 'total' ),
			),
		);
	}
	return $array;
}
add_filter('wpex_metabox_array', 'ilc_extended_meta', 10, 2);