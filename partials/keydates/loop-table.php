<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$url        = get_post_meta( get_the_ID(), 'keydate_url', true );
$start_date = get_post_meta( get_the_ID(), 'keydate_date', true );
$start_time = get_post_meta( get_the_ID(), 'keydate_start_time', true );
$end_date   = get_post_meta( get_the_ID(), 'keydate_expiry_date', true );
$end_time   = get_post_meta( get_the_ID(), 'keydate_end_time', true );
$alt_title  = get_post_meta( get_the_ID(), 'keydate_alt_title', true );
$alt_title  = trim( $alt_title );

$now_time = time();

$status = 'upcoming';

if ( ( $start_date <= $now_time ) && ( $end_date > $now_time ) ) {
	$status = 'running';
}
if ( ( $start_date < $now_time ) && ( $end_date <= $now_time ) ) {
	$status = 'expired';
}

$dates = array();
if ( $start_date ) {
	$start_date = date( 'd F Y', $start_date );
	if ( $start_time ) {
		$start_date .= ' (' . $start_time . ')';
	}
	$dates[] = $start_date;
}
if ( $end_date ) {
	$end_date = date( 'd F Y', $end_date );
	if ( $end_time ) {
		$end_date .= ' (' . $end_time . ')';
	}
	$dates[] = $end_date;
}

$title     = '';
$alt_title = wp_kses( $alt_title, array(
	'a' => array('href' => array(), 'title' => array(), 'target' => array(), 'style' => array(), 'class' => array(), 'rel' => array()),
	'span' => array('style' => array(), 'class' => array()),
	'strong' => array('style' => array(), 'class' => array()),
	'em' => array('style' => array(), 'class' => array()),
) );
if ( $alt_title ) {
	$title = $alt_title;
} elseif ( $url ) {
	$title = '<a href="' . esc_url( $url ) . '">' . $title . get_the_title() . '</a>';
}else{
	$title .= get_the_title();
}

if($title):
?>
<tr class="ilc-key-date-<?php echo $status; ?>">
    <td class="ilc-key-date-date-col">
		<?php echo implode( ' - ', $dates ); ?>
    </td>
    <td class="ilc-key-date-title-col"><?php echo $title; ?></td>
</tr>
<?php endif; ?>