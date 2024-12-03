<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Subpage Header Code
require_once('subpage-header.php');

//* Set Localization (do not remove)
load_child_theme_textdomain( 'parallax', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'parallax' ) );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'parallax_customizer' );
function parallax_customizer(){
	require_once( get_stylesheet_directory() . '/lib/customize.php' );
}

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

global $blogurl;
$blogurl = get_stylesheet_directory_uri();

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles' );
function parallax_enqueue_scripts_styles() {
	// Styles
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/css/allstyles.css', array() );
	wp_enqueue_style( 'responsive-styles', get_stylesheet_directory_uri() . '/css/responsive-styles.css', array() );
	wp_enqueue_style( 'icomoon-fonts', get_stylesheet_directory_uri() . '/icomoon.css', array() );
	//wp_enqueue_style( 'googlefonts', '//fonts.googleapis.com/css?family=Montserrat:400,700,900|PT+Sans:400,700', array() );

	// Scripts
	wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array() );
	
}

// Removes Query Strings from scripts and styles
function remove_script_version( $src ){
  if ( strpos( $src, 'uploads/bb-plugin' ) !== false || strpos( $src, 'uploads/bb-theme' ) !== false ) {
    return $src;
  }
  else {
    $parts = explode( '?ver', $src );
    return $parts[0];
  }
}
add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Reposition the primary navigation menu
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Add Search to Primary Nav
//add_filter( 'genesis_header', 'genesis_search_primary_nav_menu', 10 );
function genesis_search_primary_nav_menu( $menu ){
    locate_template( array( 'searchform-header.php' ), true );
}

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'breadcrumb',
	'footer-widgets',
	'footer',
) );

// Add Read More Link to Excerpts
add_filter('excerpt_more', 'get_read_more_link');
add_filter( 'the_content_more_link', 'get_read_more_link' );
function get_read_more_link() {
   return '...&nbsp;<a class="readmore" href="' . get_permalink() . '">Read&nbsp;More &raquo;</a>';
}

// Add Beaver Builder Editable Footers to the Genesis Footer hook
add_action( 'genesis_before_footer', 'global_footer', 4 );
function global_footer(){
	echo do_shortcode('[fl_builder_insert_layout slug="global-footer"]');
}

//* Add support for 4-column footer widgets
//add_theme_support( 'genesis-footer-widgets', 4 );

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
	$post_info = '[post_date] [post_comments] [post_edit]';
	return $post_info;
}

//* Custom Breadcrumb Hook 
function breadcrumb_hook() {
	do_action('breadcrumb_hook');
}

//* Remove breadcrumbs and reposition them
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'breadcrumb_hook', 'genesis_do_breadcrumbs', 12 );

// Modify Breadcrumbs Args
add_filter( 'genesis_breadcrumb_args', 'malcolm_breadcrumb_args' );
function malcolm_breadcrumb_args( $args ) {
	$args['prefix'] = '<div class="breadcrumbs"><div class="wrap">';
	$args['suffix'] = '</div></div>';
	$args['sep'] = ' <span class="bread-sep">></span> ';
	$args['heirarchial_attachments'] = true;
	$args['heirarchial_categories'] = true;
	$args['display'] = true;
	$args['labels']['prefix'] = '';
    return $args;
}

// Widget - Latest News on home page
genesis_register_sidebar( array(
	'id'			=> 'home-latest-news',
	'name'			=> __( 'Latest News on Home Page', 'thrive' ),
	'description'	=> __( 'This is latest news home page widget', 'thrive' ),
) );

// Blog Widgets
genesis_register_sidebar( array(
	'id'			=> 'blog-sidebar',
	'name'			=> __( 'Blog Widgets', 'thrive' ),
	'description'	=> __( 'This is latest news widget', 'thrive' ),
) );

// Single Event Page Sidebar
genesis_register_sidebar( array(
	'id'			=> 'single-event-sidebar',
	'name'			=> __( 'Single Event Widgets', 'thrive' ),
	'description'	=> __( 'This is single event page sidebar widget', 'thrive' ),
) );

// Add Header Links Widget to Header
//add_action( 'genesis_before', 'header_widget', 1 );
	function header_widget() {
	if (is_active_sidebar( 'header-links' ) ) {
 	genesis_widget_area( 'header-links', array(
		'before' => '<div class="header-links">',
		'after'  => '</div>',
	) );
}}

// Unregister unused sidebar
//unregister_sidebar( 'header-right' );

// Previous / Next Post Navigation Filter For Genesis Pagination
add_filter( 'genesis_prev_link_text', 'gt_review_prev_link_text' );
function gt_review_prev_link_text() {
        $prevlink = '&laquo;';
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'gt_review_next_link_text' );
function gt_review_next_link_text() {
        $nextlink = '&raquo;';
        return $nextlink;
}

/* Subpage Header Backgrounds - Utilizes: Featured Images & Advanced Custom Fields Repeater Fields */

// AFC Repeater Setup - NOTE: Set Image Return Value to ID
// Row Field Name:
$rows = '';
$rows = get_field('subpage_header_backgrounds', 5);

// Counts the rows and selects a random row
if( is_array($rows) ){
	$row_count = count($rows);

	$i = rand(0, $row_count - 1);
	// Set Image size to be returned
	$image_size = 'subpage-header';
	// Get Image ID from the random row
	$image_id = $rows[ $i ]['background_image'];
	// Use Image ID to get Image Array
	$image_array = wp_get_attachment_image_src($image_id, $image_size);
	// Set "Default BG" to first value of the Image Array. $image_array[0] = URL;
	$default_bg = $image_array[0]; 
}

// Custom function for getting background images
function custom_background_image($postID = "") {
	// Variables
	global $default_bg;
	global $postID;
	global $blog_slug;
	
	$currentID = get_the_ID();
	$blogID = get_option( 'page_for_posts');
	$parentID = wp_get_post_parent_id( $currentID );

	// is_home detects if you're on the blog page- must be set in admin area
	if( is_home() ) {
		$currentID = $blogID;
	} 
	// Else if post page, set ID to BlogID.
	elseif( is_home() || is_single() || is_archive() || is_search() ) {
		$currentID = $blogID;
	}

	// Try to get custom background based on current page/post
	$currentBackground = wp_get_attachment_image_src(get_post_thumbnail_id($currentID), 'subpage-header');
	//Current page/post has no custom background loaded
	if(!$currentBackground) {
		// Find blog ID
		$blog_page = get_page_by_path($blog_slug, OBJECT, 'page');
		if ($blog_page) {
			$blogID = $blogID;
			$currentID = $blogID;
		}
		// Else if post page, set ID to BlogID.
		elseif(is_single() || is_archive()) {
			$currentID = $blogID; 
		}

		// Current page has a parent
		if($parentID) {
			// Try to get parents custom background
			$parent_background = wp_get_attachment_image_src(get_post_thumbnail_id($parentID), 'subpage-header');
			// Set parent background if it exists
			if($parent_background) {
				$background_image = $parent_background[0];
			}
			// Set default background
			else {
				$background_image = $default_bg;
			}
		}
		// NO parent or no parent background: set default bg.
		else {
			$background_image = $default_bg;
		}
	}
	// Current Page has a custom background: use that
	else {
		$background_image = $currentBackground[0];
	}
	return $background_image;
}

/* Changing the Copyright text */
function genesischild_footer_creds_text () {
	global $blogurl;
 	echo '<div class="clearboth copy-line">
 			<div class="copyright first">
 				<p><span id="copy">Copyright &copy; '. date("Y") .' - All rights reserved</span> <span class="format-pipe">&#124;</span>  
	 			<a href="/sitemap/">Site Map</a>  <span>&#124;</span>  
	 			<a href="/privacy-policy/">Privacy Policy</a>  
	 			</p>
 			</div>
 			<div class="credits">
 				<span>Site by</span>
 				<a target="_blank" href="https://thriveagency.com/">
 					<img class="svg" src="'.  $blogurl . '/images/thrive-logo.png" alt="Web Design by Thrive Internet Marketing">
 				</a>
 			</div>
 		  </div>';
}
add_filter( 'genesis_footer_creds_text', 'genesischild_footer_creds_text' );


//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav', 12 );

// Add Additional Image Sizes
add_image_size( 'genesis-post-thumbnail', 163, 108, true );
add_image_size( 'subpage-header', 1600, 162, true );
add_image_size( 'news-thumb', 260, 150, false );
add_image_size( 'news-full', 800, 300, false );
add_image_size( 'sidebar-thumb', 200, 150, false );
add_image_size( 'mailchimp', 564, 9999, false );
add_image_size( 'amp', 600, 9999, false  );
add_image_size( 'home-thumbnail', 400, 230, true  );
add_image_size( 'team-thumb', 249, 249, true  );
add_image_size( 'ribbon-blog', 420, 240, true  );
add_image_size( 'single-event-full', 700, 400, true  );


// Gravity Forms confirmation anchor on all forms
add_filter( 'gform_confirmation_anchor', '__return_true' );


// Button Shortcode
// Usage: [button url="https://www.google.com"] Button Shortcode [/button]
function button_shortcode($atts, $content = null) {
  extract( shortcode_atts( array(
	  'url' => '#',
	  'target' => '_self',
	  'onclick' => '',

  ), $atts ) 
);
return '<a target="' . $target . '" href="' . $url . '" class="button" onClick="' . $onclick . '"><span>' . do_shortcode($content) . '</span></a>';
}
add_shortcode('button', 'button_shortcode');

// Link Shortcode
// Usage: [link url=”tel:1-817-447-9194″ onClick=”onClick=”ga(‘send’, ‘event’, { eventCategory: ‘Click to Call’, eventAction: ‘Clicked Phone Number’, eventLabel: ‘Header Number’});”]
function link_shortcode($atts, $content = null) {
  extract( shortcode_atts( array(
	  'url' => '#',
	  'target' => '_self',
	  'onclick' => '',
  ), $atts ) 
);
return '<a target="' . $target . '" href="' . $url . '" onClick="' . $onclick . '">' . do_shortcode($content) . '</a>';
}
add_shortcode('link', 'link_shortcode');

//* Declare WooCommerce support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// Advance Custom field for Scheme Markups will be output under wphead tag
add_action('wp_head', 'add_scripts_to_wphead');
function add_scripts_to_wphead() {
	if( get_field('custom_javascript') ):	
		echo get_field('custom_javascript', 5);
	endif;
}

// Run shortcodes in Text Widgets
add_filter('widget_text', 'do_shortcode');


// Add "nav-primary" class to Main Menu as this gets removed when we reposition the menu inside header/widget area
add_filter( 'genesis_attr_nav-header', 'thrive_custom_nav_id' );
function thrive_custom_nav_id( $attributes ) {
 	$attributes['class'] = 'nav-primary';
 	return $attributes;
}

// Added to extend allowed files types in Media upload
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {

// Add *.EPS files to Media upload
$existing_mimes['eps'] = 'application/postscript';

// Add *.AI files to Media upload
$existing_mimes['ai'] = 'application/postscript';

return $existing_mimes;
}

// Added Thumbnail to event
function custom_widget_featured_image() {
  global $post;

  echo tribe_event_featured_image( $post->ID, 'home-thumbnail' );
}
add_action( 'tribe_events_list_widget_before_the_event_title', 'custom_widget_featured_image' );

/* Site Optimization - Removing several assets from Home page that we dont need */

// Remove Assets from HOME page only
function remove_home_assets() {
  if (is_front_page()) {
      
	  wp_dequeue_style('wpautoterms_css');
	  wp_dequeue_style('font-awesome-5');
	  wp_dequeue_style('yoast-seo-adminbar');
	  wp_dequeue_style('addtoany');
	  
  }
  
};
add_action( 'wp_enqueue_scripts', 'remove_home_assets', 9999 );

add_action('wp_footer', function() {
	wp_dequeue_style('gravityformsmailchimp_form_settings');
	
	if ( !is_user_logged_in() ) {
	    wp_dequeue_style('font-awesome-5');
	  }
	    
});

// Remove Assets Globally 
function wpfiles_dequeue() {
	if (current_user_can( 'update_core' )) {
		return;
	}
	wp_dequeue_style('yoast-seo-adminbar');
	wp_dequeue_style('gravityformsmailchimp_form_settings');
	
}
add_action( 'wp_enqueue_scripts', 'wpfiles_dequeue', 99 );

add_action('wp_footer', function() {
    wp_dequeue_style('gravityformsmailchimp_form_settings');
});


//Removing unused Default Wordpress Emoji Script - Performance Enhancer
function disable_emoji_dequeue_script() {
    wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Removes Emoji Scripts 
add_action('init', 'remheadlink');
function remheadlink() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

//****** AMP Customizations ******/

//* Enqueue "stylesheet" for AMP */
add_action('amp_init','amp_css', 11);
function amp_css() { 
	require_once('css/amp.php');
}

//* Add Featured Images to AMP content
add_action( 'pre_amp_render_post', 'amp_add_custom_actions' );
function amp_add_custom_actions() {
    add_filter( 'the_content', 'amp_add_featured_image' );
}

function amp_add_featured_image( $content ) {
    if ( has_post_thumbnail() ) {
        // Just add the raw <img /> tag; our sanitizer will take care of it later.
        $image = sprintf( '<p class="featured-image">%s</p>', get_the_post_thumbnail(get_the_ID(), 'amp') );
        $content = $image . $content;
    }
    return $content;
}

// Add Fav Icon to AMP Pages
add_action('amp_post_template_head','amp_favicon');
function amp_favicon() { ?>
	<link rel="icon" href="<?php echo get_site_icon_url(); ?>" />
<?php } 

// Add Banner below content of AMP Pages
add_action('ampforwp_after_post_content','amp_custom_banner_extension_insert_banner');
function amp_custom_banner_extension_insert_banner() { ?>
	<div class="amp-custom-banner-after-post">
		<h2>CUSTOM AMP BANNER TEXT HERE IF NEEDED</h2>
		<a class="ampforwp-comment-button" href="/contact-us/">
			CONTACT US
		</a>
	</div>
<?php } 

//Sets the number of revisions for all post types
add_filter( 'wp_revisions_to_keep', 'revisions_count', 10, 2 );
function revisions_count( $num, $post ) {
	$num = 3;
    return $num;
}

// Enable Featured Images in RSS Feed and apply Custom image size so it doesn't generate large images in emails
function featuredtoRSS($content) {
global $post;
if ( has_post_thumbnail( $post->ID ) ){
$content = '<div>' . get_the_post_thumbnail( $post->ID, 'mailchimp', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
}
return $content;
}
 
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

add_filter( 'genesis_pre_get_sitemap', 'thrive_genesis_pre_get_sitemap', 10 );
/**
 * Modifies the sitemap html to include a limit to the amount of pages, categories, authors, etc, that will be displayed.
 * @return string sitemap html
 */
function thrive_genesis_pre_get_sitemap() {

	$heading = 'h2';

	$sitemap  = sprintf( '<%2$s>%1$s</%2$s>', __( 'Pages:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_list_pages( array(
		'title_li' => null,
		'echo' => false,
		'depth' => 1,
		'sort_column' => 'post_title',
	)));

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Categories:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_list_categories( array(
		'sort_column' => 'name',
		'title_li' => null,
		'echo' => false,
		'depth' => 1,
	)));

	$users = get_users( array(
		'number' => 10,
		'who' => 'authors',
		'has_published_posts' => true,
	));

	ob_start();
	foreach ( $users as $user ) {
		$author_url = get_author_posts_url( $user->ID );
		?>
		<li>
			<a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $user->display_name ); ?></a>
		</li>
		<?php
	}
	$user_li_html = ob_get_clean();

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Authors:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', $user_li_html );

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Monthly:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_get_archives( array(
		'type' => 'monthly',
		'echo' => false,
		'limit' => 12,
	)));

	$sitemap .= sprintf( '<%2$s>%1$s</%2$s>', __( 'Recent Posts:', 'genesis' ), $heading );
	$sitemap .= sprintf( '<ul>%s</ul>', wp_get_archives( array(
		'type' => 'postbypost',
		'limit' => 10,
		'echo' => false,
	)));


	return $sitemap;
}

/* 
 * Dequeue Gutenberg-hooked CSS file `wp-block-library.css` file from `wp_head()`
 *
 * @author Thrive Agency
 * @since  12182018
 * @uses   wp_dequeue_style
 */
add_action( 'wp_enqueue_scripts', function() {
  wp_dequeue_style( 'wp-block-library' );
} );



// Here we hook into our template action - just before the date tag, which is the first item in the container.
add_action(
  'tribe_template_after_include:events/v2/widgets/widget-events-list/event/date-tag',
  'my_action_add_event_featured_image',
  15,
  3
);
 
// Here we utilize the hook variables to get our event, find the image, and echo the thumbnail.
function my_action_add_event_featured_image( $file, $name, $template ) {
  // Get the event for reference - we'll need it.
  $event = $template->get('event');
 
  $link = sprintf(
    '<a class="event-image-link" href="%1$s">%2$s</a>',
    get_the_post_thumbnail_url( $event ),
    get_the_post_thumbnail( $event, 'thumbnail', array( 'class' => 'alignleft' ) )
  );
 
  echo $link;
}

/**
 * The Events Calendar - Bypass Genesis genesis_do_post_content in Event Views
 *
 * This snippet overrides the Genesis Content Archive settings for Event Views
 *
 * Event Template set to: Admin > Events > Settings > Display Tab > Events template > Default Page Template
 *
 * The Events Calendar @4.0.4
 * Genesis @2.2.6
 */
add_action( 'get_header', 'tribe_genesis_bypass_genesis_do_post_content' );
function tribe_genesis_bypass_genesis_do_post_content() {
  if ( ! class_exists( 'Tribe__Events__Main' ) ) {
    return;
  }
  add_action( 'genesis_before_loop', 'subpage_header' );
 
  if ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
    if ( tribe_is_month() 
      || tribe_is_upcoming() 
      || tribe_is_past() 
      || tribe_is_day() 
      || tribe_is_map() 
      || tribe_is_photo() 
      || tribe_is_week() 
      || ( tribe_is_recurring_event() 
        && ! is_singular( 'tribe_events' ) ) 
    ) {
    	add_action( 'genesis_header', 'subpage_header', 12 );
    }
  } else {
    if ( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() ) {
    	add_action( 'genesis_header', 'subpage_header', 12 );
    }
  }
 
}

// Current year shortcode
function current_year() {
    $year = date('Y');
    return $year;
}

add_shortcode('year', 'current_year');

// Removing Archive text from Events page title

function modify_events_archive_title( $title ) {
    if ( is_post_type_archive( 'tribe_events' ) ) {
        $title = 'Events';
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'modify_events_archive_title' );

