<?php get_header() ?>	
	<div id="content">
		<section id="main">
			<?php the_post() ?>
			<h1><?php the_title() ?></h1>
			<?php the_content() ?>
		</section>
	</div>
<?php get_footer() ?>