<title><?php
/*
 * Print the <title> tag based on what is being viewed.
 */
global $page, $paged;

// Add the blog name.
bloginfo( 'name' );

wp_title(":");

// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_front_page() ) )
	echo " : Phil Maurer - Web Development";

// Add a page number if necessary:
if ( $paged >= 2 || $page >= 2 )
	echo ' : ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

$theme_options = get_option('aurer_theme_options');
$site_logo = $theme_options['theme_logo'] != '' ? $theme_options['theme_logo'] : get_bloginfo('stylesheet_directory')."/gfx/logo.png";

?></title>
<meta charset="<?php bloginfo('charset') ?>" />
<meta name="viewport" content="initial-scale=1.0">

<?php echo HTML::favicon('favicon.ico', 'apple-touch-icon.png') ?>

<?php HTML::enqueue_style("css/main.css", false, false, "all") ?>
<?php HTML::enqueue_style("css/print.css", false, false, "print") ?>
<?php HTML::enqueue_style("css/quicklook.css") ?>
<?php HTML::enqueue_style("css/ie.css", "lt IE 9") ?>

<?php HTML::enqueue_script("js/jquery.min.js") ?>
<?php HTML::enqueue_script("js/quicklook.js") ?>
<?php HTML::enqueue_script("js/aurer.js") ?>
<?php
	global $is_IE;
	if($is_IE){
		HTML::enqueue_script("js/html5shiv.js");
	}
?>

<?php wp_head() ?>