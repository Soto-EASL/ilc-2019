<?php
global $paged;
$page_link = get_the_permalink();
if ( get_query_var('paged') ) { 
    $paged = get_query_var('paged'); 
}
elseif ( get_query_var('page') ) { 
    $paged = get_query_var('page'); 
}
else { 
    $paged = 1; 
}
if (empty($categoryid)) {
    $galleryargs = array(
        'post_type' => 'dvgalleries',
        'posts_per_page' => $max,
        'paged' => $paged
    );
}
else {
    if ( function_exists('icl_object_id') ) {
        $catid_array = (int)$categoryid;
    }
    else {
        $catid_array = explode(',', $categoryid);
    }
    $galleryargs = array(
        'post_type' => 'dvgalleries',
        'posts_per_page' => $max,
        'tax_query' => array(
            array(
                'taxonomy' => 'dvgallerytaxonomy',
                'terms'    => $catid_array,
            ),
        ),
        'paged' => $paged
    );
}
$gallery_query = new WP_Query( $galleryargs );
$lgzoom = get_option('dvgallery_lgzoom');
$lgfullscreen = get_option('dvgallery_lgfullscreen');
$lgthumbnails = get_option('dvgallery_lgthumbnails');
$lgdownload = get_option('dvgallery_lgdownload');
$lgcounter = get_option('dvgallery_lgcounter');
$lghide = get_option('dvgallery_lghide');
// Gallery navigation
if ( $gallery_query->max_num_pages > 1 ) : 
?>
        <div class="dv-blogpager">    
            <div class="dv-previous">
                <?php next_posts_link( esc_attr__( '&#8249; Older images', 'dvgallery' ), $gallery_query->max_num_pages ); ?>
            </div>
            <div class="dv-next">
                <?php previous_posts_link( esc_attr__( 'Newer images &#8250;', 'dvgallery' ), $gallery_query->max_num_pages ); ?>
            </div>
        </div>
        <?php 
endif;
?>
        <?php while($gallery_query->have_posts()) : $gallery_query->the_post(); ?>
        <?php $looprandom = rand(); ?>
        <?php $gallerytext = get_post_meta( get_the_id(), 'dvgallerytext', true ); ?>
        <?php $gallerytype = get_post_meta( get_the_id(), 'dvgallerytype', true ); ?>
        <?php $galleryimages = get_post_meta( get_the_id(), 'dvgalleryimages', true ); ?>
        <?php $galleryvideos = get_post_meta( get_the_id(), 'dvgalleryvideos', true ); ?>
        <?php $externallink = get_post_meta( get_the_id(), 'dvexternallink', true ); ?>
        <?php $newindow = get_post_meta( get_the_id(), 'dvblank', true ); ?>
        <?php $galleryautoplay = get_post_meta( get_the_id(), 'dvactivateauto', true ); ?>
        <?php $galleryautoplayduration = get_post_meta( get_the_id(), 'dvautoplaypause', true ); ?>
		<?php
		$gallery_download_link = add_query_arg(array(
			'_ilcdv_nonce' => wp_create_nonce('ilc_download_gallery'.get_the_id()),
			'_ilcdv_gallery_id' => get_the_id(),
		), $page_link);
		$gallery_download_data_attr = 'data-ilcdvnonce="'. wp_create_nonce('ilc_download_gallery'.get_the_id()) .'"';
		$gallery_download_data_attr .= ' data-ilcdvgid="'. get_the_id() .'"';
		?>
        <div id="dv-gallery<?php echo esc_attr($looprandom); ?><?php the_ID(); ?>" class="dv-gallerycontainer">
            <?php if ( has_post_thumbnail() ) { ?>
                <?php 
$thumb_id = get_post_thumbnail_id();
$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'large', true);
$thumb_url = $thumb_url_array[0];
                ?>
                <div class="dv-galleryimage <?php if($vertical == 'yes') { echo esc_attr('vertical'); } ?>" data-image="<?php echo esc_url($thumb_url); ?>">
                    <a class="<?php if ($gallerytype != 'link') { echo esc_attr('openlightbox'); } ?> <?php if ($gallerytype == 'video') { echo esc_attr('videogal'); } ?> <?php if ($gallerytype == 'link') { echo esc_attr('linkgal'); } ?> <?php if($vertical == 'yes') { echo esc_attr('vertical'); } ?>" href="<?php if ($gallerytype == 'link') { echo esc_url($externallink); } else {echo esc_attr('#');} ?>" <?php if ($newindow == 'on') { echo 'target="_blank"'; } ?>></a>
                </div>
                <?php } ?>
            <div class="dv-gallerycontent <?php if ( !has_post_thumbnail() ) { echo esc_attr('withoutfimage'); } ?>  <?php if($vertical == 'yes') { echo esc_attr('vertical'); } ?>">
                <div class="dv-gallerycontent-inner">
                <div class="dvh4"><a href="<?php if ($gallerytype == 'link') { echo esc_url($externallink); } else {echo esc_attr('#');} ?>" class="<?php if ($gallerytype != 'link') { echo esc_attr('openlightbox'); } ?>" <?php if ($newindow == 'on') { echo 'target="_blank"'; } ?>><?php the_title(); ?></a></div>
                <?php if(!empty ($gallerytext)) { ?>
                <?php echo apply_filters('the_content',$gallerytext); ?>
                <?php } ?>
                <a href="<?php if ($gallerytype == 'link') { echo esc_url($externallink); } else {echo esc_attr('#');} ?>" class="<?php if ($gallerytype != 'link') { echo esc_attr('openlightbox'); } ?> dv-readmore-button" <?php if ($newindow == 'on') { echo 'target="_blank"'; } ?>><?php esc_attr_e( 'View Gallery', 'dvgallery' ); ?></a>
				<a class="dv-readmore-button ilclg-download-album" href="<?php echo $gallery_download_link; ?>"><?php esc_attr_e( 'Download Gallery', 'dvgallery' ); ?></a>
                </div>
            </div>
            </div>
<?php if (($gallerytype != 'video') && (!empty($galleryimages))) { ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        jQuery('#dv-gallery<?php echo esc_js($looprandom); ?><?php the_ID(); ?> .openlightbox').click(function (e) {
            e.preventDefault();
            jQuery(this).lightGallery({
                dynamic: true,
                    preload: 2,
                    zoom: <?php if (!empty($lgzoom)) { echo esc_js($lgzoom); } else { echo esc_js('true'); } ?>,
                    fullScreen: <?php if (!empty($lgfullscreen)) { echo esc_js($lgfullscreen); } else { echo esc_js('true'); } ?>,
                    thumbnail: <?php if (!empty($lgthumbnails)) { echo esc_js($lgthumbnails); } else { echo esc_js('true'); } ?>,
                    download: <?php if (!empty($lgdownload)) { echo esc_js($lgdownload); } else { echo esc_js('true'); } ?>,
                    counter: <?php if (!empty($lgcounter)) { echo esc_js($lgcounter); } else { echo esc_js('true'); } ?>,
                    hideBarsDelay: <?php if (!empty($lghide)) { echo esc_js($lghide); } else { echo esc_js('6'); } ?>000,
                <?php if ($galleryautoplay == 'on') { ?>
                autoplay: true,
                pause: <?php if(!empty($galleryautoplayduration)) { echo esc_js($galleryautoplayduration); } else { echo esc_js('4'); } ?>000,
                <?php } ?>
                dynamicEl: [
                    <?php foreach ($galleryimages as $image => $link) { ?> 
                    <?php $fullimage = wp_get_attachment_image_src( $image, 'full' ); ?>
                    <?php $large = wp_get_attachment_image_src( $image, 'large' ); ?>
                    <?php $medium = wp_get_attachment_image_src( $image, 'medium' ); ?>
                    <?php $thumb = wp_get_attachment_image_src( $image, 'thumbnail' ); ?>
                    <?php $attachment = get_post($image); ?>
					<?php $title = $attachment->post_title; ?>
					<?php 
					$alt = get_post_meta($image, '_wp_attachment_image_alt', true); 
					if(empty($alt)){
						$alt = trim(strip_tags( $attachment->post_excerpt ));
					}
					if(empty($alt)){
						$alt = trim(strip_tags( $title ));
					}
					?>
                    {
                        "src": "<?php echo esc_js($fullimage['0']); ?>",
                        "thumb": "<?php echo esc_js($thumb['0']); ?>",
                        "subHtml": "<?php echo esc_js($attachment->post_excerpt); ?>",
						"alt": "<?php echo esc_js($alt); ?>",
						"title": "<?php echo esc_js($title); ?>",
                        //"responsive": "<?php echo $medium[0]; ?> 480, <?php echo $large[0]; ?> 1024"
                    },
                    <?php } ?>                    
                ]
            });
			
			jQuery(this).on('onAferAppendSlide.lg', function(ev, o){
				var dvcore = $(this).data('lightGallery'),
					dlIcons = '<a class="lg-icon ilclg-download-album" href="<?php echo $gallery_download_link; ?>">Download full gallery</a',
					slideImg = dvcore.$slide.eq(o).find(".lg-object"),
					slideProps = dvcore.s.dynamicEl[o];
				slideImg.attr('alt', slideProps.alt).attr('title', slideProps.title);
				if(typeof dvcore.$outer.data('adiadded') != "undefined"){
					return false;
				}
				dvcore.$outer.data('adiadded', 1)
				dvcore.$outer.find('.lg-toolbar').append(dlIcons);
			});
        });
        });
</script> 
        <?php } if (($gallerytype == 'video') && (!empty($galleryvideos))) { ?>
        <script type="text/javascript">
        jQuery(document).ready(function ($) {
            jQuery('#dv-gallery<?php echo esc_js($looprandom); ?><?php the_ID(); ?> .openlightbox').click(function (e) {
                e.preventDefault();
                jQuery(this).lightGallery({
                    dynamic: true,
                    zoom: false,
                    fullScreen: false,
                    autoplay: false,
                    autoplayControls: false,
                    thumbnail: false,
                    download: <?php if (!empty($lgdownload)) { echo esc_js($lgdownload); } else { echo esc_js('true'); } ?>,
                    counter: <?php if (!empty($lgcounter)) { echo esc_js($lgcounter); } else { echo esc_js('true'); } ?>,
                    hideBarsDelay: <?php if (!empty($lghide)) { echo esc_js($lghide); } else { echo esc_js('6'); } ?>000,
                    dynamicEl: [
                        <?php foreach ($galleryvideos as $video => $link) { ?> 
                        <?php if (isset($link['dvvideourl'])) { $videourl = esc_js($link['dvvideourl']); } ?>
                        <?php if (isset($link['dvvideotitle'])) { $videotitle = esc_js($link['dvvideotitle']); } ?>
                        {
                            "src": "<?php if (isset($link['dvvideourl'])) { echo esc_js($videourl); } ?>",
                            "subHtml": "<?php if (isset($link['dvvideotitle'])) { echo esc_js($videotitle); } ?>",
                        },
                        <?php } ?>
                    ]
                });
            })
        });
    </script>
<?php } ?>       
<?php endwhile; ?> 
<?php
if ( $gallery_query->max_num_pages > 1 ) : 
        ?>
        <div class="dv-blogpager">    
            <div class="dv-previous">
                <?php next_posts_link( esc_attr__( '&#8249; Older images', 'dvgallery' ), $gallery_query->max_num_pages ); ?>
            </div>
            <div class="dv-next">
                <?php previous_posts_link( esc_attr__( 'Newer images &#8250;', 'dvgallery' ), $gallery_query->max_num_pages ); ?>
            </div>
        </div>
        <?php 
endif;
wp_reset_postdata();
        ?>