<?php
/*
	Template Name: Page with widgets
*/
?>
<?php get_header() ?>	
	<div id="content">
		<section id="main">
			<?php the_post() ?>
			<h1><?php the_title() ?></h1>
			<?php the_content() ?>
		</section>
		<?php get_template_part('sidebar') ?>
	</div>
<?php get_footer() ?>