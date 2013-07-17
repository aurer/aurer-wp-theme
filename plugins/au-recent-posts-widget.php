<?php
/*
* Plugin Name: Aurer Recent Posts Widget
* Description: Display latest posts in a custom format.
* Version: 1.0
* Author: Phil Maurer
*/

class Au_Recent_Posts_Widget extends WP_Widget {

    function __construct() {
		$widget_options = array('classname' => 'aurer-latest-posts', 'description' => __( 'Show recent posts in customisable format.') );
        parent::WP_Widget(false, $name = __( 'Aurer Latest Posts'), $widget_options);	
    }

    function widget($args, $instance) {		
	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$max = $instance['max'];
		$format = $instance['format'];
		$sidebar_posts = "";
       	
		echo $before_widget; 
		
		// If title is set, display it
		if ( $title ) { 
			echo $before_title . $title . $after_title; 
		} 
		
		// if format not set, apply standard format
		if ( !$format ) {
			$format = 'M d';
		}
		
		// if Max not set, show all
		if ( !$max ) {
			$max = 5;
		}
				
		$my_query = new WP_Query("posts_per_page=" . $max);
		while ($my_query->have_posts()) : $my_query->the_post(); 
			$sidebar_posts .= "<li>";
				$sidebar_posts .= "<span>" . get_the_time($format) . "</span>"; 
				$sidebar_posts .= "<a href='". get_permalink() . "'>" . get_the_title() . "</a>";				
			$sidebar_posts .= "</li>";
		endwhile;
		
		if(!empty($sidebar_posts)) {
			echo "<ul id='recent-posts'>" . $sidebar_posts . "</ul>";
		}
				
        echo $after_widget; 
    }


	// Update Settings
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['max'] = strip_tags($new_instance['max']);
		$instance['format'] = strip_tags($new_instance['format']);
		return $instance;
    }

	
	// User Settings Form
    function form($instance) {		
		
		$title = $max = $format = $show_excerpt = $show_date = $show_excerpt_checked = $show_date_checked = "";
		
        $title = esc_attr($instance['title']);
		$max = esc_attr($instance['max']);
		$format = esc_attr($instance['format']);
		$show_excerpt = esc_attr($instance['show_excerpt']);
		$show_date = esc_attr($instance['show_date']);
		
		if($show_excerpt == 1) {
			$show_excerpt_checked = " checked='checked' ";
		}
		
		if($show_date == 1) {
			$show_date_checked = " checked='checked' ";
		}
		
		if($format == ''){
			$format = 'M d';
		}
		
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('max'); ?>"><?php _e('Show how many:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('max'); ?>" name="<?php echo $this->get_field_name('max'); ?>" type="text" value="<?php echo $max; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Date format:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" type="text" value="<?php echo $format; ?>" /></label></p>			
        <?php 
    }

}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("Au_Recent_Posts_Widget");'));
?>