<?php 
/*
Template Name: Reading list
*/

$theme_options = get_option('aurer_theme_options');
$response = file_get_contents("http://www.google.com/reader/public/javascript/user%2F" . $theme_options['google_id'] . "%2Fstate%2Fcom.google%2Fstarred");
$result = json_decode($response);
$reader_items = array();
if($result){
	$reader_items = array_map(function($item){
		return array(
			"title"			=> $item->title,
			"published"		=> date("jS M Y", $item->published),
			"content"		=> trim(preg_replace('/\\[[\w\W]*\\]/i', "", $item->content )),
			"link"			=> $item->alternate->href,
			"author"		=> $item->author,
			"source_title"	=> $item->origin->title,
			"source_link"	=> $item->origin->htmlUrl
		);
	}, $result->items);
}
?>
<?php get_header() ?>
	<div id="content">
		<section id="main">
			<?php the_post() ?>
			<h1><?php wp_title('') ?></h1>
			<?php if(empty($reader_items)): ?>
				<h2>Hmm...it looks like Google aren't responding right now.</h2>
				<p>The Google Reader webservice has failed to provide a list of my articles, if reloading doesn't help I guess you'll have to try again later.</p>
			<? else: ?>
				<?php the_content() ?>
				<?php foreach ($reader_items as $item): ?>
					<article class="post">
						<h2><a target="_blank" href="<?php echo $item['link'] ?>"><?php echo $item['title'] ?></a></h2>
						<p class="meta"><?php echo $item['published'] ?> via <a target="_blank" href="<?php echo $item['source_link'] ?>"><?php echo $item['source_title'] ?></a></p>
						<div class="post-content">
							<p><?php echo $item['content'] ?></p>
						</div>
					</article>
				<?php endforeach  ?>
			<?php endif ?>
		</section>
		<script type="text/javascript">
			$(function(){
				
			});
		</script>
	</div>
<?php get_footer() ?>