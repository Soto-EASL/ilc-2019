<?php
//topbar_countdown_start
$count_down_start = wpex_get_mod('topbar_countdown_start');
$count_down_end = wpex_get_mod('topbar_countdown_end');
$count_down_time_zone_offset = wpex_get_mod('topbar_countdown_timezone');
$enable_daynum = wpex_get_mod('topbar_countdown_daynum');
$enable_endc = wpex_get_mod('topbar_countdown_enable_endc');
$end_content = wpex_get_mod('topbar_countdown_end_content');

$countdoun_dftz = date_default_timezone_get();
date_default_timezone_set('UTC');
$countdown_timestamp_now = time();
$countdown_timestamp_start = strtotime($count_down_start . ' ' . $count_down_time_zone_offset);
$countdown_timestamp_end = strtotime($count_down_end . ' ' . $count_down_time_zone_offset);
date_default_timezone_set($countdoun_dftz);

$started = $countdown_timestamp_now - $countdown_timestamp_start;
$ended = $countdown_timestamp_now - $countdown_timestamp_end;

if($started < 0){
?> 
<div class="ilc-countdown" data-until="<?php echo esc_attr($count_down_start); ?>" data-zoneoffset="<?php echo esc_attr($count_down_time_zone_offset); ?>">
    
</div>

    
<?php
}
?>