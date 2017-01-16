<?php
/**
 * Custom functions that are not template related
 *
 * @package Wellington
 */

if ( ! function_exists( 'wellington_default_menu' ) ) :
	/**
	 * Display default page as navigation if no custom menu was set
	 */
	function wellington_default_menu() {

		echo '<ul id="menu-main-navigation" class="main-navigation-menu menu">' . wp_list_pages( 'title_li=&echo=0' ) . '</ul>';

	}
endif;


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wellington_body_classes( $classes ) {

	// Get theme options from database.
	$theme_options = wellington_theme_options();

	// Switch sidebar layout to left.
	if ( 'left-sidebar' == $theme_options['layout'] ) {
		$classes[] = 'sidebar-left';
	}

	// Add post columns classes.
	if ( 'two-columns' == $theme_options['post_layout'] ) {
		$classes[] = 'post-layout-two-columns post-layout-columns';
	} elseif ( 'three-columns' == $theme_options['post_layout'] ) {
		$classes[] = 'post-layout-three-columns post-layout-columns';
	} else {
		$classes[] = 'post-layout-one-column';
	}

	return $classes;
}
add_filter( 'body_class', 'wellington_body_classes' );


/**
 * Hide Elements with CSS.
 *
 * @return void
 */
function wellington_hide_elements() {

	// Get theme options from database.
	$theme_options = wellington_theme_options();

	$elements = array();

	// Hide Site Title?
	if ( false === $theme_options['site_title'] ) {
		$elements[] = '.site-title';
	}

	// Hide Site Description?
	if ( false === $theme_options['site_description'] ) {
		$elements[] = '.site-description';
	}

	// Return early if no elements are hidden.
	if ( empty( $elements ) ) {
		return;
	}

	// Create CSS.
	$classes = implode( ', ', $elements );
	$custom_css = $classes . ' {
	position: absolute;
	clip: rect(1px, 1px, 1px, 1px);
}';

	// Add Custom CSS.
	wp_add_inline_style( 'wellington-stylesheet', $custom_css );
}
add_filter( 'wp_enqueue_scripts', 'wellington_hide_elements', 11 );


/**
 * Change excerpt length for default posts
 *
 * @param int $length Length of excerpt in number of words.
 * @return int
 */
function wellington_excerpt_length( $length ) {

	// Get theme options from database.
	$theme_options = wellington_theme_options();

	// Return excerpt text.
	if ( isset( $theme_options['excerpt_length'] ) and $theme_options['excerpt_length'] >= 0 ) :
		return absint( $theme_options['excerpt_length'] );
	else :
		return 30; // Number of words.
	endif;
}
add_filter( 'excerpt_length', 'wellington_excerpt_length' );


/**
 * Function to change excerpt length for posts in category posts widgets
 *
 * @param int $length Length of excerpt in number of words.
 * @return int
 */
function wellington_magazine_posts_excerpt_length( $length ) {
	return 12;
}


/**
 * Change excerpt more text for posts
 *
 * @param String $more_text Excerpt More Text.
 * @return string
 */
function wellington_excerpt_more( $more_text ) {

	return '';

}
add_filter( 'excerpt_more', 'wellington_excerpt_more' );


/**
 * Set wrapper start for wooCommerce
 */
function wellington_wrapper_start() {
	echo '<section id="primary" class="content-area">';
	echo '<main id="main" class="site-main" role="main">';
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_before_main_content', 'wellington_wrapper_start', 10 );


/**
 * Set wrapper end for wooCommerce
 */
function wellington_wrapper_end() {
	echo '</main><!-- #main -->';
	echo '</section><!-- #primary -->';
}
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_after_main_content', 'wellington_wrapper_end', 10 );