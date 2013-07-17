<?php 
/*
    Template Name: Archives
*/
?>
<?php get_header() ?>
	
	<div id="content">
		<section id="main">
			<?php the_post() ?>
			<h1><?php wp_title('') ?></h1>
			<?php the_content() ?>
            <div id="categories-list" class="archives">
                <h2>By category</h2>
                <ul>
                    <?php wp_list_categories('optioncount=1&title_li=&show_count=true') ?>
                </ul>
            </div>
			<div id="archives-list" class="archives">
                <h2>By Month</h2>
                <ul>
                    <?php wp_get_archives('type=monthly&show_post_count=true') ?>
                </ul>
            </div>
		</section>		
	</div>
	
<?php get_footer() ?>