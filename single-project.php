<?php get_header() ?>
	<div id="content">
		<section id="main" class="post">
			<?php while ( have_posts() ) : the_post(); ?>
				<h1><?php the_title() ?></h1>
				<p class="meta"><?php the_time('jS M Y') ?> <b class="category"><?php the_category(', ') ?></b> <?php edit_post_link( __('Edit'), ' <span class="edit-post">', '</span>' ); ?></p>
				<?php the_content() ?>
				<?php comments_template( '', true ); ?>
				<div class="page-numbers">
					<span class="nav-previous"><?php previous_post_link( '%link', __( 'Previous post') ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', __( 'Next post') ); ?></span>
				</div>
			<?php endwhile ?>
		</section>
	</div>
<?php get_footer() ?>