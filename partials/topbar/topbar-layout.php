<?php
/**
 * Topbar layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Classes
$classes = 'clr';
if ( wpex_get_mod( 'top_bar_sticky' ) ) {
	$classes .= ' wpex-top-bar-sticky';
}
if ( $visibility = wpex_get_mod( 'top_bar_visibility' ) ) {
	$classes .= ' '. $visibility;
}
if ( 'full-width' == wpex_global_obj( 'main_layout' ) && wpex_get_mod( 'top_bar_fullwidth' ) ) {
	$classes .= ' wpex-full-width';
} 

$registration_enabled = wpex_get_mod('topbar_registration_enable');
$abstract_enabled = wpex_get_mod('topbar_abstract_enable');
?>

<?php wpex_hook_topbar_before(); ?>

	<div id="top-bar-wrap" class="<?php echo esc_attr( $classes ); ?>">

		<div id="top-bar" class="clr container">

                    <div class="top-bar-table">
                        <div class="top-bar-row">
                            <div class="top-bar-col top-bar-col-logo">
                                <?php wpex_header_logo(); ?>
                            </div>
                            
                            <?php if($registration_enabled || $abstract_enabled){?>
                            <div class="top-bar-col top-bar-col-regilink">
								<?php if($registration_enabled): ?>
                                <a class="theme-button" title="" href="<?php echo esc_url(trim(wpex_get_mod('topbar_registrationt_link'))); ?>" <?php if(wpex_get_mod('topbar_registration_newtab')){ echo ' target="_blank" '; } ?> >
                                    <span class="theme-button-inner"><?php  echo wpex_get_mod('topbar_registrationt_title'); ?></span>
                                </a>
								<?php endif; ?>
								<?php if($abstract_enabled): ?>
                                <a class="theme-button" title="" href="<?php echo esc_url(trim(wpex_get_mod('topbar_abstract_link'))); ?>" <?php if(wpex_get_mod('topbar_abstract_newtab')){ echo ' target="_blank" '; } ?> >
                                    <span class="theme-button-inner"><?php  echo wpex_get_mod('topbar_abstruct_title'); ?></span>
                                </a>
								<?php endif; ?>
                            </div>
                            <?php } ?>
                            
                            <?php if(wpex_get_mod('topbar_countdown_enable')){
	                            wpex_get_template_part('topbar_countdown');
                            } ?>
                        </div>
                    </div>

		</div><!-- #top-bar -->

	</div><!-- #top-bar-wrap -->

<?php wpex_hook_topbar_after(); ?>