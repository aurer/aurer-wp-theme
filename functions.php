<?php 

/*
	Register widget areas
*/
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
    	'name' => 'Blog Sidebar',
    	'id' => 'sidebar-1',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    
    register_sidebar(array(
    	'name' => 'Page Sidebar',
    	'id' => 'sidebar-2',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => 'Footer Left',
        'id' => 'foot-left',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => 'Footer Right',
        'id' => 'foot-right',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => 'Homepage 1',
        'id' => 'home-1',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ));
    
    register_sidebar(array(
        'name' => 'Homepage 2',
        'id' => 'home-2',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ));

}

// Add a custom style to tinyMCE
add_editor_style('css/editor-style.css');
    
/*
	Enable paginated links
*/
function paginated_links(){
    
    global $wp_query;

    $big = 999999999; // need an unlikely integer
    echo paginate_links( array(
        'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $wp_query->max_num_pages,
        'type' => 'list',
        'prev_text' => __('Prev'),
        'next_text' => __('Next'),
    ) );
}

/*
	Register custom footer menu
*/
if ( function_exists('register_nav_menus') ) {
    register_nav_menus(
        array(
            'main-menu' => __( 'Main Menu' )
        )
    );
}


if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
}
set_post_thumbnail_size( 220, 150, true );
//add_image_size( 'medium-thumb', 650, 9999 ); //650 pixels wide, unlimited height

/*
	Include custom plugins
*/
$theme_options = get_option('aurer_theme_options');
include "lib/html.php";
include "theme-options.php";
include "plugins/projects.php";
include "plugins/au-recent-posts-widget.php";
if($theme_options['enable_reader_widget'] == 'checked'){ include "plugins/au-reader-widget.php"; }
if($theme_options['enable_lastfm_widget'] == 'checked'){ include "plugins/au-lastfm-widget/au-lastfm-widget.php"; }
if($theme_options['enable_tweets_widget'] == 'checked'){ include "plugins/au-tweets-widget.php"; }
include "plugins/holding-page/index.php";

/*
    Truncate a sentence to a specified word count
*/
function word_truncate($string, $max=15, $prepend='...'){
    $words = split(' ', trim($string));
    if(count($words) > $max){
        $string = implode(' ', array_slice($words, 0, $max)).$prepend;
    }
    return $string;
}

/*
    Enable file upload of custom mime types
*/
add_filter('upload_mimes', function ( $existing_mimes=array() ) {
    $existing_mimes['svg'] = 'image/svg';
    $existing_mimes['webm'] = 'video/webm';
    return $existing_mimes;
});

/*
    Overide image captions to remove inline width, I fucking hate wordpress!
*/
add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
add_shortcode('caption', 'fixed_img_caption_shortcode');
function fixed_img_caption_shortcode($attr, $content = null) {
    
    // New-style shortcode with the caption inside the shortcode with the link and image tags.
    if ( ! isset( $attr['caption'] ) ) {
        if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
            $content = $matches[1];
            $attr['caption'] = trim( $matches[2] );
        }
    }

    // Allow plugins/themes to override the default caption template.
    $output = apply_filters('img_caption_shortcode', '', $attr, $content);
    if ( $output != '' ) return $output;
    extract(shortcode_atts(array(
        'id'=> '',
        'align' => 'alignnone',
        'width' => '',
        'caption' => ''), $attr));
    if ( 1 > (int) $width || empty($caption) )
    return '$content';
    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
    return '<div ' . $id . 'class="wp-caption ' . esc_attr($align)
    . '">'
    . do_shortcode( $content ) . '<p class="wp-caption-text">'
    . $caption . '</p></div>';
}
