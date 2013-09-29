<?php get_header() ?>
	
	<div id="content">
		<section id="main">
			<?php if ( have_posts() ) : ?>
				<h1><?php single_cat_title(); ?></h1>
				<?php while ( have_posts() ) : the_post() ?>
					<article class="post type-<?php echo get_post_type(); ?>">
						<?php/* Projects */?>
						<?php if ( has_post_thumbnail() && get_post_type() == 'project' ): $custom = get_post_custom() ?>
							<div class="post-content">
								<h2><a href="<?php the_permalink() ?>">Projects - <?php the_title() ?></a></h2>
								<p class="meta"><?php the_time('jS M Y') ?> <b class="category"><?php the_category(', ') ?></b> <?php edit_post_link( __('Edit'), ' <span class="edit-post">', '</span>' ); ?></p>
								<p class="description"><?php echo $custom['description'][0]; ?></p>
							</div>
							<div class="post-thumb">
								<a href="<?php the_permalink() ?>"><?php echo the_post_thumbnail(); ?></a>
							</div>
						<?php else: ?>
						<?php/* Posts */?>
							<div class="post-content">
								<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
								<p class="meta"><?php the_time('jS M Y') ?> <b class="category"><?php the_category(', ') ?></b> <?php edit_post_link( __('Edit'), ' <span class="edit-post">', '</span>' ); ?></p>
								<?php the_content() ?>
							</div>
						<?php endif ?>
					</article>
				<?php endwhile ?>
				<?php if( the_post() ) : ?>
					<h2>Nothing to see here...</h2>
					<p>Sorry, no posts we're found.</p>
				<?php endif ?>
			<?php else: ?>
				<h2>Nothing to see here...</h2>
				<p>Sorry, no posts we're found.</p>
			<?php endif ?>
			<?php paginated_links() ?>

		</section>
		
		<section id="secondary">
			<h3>Categories</h3>
			<ul>  
                <?php wp_list_categories('optioncount=1&title_li=') ?>  
            </ul>  
		</section>
		
	</div>
	
<?php get_footer() ?>