<?php
/*
* Plugin Name: Aurer Contact Form
* Description: Displays contact from.
* Version: 1.0
* Author: Phil Maurer
*/
/*
	Theme options
*/

add_action('admin_menu', 'register_theme_settings_page');
function register_theme_settings_page(){
	//add_options_page('Theme Options', 'Theme Options', 'administrator', __FILE__, 'theme_options_page');
	add_theme_page('Theme Options', 'Theme Options', 'edit_theme_options', 'theme-options', 'theme_options_page');
}
function theme_options_page(){ ?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br></div>
		<?php if ( isset( $_GET['settings-updated'] ) ) { echo "<div class='updated'><p>Theme options updated successfully.</p></div>"; } ?>
		<h2>Theme Options</h2>
		<form action="options.php" method="post" enctype="multipart/form-data">
			<?php settings_fields('aurer_theme_options') ?>
			<?php do_settings_sections(__FILE__) ?>
			<p><input type="submit" class="button-primary" value="Save Changes"></p>
		</form>
	</div><?php
}


add_action('admin_init', 'theme_settings_init');
function theme_settings_init() {
 	
 	wp_enqueue_style('option-style', get_template_directory_uri().'/css/admin.css', false, 1.0);
 	wp_enqueue_script('option-js', get_template_directory_uri().'/js/admin.js', false, 1.0);
 	register_setting('aurer_theme_options','aurer_theme_options', 'sanitise_callback'); 	
 	add_settings_section('theme_option_section', 'Main options', 'options_section_cb', __FILE__);

 	// Add the field with the names and function to use for our new
 	// settings, put it in our new section
 	add_settings_field('theme_logo', 'Theme logo', 'theme_logo_cb', __FILE__, 'theme_option_section');
 	add_settings_field('homepage_strapline', 'Show Tagline on Homepage', 'homepage_strapline_cb', __FILE__, 'theme_option_section');
 	add_settings_field('enable_lastfm_widget', 'Enable LastFM Widget', 'enable_lastfm_widget_cb', __FILE__, 'theme_option_section');
 	add_settings_field('lastfm_username', 'LastFM Username', 'theme_lastfm_username_cb', __FILE__, 'theme_option_section');
 	add_settings_field('enable_reader_widget', 'Enable Google Reader Widget', 'enable_reader_widget_cb', __FILE__, 'theme_option_section');
 	add_settings_field('google_id', 'Google reader ID', 'theme_google_id_cb', __FILE__, 'theme_option_section');
 	add_settings_field('enable_tweets_widget', 'Enable Tweets Widget', 'enable_tweets_widget_cb', __FILE__, 'theme_option_section');
 	add_settings_field('twitter_id', 'Twitter Username', 'theme_twitter_id_cb', __FILE__, 'theme_option_section');
 	add_settings_field('enable_holding_page', 'Enable holding page', 'enable_holding_page_cb', __FILE__, 'theme_option_section');
} 
 
  
// ------------------------------------------------------------------
// Callback functions for options
// ------------------------------------------------------------------
function theme_logo_cb() {
	$options = get_option('aurer_theme_options');
	echo '<input name="theme_logo" type="file" /> <span class="description">The logo for the site</span>';
	if($options['theme_logo']){
		$size = getimagesize($options['theme_logo']);
		echo "<div class='option-image'><a href='$options[theme_logo]'><img src='{$options[theme_logo]}' alt='Theme logo' /></a><i class='image-meta'>{$size[0]} x {$size[1]}px</i></div>";
	} else {
		echo '<div class="option-image"><img src="'.get_bloginfo('stylesheet_directory').'/gfx/logo.png" alt="Theme logo" /></div>';
	}
}

function homepage_strapline_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[homepage_strapline]" type="checkbox" '.$options['homepage_strapline'].' value="checked"/>';
}

// Enable widget fields
function enable_lastfm_widget_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[enable_lastfm_widget]" id="option-enable-lastfm" type="checkbox" '.$options['enable_lastfm_widget'].' value="checked"/>';
}
function enable_reader_widget_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[enable_reader_widget]" id="option-enable-reader" type="checkbox" '.$options['enable_reader_widget'].' value="checked"/>';
}
function enable_tweets_widget_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[enable_tweets_widget]" id="option-enable-tweets" type="checkbox" '.$options['enable_tweets_widget'].' value="checked"/>';
}

// Widget value fields
function theme_lastfm_username_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[lastfm_username]" id="option-theme-lastfm-id" type="text" value="'.$options['lastfm_username'].'" class="code" /> <span class="description">Your Last.FM username for use with the reader widget</span>';
}
function theme_google_id_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[google_id]" id="option-theme-reader-id" type="text" value="'.$options['google_id'].'" class="code" /> <span class="description">Your Google Reader ID for use with the reader widget</span>';
}
function theme_twitter_id_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[twitter_id]" id="option-theme-tweets-id" type="text" value="'.$options['twitter_id'].'" class="code" /> <span class="description">Your Twitter username for use with the tweets widget</span>';
}
function enable_holding_page_cb() {
	$options = get_option('aurer_theme_options');
 	echo '<input name="aurer_theme_options[enable_holding_page]" id="option-enable-tweets" type="checkbox" '.$options['enable_holding_page'].' value="checked"/><span class="description">Disable access to the site</span>';
}

// ------------------------------------------------------------------
// Settings section callback function
// ------------------------------------------------------------------
function options_section_cb() {
	// echo '<p>Intro text for our settings section</p>';
}

function sanitise_callback($aurer_theme_options){

	foreach($_FILES as $key => $image){
		if($image['size']){
			if( preg_match('/(jpeg|jpg|png|gif|svg\+xml)$/i', $image['type']) ){
				$override = array('test_form' => false);
				
				$file = wp_handle_upload($image, $override);

				$aurer_theme_options[$key] = $file['url'];
			}
			else{
				wp_die('No image was uploaded '.$image['type']);
			}
		}
		else{
			$options = get_option('aurer_theme_options');
			$aurer_theme_options[$key] = $options[$key];
		}
	}

	return $aurer_theme_options;
}