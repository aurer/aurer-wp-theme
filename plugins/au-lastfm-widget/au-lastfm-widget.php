<?php
/*
* Plugin Name: Aurer Scrobbles
* Description: Display tracks scrobbled to Last FM.
* Version: 1.0
* Author: Phil Maurer
*/

// Create the widget on the front-end
class Au_LastFM_Widget extends WP_Widget {

    function __construct() {
		$widget_options = array('classname' => 'aurer-lastfm', 'description' => __( 'Display tracks scrobbled to Last FM.') );
        parent::WP_Widget(false, $name = __( 'Aurer LastFM'), $widget_options);	
    }


	// Update Settings
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['thumbnails'] = strip_tags($new_instance['thumbnails']);
		$instance['source'] = strip_tags($new_instance['source']);
		$instance['showmore'] = strip_tags($new_instance['showmore']);
		return $instance;
    }

	
	// User Settings Form
    function form($instance) {		
		
		$title = $user = $count = $thumbnails = $source = $showmore = "";

        $title = esc_attr($instance['title']);
		$count = esc_attr($instance['count']);
		$thumbnails = esc_attr($instance['thumbnails']);
		$source = esc_attr($instance['source']);
		$showmore = esc_attr($instance['showmore']);
		
		if(empty($count)) $count = 5;
				
        ?>
        	<p>
        		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
        			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        		</label>
        	</p>
            <p>
            	<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show how many:'); ?>
            		<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" />
            	</label>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('source'); ?>"><?php _e('Show:'); ?>
            		<select class="widefat" name="<?php echo $this->get_field_name('source'); ?>">
            			<option <?php if($source=='RecentTracks') echo('selected') ?> value="RecentTracks">Recent Tracks</option>
            			<option <?php if($source=='LovedTracks') echo('selected') ?> value="LovedTracks">Loved Tracks</option>
            		</select>
            	</label>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('thumbnails'); ?>"><?php _e('Thumbnail Display:'); ?>
            		<select class="widefat" name="<?php echo $this->get_field_name('thumbnails'); ?>">
            			<option <?php if($thumbnails==0) echo('selected') ?> value="0">None</option>
            			<option <?php if($thumbnails==1) echo('selected') ?> value="1">Small</option>
            			<option <?php if($thumbnails==2) echo('selected') ?> value="2">Medium</option>
            			<option <?php if($thumbnails==3) echo('selected') ?> value="3">Large</option>
            		</select>
            	</label>
            </p>
            <p>
            	<input type="checkbox" class="checkbox" name="<?php echo$this->get_field_name('showmore'); ?>" value="1" <?php if($showmore == '1') echo('checked') ?> id="<?php echo $this->get_field_id('showmore'); ?>" />
            	<label for="<?php echo $this->get_field_id('showmore'); ?>"><?php _e('Show more link'); ?></label>
            </p>
        <?php 
    }

	function widget($args, $instance) {		
		
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];
		$thumbs = $instance['thumbnails'];
		$source = strtolower($instance['source']);
		$showmore = $instance['showmore'];

		$theme_options = get_option('aurer_theme_options');
		$user = $theme_options['lastfm_username'];

		$api_key = 'ccfce33b35f8480c2413f2a642fa2c6a';

		switch($thumbs){
			case '0' : 
				$thumbsize = '';
				$scale = 0;
				break;
			case '1' : 
				$thumbsize = 'image-small';
				$scale = 34;
				break;
			case '2' :
				$thumbsize = 'image-medium';
				$scale = 64;
				break;
			case '3' : 
				$thumbsize = 'image-large';
				$scale = 128;
				break;
		}
		
		echo $before_widget;

		// If title is set, display it
		if ( $title ) { 
			echo $before_title . $title . $after_title; 
		}
		
		// if Max not set, show all
		if ( !$count ) {
			$count = 5;
		}
		
		if( !$source ) {
			$source = 'recenttracks';
		}

		?>
		<div id="au-lastfm"><ul><li>This item requires javascript</li></ul></div>
		<script id="au-lastfm-template" type="text/x-handlebars-template">
			<li class="track thumb-<?php echo $scale ?>">
				<a href="{{url}}" title="View this track on Last.FM" target="_blank">
					<?php if ($thumbs): ?>
						<img src="{{image}}" width="<?php echo $scale ?>" height="<?php echo $scale ?>" alt="{{name}} album image">					
					<?php endif ?>
					<b class="trackname">{{name}}</b>
					<span class="trackartist">{{artist}}</span>
				</a>
			</li>
		</script>
		<script type="text/javascript">
			$("#au-lastfm ul").JSWidget({
				template: '#au-lastfm-template',
				<?php if(!$showmore) echo "showmore: false," ?>
				count: <?php echo $count ?>,
				source: "<?php echo get_template_directory_uri() . "/plugins/au-lastfm-widget/fetch.php?source=" . $source . "&user=" . $user ?>",
				datamap: function(data){
					return $.map(data.items, function(data){
						return {
							artist: data.artist,
							name: data.name,
							url: data.url,
							image: data["<?php echo $thumbsize ?>"].length ? data["<?php echo $thumbsize ?>"] : "<?php echo get_template_directory_uri().'/gfx/album_default.jpg' ?>",
						}
					});
				},
				cachePrefix: "<?php echo $thumbsize ?>"
			});
		</script>
		<?php echo $after_widget;
        
    }

}

// register widget
// add_action('widgets_init', create_function('', 'return register_widget("Au_LastFM_Widget");'));

add_action('widgets_init', 'load_au_lastfm_widget');

function load_au_lastfm_widget(){
	wp_register_script("jswidget", get_template_directory_uri()."/plugins/au-reader-widget/jswidget.js", array("jquery-min"));
	wp_enqueue_script("jswidget");
	register_widget('Au_LastFM_Widget');
}
