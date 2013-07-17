<?php
/*
* Plugin Name: Aurer Tweets Widget
* Description: Display a users twitter timeline.
* Version: 1.0
* Author: Phil Maurer
*/

class Au_Tweets_Widget extends WP_Widget {

    function __construct() {
		$widget_options = array('classname' => 'aurer-tweets', 'description' => __( 'Display your twitter timeline.') );
        parent::WP_Widget(false, $name = __( 'Aurer Tweets'), $widget_options);
    }


	// Update Settings
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['showmore'] = strip_tags($new_instance['showmore']);
		return $instance;
    }

	
	// User Settings Form
    function form($instance) {		
		
		$title = $username = $count = "";
		
        $title = esc_attr($instance['title']);
		$count = esc_attr($instance['count']);
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
            	<input type="checkbox" class="checkbox" name="<?php echo$this->get_field_name('showmore'); ?>" value="1" <?php if($showmore == '1') echo('checked') ?> id="<?php echo $this->get_field_id('showmore'); ?>" />
            	<label for="<?php echo $this->get_field_id('showmore'); ?>"><?php _e('Show more link'); ?></label>
            </p>
        <?php 
    }

	function widget($args, $instance) {		
		
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];
		
		$theme_options = get_option('aurer_theme_options');
		$username = $theme_options['twitter_id'];
		$showmore = $instance['showmore'];

		echo $before_widget;

		// If title is set, display it
		if ( $title ) { 
			echo $before_title . "<a href='http://twitter.com/#!/$username' target='_blank'>" . $title . "</a>" . $after_title; 
		} 
		
		// if Max not set, show all
		if ( !$count ) {
			$count = 5;
		}

		?>
		<div id="au-tweets"><ul><li>This item requires javascript</li></ul></div>
		<script id="au-tweets-template" type="text/x-handlebars-template">
			<li>
				{{text}}
			</li>
		</script>
		<script type="text/javascript">
			$("#au-tweets ul").JSWidget({
				template: '#au-tweets-template',
				<?php if(!$showmore) echo "showmore: false," ?>
				count: <?php echo $count ?>,
				source: "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=<?php echo $username ?>&trim_user=true&count=20&callback=?",
				datamap: function(data){
					return {
						text: data.text,
					}
				},
				datamap: function(data){
					return $.map(data, function(data){	
						return {
							text: addLinks(data.text)
						}
					});
				},
				cacheEnabled: false
			});
			function addLinks(string){
        		return string
        			.replace(/(http:\/\/[\w].[\w.\/]+[\w\?=&_-]+)/, '<a target="_blank" href="$1">$1</a>') // Links
        			.replace(/@([\S]+)/, '<a target="_blank" href="https://twitter.com/$1">@$1</a>') // Users
        			.replace(/#([\w]+)/, '<a target="_blank" href="https://twitter.com/search?q=$1&src=hash">#$1</a>') // Hashtags
    		}
		</script>
        <?php  echo $after_widget;
    }

}

// register widget
add_action('widgets_init', 'load_au_tweets_widget');

function load_au_tweets_widget(){
	wp_register_script("jswidget", get_template_directory_uri()."/plugins/au-reader-widget/jswidget.js", array("jquery-min"));
	wp_enqueue_script("jswidget");
	register_widget('Au_Tweets_Widget');
}