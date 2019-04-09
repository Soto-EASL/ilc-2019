<?php
//topbar_countdown_start
$count_down_start = wpex_get_mod('topbar_countdown_start');
$count_down_end = wpex_get_mod('topbar_countdown_end');
$count_down_time_zone_offset = wpex_get_mod('topbar_countdown_timezone');
$enable_daynum = wpex_get_mod('topbar_countdown_daynum');
$enable_endc = wpex_get_mod('topbar_countdown_enable_endc');
$enable_next_cd = wpex_get_mod('topbar_countdown_enable_next_cd');


$count_down_start_nc = wpex_get_mod('topbar_countdown_start_nc');
$count_down_time_zone_offset_nc = wpex_get_mod('topbar_countdown_timezone_nc');

$countdoun_dftz = date_default_timezone_get();
date_default_timezone_set('UTC');
$countdown_timestamp_now = time();
$countdown_timestamp_start = strtotime($count_down_start . ' ' . $count_down_time_zone_offset);
$countdown_timestamp_end = strtotime($count_down_end . ' ' . $count_down_time_zone_offset);

$countdown_timestamp_start_nc = strtotime($count_down_start_nc . ' ' . $count_down_time_zone_offset_nc);

date_default_timezone_set($countdoun_dftz);

$started = $countdown_timestamp_now - $countdown_timestamp_start;
$ended = $countdown_timestamp_now - $countdown_timestamp_end;

$started_nc =  $countdown_timestamp_now - $countdown_timestamp_start_nc;

if($started < 0){
?>
    <div class="top-bar-col top-bar-col-countdown countdown-hide">
        <div class="ilc-countdown" data-until="<?php echo esc_attr($count_down_start); ?>" data-zoneoffset="<?php echo esc_attr($count_down_time_zone_offset); ?>">

        </div>
    </div>
<?php
}elseif ($ended < 0) {
	//Event Started, so display the day number until it is ended
	$event_day_number = intval($started/86400, 10) + 1;
	if($enable_daynum):
	?>
    <div class="top-bar-col top-bar-col-countdown">
        <div class="ilc-countdown-text">DAY<br/><?php echo $event_day_number; ?></div>
    </div>
    <?php endif; ?>
	<?php
}elseif($enable_next_cd && $started_nc < 0) {
		?>
        <div class="top-bar-col top-bar-col-countdown countdown-hide">
            <div class="ilc-countdown" data-until="<?php echo esc_attr($count_down_start_nc); ?>" data-zoneoffset="<?php echo esc_attr($count_down_time_zone_offset_nc); ?>">

            </div>
        </div>
	<?php
}
?>