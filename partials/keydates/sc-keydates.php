<?php
/**
 * Available varibles
 * $title
 * $wrap_class
 * $kd_query
 * $hide_expired = yes/no
 */
?>
<div class="listing-key-dates <?php echo $wrap_class; ?>">
    <?php if ($title) { ?>
        <h2 class="kdl-title"><?php echo $title; ?></h2>
    <?php } ?>
    <div class="key-dates">
        <ul>
            <?php
            while ($kd_query->have_posts()) {
                $kd_query->the_post();
                $url = get_post_meta(get_the_ID(), 'keydate_url', true);
                $date = get_post_meta(get_the_ID(), 'keydate_date', true);
                $expiry_date = get_post_meta(get_the_ID(), 'keydate_expiry_date', true);

                if ($date) {
                    $date = date('d.m.Y', $date);
                }
                if ($expiry_date) {
                    $expiry_date = date('d.m.Y', $expiry_date);
                }
                ?>
                <li class="key-date <?php if ('no' == $hide_expired) {echo 'key-date-expired';} ?>">
                    <?php if ($url) { ?><a title="" href="<?php echo esc_url($url); ?>"><?php } ?>
                        <span><?php echo $date; ?></span><br/><?php the_title(); ?>
                <?php if ($url) { ?></a><?php } ?>
                </li>

                <?php
            }
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</div>

