<?php

// Add settings for 
function ilc_customizer_sections($sections) {
    // Add registration link settings
    $sections['wpex_topbar_registration'] = array(
        'title' => esc_html__('Registration Link', 'total'),
        'panel' => 'wpex_topbar',
        'settings' => array(
            array(
                'id' => 'topbar_registration_enable',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Enable', 'total'),
                    'type' => 'checkbox',
                ),
            ),
            array(
                'id' => 'topbar_registrationt_title',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('Button Text', 'total'),
                    'type' => 'text',
                ),
            ),
            array(
                'id' => 'topbar_registrationt_link',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('Button Url', 'total'),
                    'type' => 'text',
                ),
            ),
            array(
                'id' => 'topbar_registration_newtab',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Open in New Tab', 'total'),
                    'type' => 'checkbox',
                ),
            ),
        ),
    );
    
    // Add Submit Abstract link settings
    $sections['wpex_topbar_abstract'] = array(
        'title' => esc_html__('Submit Abstract Link', 'total'),
        'panel' => 'wpex_topbar',
        'settings' => array(
            array(
                'id' => 'topbar_abstract_enable',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Enable', 'total'),
                    'type' => 'checkbox',
                ),
            ),
            array(
                'id' => 'topbar_abstruct_title',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('Button Text', 'total'),
                    'type' => 'text',
                ),
            ),
            array(
                'id' => 'topbar_abstract_link',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('Button Url', 'total'),
                    'type' => 'text',
                ),
            ),
            array(
                'id' => 'topbar_abstract_newtab',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Open in New Tab', 'total'),
                    'type' => 'checkbox',
                ),
            ),
        ),
    );
    
    // Add countdown settings
    $sections['wpex_topbar_countdown'] = array(
        'title' => esc_html__('Countdown', 'total'),
        'panel' => 'wpex_topbar',
        'settings' => array(
            array(
                'id' => 'topbar_countdown_enable',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Enable', 'total'),
                    'type' => 'checkbox',
                ),
            ),
            array(
                'id' => 'topbar_countdown_start',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('Start', 'total'),
                    'type' => 'text',
                    'description' => 'Format must be <strong>YYYY-MM-DD HH:MM:SS</strong>, for example 2014-02-25 10:00:00',
                ),
            ),
            array(
                'id' => 'topbar_countdown_end',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('End', 'total'),
                    'type' => 'text',
                    'description' => 'Format must be <strong>YYYY-MM-DD HH:MM:SS</strong>, for example 2014-02-25 10:00:00',
                ),
            ),
            array(
                'id' => 'topbar_countdown_timezone',
                'default' => '',
                'control' => array(
                    'label' => esc_html__('Time Zone Offset', 'total'),
                    'type' => 'text',
                    'description' => esc_html__('Set the time zone offset from GMT. For example +2 for Barcelona'),
                ),
            ),
            array(
                'id' => 'topbar_countdown_daynum',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Display day number', 'total'),
                    'type' => 'checkbox',
                ),
            ),
            array(
                'id' => 'topbar_countdown_enable_next_cd',
                'default' => '1',
                'control' => array(
                    'label' => esc_html__('Display next congress countdown', 'total'),
                    'type' => 'checkbox',
                ),
            ),
	        array(
		        'id' => 'topbar_countdown_start_nc',
		        'default' => '',
		        'control' => array(
			        'label' => esc_html__('Next Congress Start', 'total'),
			        'type' => 'text',
			        'description' => 'Format must be <strong>YYYY-MM-DD HH:MM:SS</strong>, for example 2014-02-25 10:00:00',
		        ),
	        ),
	        array(
		        'id' => 'topbar_countdown_timezone_nc',
		        'default' => '',
		        'control' => array(
			        'label' => esc_html__('Next Congress Time Zone Offset', 'total'),
			        'type' => 'text',
			        'description' => esc_html__('Set the time zone offset from GMT. For example +2 for Barcelona'),
		        ),
	        ),
        ),
    );
    
	// Extra footer fields
	$sections['wpex_footer_bottom']['settings'][] = array(
		'id' => 'enable_footer_sticky_msg',
		'default' => '1',
		'control' => array(
			'label' => esc_html__('Display footer sticky message', 'total'),
			'type' => 'checkbox',
		),
	);
	$sections['wpex_footer_bottom']['settings'][] = array(
		'id' => 'footer_stikcy_message',
		'transport' => 'partialRefresh',
		'default' => '',
		'control' => array(
			'label' => __( 'Footer Stikcy Message', 'total' ),
			'type' => 'wpex-textarea',
			'rows' => 7,
		),
	);
    return $sections;
}

add_filter('wpex_customizer_sections', 'ilc_customizer_sections');
