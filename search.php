<?php get_header() ?>
	<div id="content">
		<section id="main">
			<?php if ( have_posts() ) : ?>
				<h1>Search results for <q><b><?php echo get_search_query() ?></b></q></h1>
				<?php while ( have_posts() ) : ?>
					<div class="post">
					<?php the_post() ?>
					<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
					<p class="category"><i>Categoryies:</i> <?php the_category(', ') ?> | <i>Posted on: </i><?php the_time('j F Y') ?></p>
					</div>
				<?php endwhile ?>
				<?php paginated_links() ?>
			<?php else: ?>
				<h1>Nothing found for <q><b><?php echo get_search_query() ?></b></q></h1>
				<p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
			<?php endif; ?>
		</section>
		<?php get_sidebar() ?>
	</div>
<?php get_footer() ?>