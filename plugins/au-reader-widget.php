<?php
/*
* Plugin Name: Aurer Reader Widget
* Description: Display your google reader starred or public items.
* Version: 1.0
* Author: Phil Maurer
*/

// Create the widget on the front-end
class Au_Reader_Widget extends WP_Widget {

    function __construct() {
		$widget_options = array('classname' => 'aurer-reader', 'description' => __( 'Display your google reader starred or public items.') );
        parent::WP_Widget(false, $name = __( 'Aurer Reader'), $widget_options);
    }


	// Update Settings
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['source'] = strip_tags($new_instance['source']);
		$instance['showmore'] = strip_tags($new_instance['showmore']);
		return $instance;
    }

	
	// User Settings Form
    function form($instance) {		
		
		$title = $key = $count = $thumbnails = $source = "";
		
        $title = esc_attr($instance['title']);
		$count = esc_attr($instance['count']);
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
            			<option <?php if($source=='starred') echo('selected') ?> value="starred">Starred</option>
            			<option <?php if($source=='broadcast') echo('selected') ?> value="broadcast">Broadcast</option>
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
		$source = strtolower($instance['source']);
		$showmore = $instance['showmore'];
		
		$theme_options = get_option('aurer_theme_options');
		$userid = $theme_options['google_id'];

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
			$source = 'starred';
		}

		?>
		<div id="au-reader"><ul><li>This item requires javascript</li></ul></div>
		<script id="au-reader-template" type="text/x-handlebars-template">
			<li>
				<a href="{{link}}" target="_blank"><b>{{title}}</b></a>
				<cite><a href="{{source_link}}" target="_blank">{{source_title}}</a></cite>
			</li>
		</script>
		<script type="text/javascript">
			$("#au-reader ul").JSWidget({
				template: '#au-reader-template',
				<?php if(!$showmore) echo "showmore: false," ?>
				count: <?php echo $count ?>,
				source: "http://www.google.com/reader/public/javascript/user%2F<?php echo $userid ?>%2Fstate%2Fcom.google%2F<?php echo $source ?>?callback=?",
				datamap: function(data){
					return $.map(data.items, function(data){	
						return {
							title 			: data.title,
							published 		: new Date(data.published),
							link 			: data.alternate.href,
							author 			: data.author,
							source_title 	: data.origin.title,
							source_link 	: data.origin.htmlUrl
						}
					});
				},
			});
		</script>
		<?php echo $after_widget;
    }

}

// register widget
add_action('widgets_init', 'load_au_reader_widget');

function load_au_reader_widget(){
	wp_register_script("jswidget", get_template_directory_uri()."/plugins/au-reader-widget/jswidget.js", array("jquery-min"));
	wp_enqueue_script("jswidget");
	register_widget('Au_Reader_Widget');
}