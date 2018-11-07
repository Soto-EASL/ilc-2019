<?php
/**
 * Template Name: Sponsorship & Exhibition - Horizontal
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

				<article id="single-blocks" class="single-page-article wpex-clr">
					<?php 
					$intro_enabled = get_post_meta( get_the_ID(), 'ilc_spex_enable_intro_text', true);
					$intro_text = get_post_meta( get_the_ID(), 'ilc_spex_intro_text', true);
					if('on' == $intro_enabled):
					?> 
					<div class="spex-introduction">
						<?php echo do_shortcode($intro_text); ?>
					</div>
					<?php endif; ?>
					<div class="ilc-spex-section">
						<div class="ilc-spex-menu-container-hz">
							<div class="spex-menu-mobile-tray">
								<span class="spex-menu-mobile-trigger"></span>
								<p class="spex-menu-mobile-text"></p>
							</div>
							<nav class="ilc-spex-menu-hz">
								<?php 
								wp_nav_menu(
									array(
										'theme_location' => 'sponsors-exhibition-2',
										'depth'			 => 1,
										'container'		 => '',
										'menu_class'	 => 'menu-spex-hz',
										'link_before'	 => '',
										'link_after'	 => '',
										'fallback_cb'	 => '',
									)
								);
								?>
							</nav>
						</div>
						<div class="ilc-spex-content-container-hz">
							<?php get_template_part( 'partials/page-single-content' ); ?>
						</div>
					</div>
				</article>

				<?php endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>