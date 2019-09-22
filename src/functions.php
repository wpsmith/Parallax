<?php

namespace WPS\WP\Parallax;

/**
 * Gets background open tag, 2 <div> tags.
 *
 * @param string $id Background ID.
 * @param string $color_or_image Whether 'color' or 'image'.
 * @param array $data_args Array of data args for parallax window.
 *
 * @return string
 */
function get_bg_open( $id, $color_or_image, $data_args = array() ) {
	$data_args = wp_parse_args( $data_args, array(
		'speed'     => '0.1',
		'parallax'  => 'scroll',
		'position'  => '0px 0px',
		'image-src' => '',
	) );

	$id             = str_replace( '-', '_', sanitize_title_with_dashes( $id ) );
	$color_or_image = 'image' === $color_or_image ? 'image' : 'color';

	$background_setting = get_theme_mod( 'wps_parallax_setting_' . $id . '_' . $color_or_image );

	if ( 'color' === $color_or_image ) {
		return "<div class='parallax-window' style='background-color:$background_setting'><div class='wrap'>";
	}

	$attributes             = '';
	$data_args['image-src'] = $background_setting;
	foreach ( $data_args as $key => $value ) {
		$attributes .= " data-$key='$value'";
	}

	return sprintf( "<div id=\"$id\" class=\"fullwidth parallax-widget-areas parallax-window\" %s><div class=\"wrap\">", $attributes );

}

/**
 * Gets background close tags, 2 </div> tags.
 *
 * @return string
 */
function get_bg_image_close() {

	return '</div></div>';

}