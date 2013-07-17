<?php get_header() ?>

	<div id="content">
		<div id="main">
			<h1>Sorry we couldn't find that one...</h1>
			<p>It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.</p>		
			<?php get_search_form() ?>
		</div>
		<section id="secondary">
			
			<div id="categories-list" class="widget">
                <h3>Categories</h3>
                <ul>
                    <?php wp_list_categories('optioncount=1&title_li=') ?>
                </ul>
            </div>
			<div id="archives-list" class="widget">
                <h3>Recent posts</h3>
                <ul>
                    <?php $recent_posts = wp_get_recent_posts();
                    	foreach( $recent_posts as $recent ){
							echo '<li><span>' . date('M d', strtotime($recent["post_date"]) ) . '</span> <a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
						}
					?>
                </ul>
            </div>
		</section>
	</div>
	
<?php get_footer() ?>