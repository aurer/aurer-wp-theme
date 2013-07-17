<?php get_header() ?>
	<div id="content">
		<section id="main" class="post">
			<?php while ( have_posts() ) : the_post(); ?>
				<h1><?php the_title() ?></h1>
				<?php if (wp_attachment_is_image($post->id)) :
					$att_image = wp_get_attachment_image_src( $post->id, "large");?>
					<p class="attachment">
						<a href="<?php echo wp_get_attachment_url($post->id); ?>" title="<?php the_title(); ?>">
						<img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>"  class="attachment-medium" alt="<?php $post->post_excerpt; ?>" />
						</a>
					</p>
				<?php endif ?>
			<?php endwhile ?>
		</section>
	</div>
<?php get_footer() ?>