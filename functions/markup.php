<?php
/**
 * Genesis Starter Theme
 *
 * This file adds extra functions used in the Genesis Starter theme.
 *
 * @package   SEOThemes\Library
 * @link      https://github.com/seothemes/seothemes-library
 * @author    SEO Themes
 * @copyright Copyright © 2017 SEO Themes
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {

	die;

}

add_filter( 'genesis_markup_title-area_close', 'child_theme_after_title_area', 10, 2 );
/**
 * Appends HTML to the closing markup for .title-area.
 *
 * Adding something between the title + description and widget area used to require
 * re-building genesis_do_header(). However, since the title-area closing markup
 * now goes through genesis_markup(), it means we now have some extra filters
 * to play with. This function makes use of this and adds in an extra hook
 * after the title-area used for displaying the primary navigation menu.
 *
 * @since  0.1.0
 *
 * @param  string $close_html HTML tag being processed by the API.
 * @param  array  $args       Array with markup arguments.
 *
 * @return string
 */
function child_theme_after_title_area( $close_html, $args ) {

	if ( $close_html ) {

		ob_start();

		do_action( 'genesis_after_title_area' );

		$close_html = $close_html . ob_get_clean();

	}

	return $close_html;

}

add_action( 'init', 'child_theme_structural_wrap_hooks' );
/**
 * Add hooks immediately before and after Genesis structural wraps.
 *
 * @since   1.0.0
 *
 * @version 1.1.0
 * @author  Tim Jensen
 * @link    https://timjensen.us/add-hooks-before-genesis-structural-wraps
 *
 * @return void
 */
function child_theme_structural_wrap_hooks() {

	$wraps = get_theme_support( 'genesis-structural-wraps' );

	foreach ( $wraps[0] as $context ) {

		/**
		 * Inserts an action hook before the opening div and after the closing div
		 * for each of the structural wraps.
		 *
		 * @param string $output   HTML for opening or closing the structural wrap.
		 * @param string $original Either 'open' or 'close'.
		 *
		 * @return string
		 */
		add_filter( "genesis_structural_wrap-{$context}", function ( $output, $original ) use ( $context ) {

			$position = ( 'open' === $original ) ? 'before' : 'after';

			ob_start();

			do_action( "genesis_{$position}_{$context}_wrap" );

			if ( 'open' === $original ) {

				return ob_get_clean() . $output;

			} else {

				return $output . ob_get_clean();

			}

		}, 10, 2 );

	}

}
