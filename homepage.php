<?php
/*
	Template Name: Homepage
*/
$theme_options = get_option('aurer_theme_options');
$site_logo = $theme_options['theme_logo'] != '' ? $theme_options['theme_logo'] : get_bloginfo('stylesheet_directory')."/gfx/logo.png";
HTML::enqueue_style("css/home.css", array("main"));
HTML::enqueue_script("plugins/au-reader-widget/jswidget.js", array("jquery-min"));
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php get_template_part('head') ?>
</head>
<body>
<div id="page">
	<div id="first">
		<h1><a href="/"><img src="<?php echo $site_logo ?>" alt="<?php bloginfo('name') ?>" /></a></h1>
		<nav><?php wp_nav_menu( array( 'theme_location'=>'main-menu', 'sort_column' => 'menu_order', 'container_id' => 'navigation' ) ); ?></nav>
		<?php if($theme_options["homepage_strapline"] == true): ?> <h2><span><?php bloginfo('description') ?></span></h2><? endif ?>
	</div>
	<div id="second">
		<div class="section" id="section2">
			<h2><a href="/posts/">Posts</a></h2>
			<ul>
			<?php $posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 4 ) );
			while ( $posts->have_posts() ) : $posts->the_post(); ?>
				<li>
					<a href="<?php the_permalink() ?>"><strong><?php the_title() ?></strong></a>
					<small><?php echo word_truncate(get_the_excerpt(), 11) ?></small>
				</li>
			<?php endwhile ?>
			</ul>
			<a href="/posts/">more</a>
		</div>
		<div class="section" id="section1">
			<h2><a href="/projects/">Projects</a></h2>
			<ul>
			<?php $projects = new WP_Query( array( 'post_type' => 'project', 'posts_per_page' => 4 ) );
			while ( $projects->have_posts() ) : $projects->the_post(); $custom = get_post_custom($post->ID); ?>
				<li>
					<a href="<?php the_permalink() ?>"><strong><?php the_title() ?></strong></a>
					<small><?php echo $custom['description'][0] ?></small>
				</li>
			<?php endwhile ?>
			</ul>
			<a href="/projects/">more</a>
		</div>
		<div class="section" id="section3">
			<div id="au-lastfm">
				<ul id="lastfm-widget" class="widget"></ul>
			</div>
			<script id="au-lastfm-template" type="text/x-handlebars-template">
				<li class="thumb-34">
					<a href="{{url}}" target="_blank">
						<img src="{{image_small}}" alt="" width="34" height="34" />
						{{name}}</a>
					<cite><a href="{{url}}" target="_blank">{{artist}}</a></cite>
				</li>
			</script>
			<script type="text/javascript">
				$("#lastfm-widget").JSWidget({
					template: '#au-lastfm-template',
					showmore: false,
					count: 4,
					title: '<h2><a href="/music/">Listening to</a></h2>',
					source: "<?php echo get_template_directory_uri()."/plugins/au-lastfm-widget/fetch.php?source=RecentTracks&user=philmau"; ?>",
					datamap: function(data){
						return $.map(data.items, function(data){
							return {
								artist: data.artist,
								name: data.name,
								url: data.url,
								image_small: data["image-small"].length ? data["image-small"] : "<?php echo get_template_directory_uri().'/gfx/album_default.jpg' ?>",
							}
						});
					}
				});
			</script>
		</div>
	</div>
	<div id="third">
		<!-- This is just here for aesthetics -->
	</div>
</div>
<?php wp_footer() ?>
</body>
</html>