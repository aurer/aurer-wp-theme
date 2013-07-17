<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php get_template_part('head') ?>
</head>
<body>
<div id="container">
	<div id="page">
		<div id="mast">
			<div class="inner">
				<div id="logo" title="<?php bloginfo('description') ?>">
					<?php
						$theme_options = get_option('aurer_theme_options');
						$site_logo = $theme_options['theme_logo'] != '' ? $theme_options['theme_logo'] : get_bloginfo('stylesheet_directory')."/gfx/logo.png";
					?>
					<a href="<?php echo get_option('home'); ?>/"><img src="<?php echo $site_logo; ?>" alt="<?php bloginfo('name') ?>" /></a>
				</div>
				<?php wp_nav_menu( array( 'theme_location'=>'main-menu', 'sort_column' => 'menu_order', 'container_id' => 'nav1' ) ); ?>
				<form id="search" action="<?php echo get_option('home') ?>/">
					<input type="search" autocapitalize="off" name="s" id="input" title="Search the site" value="<?php echo get_search_query() ?>" placeholder="Search the site" />
				</form>
			</div>
		</div>