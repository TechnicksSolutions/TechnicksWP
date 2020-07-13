<?php
// Register menus
register_nav_menus(
	array(
		'main-nav'		=> __( 'The Main Menu', 'jointswp' ),		// Main nav in header
        'secondary-nav'		=> __( 'The Secondary Menu', 'jointswp' ),		// Main nav in header
		'offcanvas-nav'	=> __( 'The Off-Canvas Menu (Mobile)', 'jointswp' ),	// Off-Canvas nav
        'footer-nav-1'	=> __( 'Footer Menu 1', 'jointswp' ),			// Secondary nav in footer
		'footer-nav-2'	=> __( 'Footer Menu 2', 'jointswp' ),			// Secondary nav in footer
        'category-nav'	=> __( 'Category Menu', 'jointswp' ),			// Secondary nav in footer
        'links-nav'	=> __( 'Links Menu', 'jointswp' ),			// Secondary nav in footer
	)
);

// The Top Menu
function joints_top_nav() {
	wp_nav_menu(array(
		'container'			=> false,						// Remove nav container
		'menu_id'			=> 'main-nav',					// Adding custom nav id
		'menu_class'		=> 'medium-horizontal menu',	// Adding custom nav class
		'items_wrap'		=> '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown">%3$s</ul>',
		'theme_location'	=> 'main-nav',					// Where it's located in the theme
		'depth'				=> 5,							// Limit the depth of the nav
		'fallback_cb'		=> false,						// Fallback function (see below)
		'walker'			=> new Topbar_Menu_Walker()
	));
}

// The Secondary Menu
function joints_secondary_nav() {
    wp_nav_menu(array(
        'container'			=> false,						// Remove nav container
        'menu_id'			=> 'secondary-nav',					// Adding custom nav id
        'menu_class'		=> 'medium-horizontal menu',	// Adding custom nav class
        'items_wrap'		=> '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown">%3$s</ul>',
        'theme_location'	=> 'secondary-nav',					// Where it's located in the theme
        'depth'				=> 5,							// Limit the depth of the nav
        'fallback_cb'		=> false,						// Fallback function (see below)
        'walker'			=> new Topbar_Menu_Walker()
    ));
}

// Big thanks to Brett Mason (https://github.com/brettsmason) for the awesome walker
class Topbar_Menu_Walker extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = Array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"menu\">\n";
	}
}

// The Off Canvas Menu
function joints_off_canvas_nav() {
	wp_nav_menu(array(
		'container'			=> false,							// Remove nav container
		'menu_id'			=> 'offcanvas-nav',					// Adding custom nav id
		'menu_class'		=> 'vertical menu accordion-menu',	// Adding custom nav class
		'items_wrap'		=> '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
		'theme_location'	=> 'offcanvas-nav',					// Where it's located in the theme
		'depth'				=> 5,								// Limit the depth of the nav
		'fallback_cb'		=> false,							// Fallback function (see below)
		'walker'			=> new Off_Canvas_Menu_Walker()
	));
}

class Off_Canvas_Menu_Walker extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = Array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"vertical menu\">\n";
	}
}

// The Footer Menu 1
function joints_footer_nav_1_menu() {
	wp_nav_menu(array(
		'container'			=> 'false',				// Remove nav container
		'menu_id'			=> 'footer-nav-1',		// Adding custom nav id
		'menu_class'		=> 'menu',				// Adding custom nav class
		'theme_location'	=> 'footer-nav-1',		// Where it's located in the theme
		'depth'				=> 0,					// Limit the depth of the nav
		'fallback_cb'		=> '',					// Fallback function
        'walker'			=> new Off_Canvas_Menu_Walker()
	));
} /* End The Footer Menu 1 */

// The The Footer Menu 2
function joints_footer_nav_2_menu() {
    wp_nav_menu(array(
        'container'			=> 'false',				// Remove nav container
        'menu_id'			=> 'footer-nav-2',		// Adding custom nav id
        'menu_class'		=> 'menu',				// Adding custom nav class
        'theme_location'	=> 'footer-nav-2',		// Where it's located in the theme
        'depth'				=> 0,					// Limit the depth of the nav
        'fallback_cb'		=> '',					// Fallback function
        'walker'			=> new Off_Canvas_Menu_Walker()
    ));
} /* End The Footer Menu 2 */

// The Product Category Menu
function joints_product_category_menu() {
	wp_nav_menu(array(
		'container'			=> 'false',				// Remove nav container
		'menu_id'			=> 'category-nav',		// Adding custom nav id
		'menu_class'		=> 'menu',				// Adding custom nav class
		'theme_location'	=> 'category-nav',		// Where it's located in the theme
		'depth'				=> 0,					// Limit the depth of the nav
		'fallback_cb'		=> '',					// Fallback function
		'walker'			=> new WC_Product_Cat_List_Walker()
	));
} /* End Product Category Menu */

// The Links Menu
function joints_links_menu() {
    wp_nav_menu(array(
        'container'			=> 'false',				// Remove nav container
        'menu_id'			=> 'links-nav',		// Adding custom nav id
        'menu_class'		=> 'menu',				// Adding custom nav class
        'theme_location'	=> 'links-nav',		// Where it's located in the theme
        'depth'				=> 0,					// Limit the depth of the nav
        'fallback_cb'		=> '',					// Fallback function
        'walker'			=> new Off_Canvas_Menu_Walker()
    ));
} /* End Links Menu */

// Header Fallback Menu
function joints_main_nav_fallback() {
	wp_page_menu( array(
		'show_home'		=> true,
		'menu_class'	=> '',		// Adding custom nav class
		'include'		=> '',
		'exclude'		=> '',
		'echo'			=> true,
		'link_before'	=> '',		// Before each link
		'link_after'	=> ''		// After each link
	));
}

// Footer Fallback Menu
function joints_footer_links_fallback() {
	/* You can put a default here if you like */
}

// Add Foundation active class to menu
function required_active_nav_class( $classes, $item ) {
	if ( $item->current == 1 || $item->current_item_ancestor == true ) {
		$classes[] = 'active';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'required_active_nav_class', 10, 2 );