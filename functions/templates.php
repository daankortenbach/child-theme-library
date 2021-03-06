<?php
/**
 * Genesis Starter Theme
 *
 * This file adds extra functions used in the Genesis Starter theme.
 *
 * @package   SEOThemes\Core
 * @link      https://github.com/seothemes/seothemes-library
 * @author    SEO Themes
 * @copyright Copyright © 2017 SEO Themes
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {

	die;

}

add_filter( 'theme_page_templates', 'child_theme_add_page_templates' );
/**
 * Add page templates.
 *
 * Removes default Genesis templates then loads library templates defined in
 * the child theme's config file.
 *
 * @since  1.0.0
 *
 * @param  array $page_templates The existing page templates.
 *
 * @throws \Exception If no sub-config found.
 *
 * @return array
 */
function child_theme_add_page_templates( $page_templates ) {

	$child_theme_templates = child_theme_get_config( 'page-templates' );

	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );

	$page_templates = array_merge( $page_templates, $child_theme_templates );

	return $page_templates;

}

add_filter( 'template_include', 'child_theme_set_page_template' );
/**
 * Modify page based on selected page template.
 *
 * @since  1.0.0
 *
 * @param  string $template The path to the template being included.
 *
 * @throws \Exception If no sub-config found.
 *
 * @return string
 */
function child_theme_set_page_template( $template ) {

	$page_templates = child_theme_get_config( 'page-templates' );

	if ( ! is_singular( 'page' ) ) {

		return $template;

	}

	$current_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

	if ( ! array_key_exists( $current_template, $page_templates ) ) {

		return $template;

	}

	$template_override = CHILD_THEME_DIR . '/templates/' . $current_template;

	if ( file_exists( $template_override ) ) {

		$template = $template_override;

	} else {

		$template_path = trailingslashit( CHILD_THEME_VIEWS ) . $current_template;

		if ( file_exists( $template_path ) ) {

			$template = $template_path;

		}
	}

	return $template;

}
