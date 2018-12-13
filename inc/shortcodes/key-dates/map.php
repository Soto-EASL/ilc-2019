<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'Key Dates', 'total-child' ),
	'base'                    => 'ilc_key_dates',
	'icon'                    => 'vcex-icon-box vcex-icon fa fa-calendar',
	'is_container'            => false,
	'show_settings_on_create' => false,
	'category'                => __( 'ILC', 'total-child' ),
	'description'             => __( 'Display key dates', 'total-child' ),
	'php_class_name'          => 'ILC_VC_Key_Dates',
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Widget Title', 'total' ),
			'param_name'  => 'widget_title',
			'admin_label' => true,
		),
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'js_composer' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'total-child' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Hide Expired', 'total-child' ),
			'param_name' => 'hide_expired',
			'group'      => __( 'Query', 'total-child' ),
		),
		array(
			'type'               => 'autocomplete',
			'heading'            => __( 'Include Categories', 'total-child' ),
			'param_name'         => 'include_categories',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label'        => true,
			'settings'           => array(
				'multiple'       => true,
				'min_length'     => 1,
				'groups'         => false,
				'unique_values'  => true,
				'display_inline' => true,
				'delay'          => 0,
				'auto_focus'     => true,
			),
			'group'              => __( 'Query', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Number of Key Dates to Show', 'total-child' ),
			'param_name'  => 'limit',
			'value'       => '',
			'description' => __( 'Enter the limit of key dates to show. Leave empty to show all.', 'total-child' ),
			'group'       => __( 'Query', 'total' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order', 'total-child' ),
			'param_name' => 'order',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'Default', 'total-child' ) => '',
				__( 'DESC', 'total-child' )    => 'DESC',
				__( 'ASC', 'total-child' )     => 'ASC',
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order By', 'total-child' ),
			'param_name' => 'orderby',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'Default', 'total-child' )    => '',
				__( 'Start Date', 'total-child' ) => 'start_date',
				__( 'Title', 'total-child' )      => 'title',
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Display Type', 'total-child' ),
			'param_name' => 'display_type',
			'group'      => __( 'View', 'total-child' ),
			'value'      => array(
				__( 'Default', 'total-child' )    => '',
				__( 'Table', 'total-child' ) => 'table',
				__( 'List', 'total-child' )   => 'list',
				__( 'Message Box', 'total-child' )      => 'message_box',
			),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Display bottom content', 'total-child' ),
			'param_name' => 'diaplay_bototm_content',
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'       => 'textarea_html',
			'value'      => '',
			'heading'    => __( 'Content', 'total-child' ),
			'param_name' => 'content',
			'group'      => __( 'View', 'total-child' ),
			'dependency' => array(
				'element' => 'diaplay_bototm_content',
				'value'   => array(  'true' ),
			),
		),
	),
);
