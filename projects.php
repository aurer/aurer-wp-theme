<?php 
/*
Template Name: Projects
*/
?>
<?php get_header() ?>
	
	<div id="content" class="one-column">
		<section id="main">
			<h1><?php the_title() ?></h1>
			<?php the_content() ?>
			<ul id="projects">
			<?php $loop = new WP_Query( array( 'post_type' => 'project', 'posts_per_page' => 10, 'orderby'=>'menu_order', 'order'=>'ASC' ) );
			while ( $loop->have_posts() ) : $loop->the_post(); $custom = get_post_custom($post->ID); ?>
			<li class="project mod<?php echo $loop->current_post % 3 ?> item<?php echo $loop->current_post ?>">
				<a href="<?php the_permalink() ?>">
					<h2><?php the_title() ?></h2>
					<div class="detail">
						<span class="thumbnail"><?php echo get_the_post_thumbnail( $post->ID ) ?></span>
						<span class="description"><?php echo $custom['description'][0] ?></span>
					</div>
				</a>
			</li>
			<?php endwhile; ?>
			</ul>
		</section>		
	</div>
	
<?php get_footer() ?>