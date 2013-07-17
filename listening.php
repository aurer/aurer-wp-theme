<?php 
/*
Template Name: Music list
*/
$theme_options = get_option('aurer_theme_options');
$user = $theme_options['lastfm_username'];
$api_key = 'ccfce33b35f8480c2413f2a642fa2c6a';
$xml = simplexml_load_file("http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=$user&api_key=$api_key");
$tracks = array();
$scale=128;
if($xml){
	foreach ( $xml->recenttracks->track as $key => $value) {
		$track['artist']       = (string)$value->artist;
		if( $source == 'lovedtracks' ){
			$track['artist']       = (string)$value->artist->name;	
		}
		$track['name']         = (string)$value->name;
		$track['album']        = (string)$value->album;
		$track['date']         = (string)$value->date;
		$track['image-small']  = (string)$value->image[0];
		$track['image-medium'] = (string)$value->image[1];
		$track['image-large']  = (string)$value->image[2];
		$track['image-xlarge'] = (string)$value->image[3];
		$track['url']          = (string)$value->url;
		array_push($tracks, $track);
	};
}
?>
<?php get_header() ?>
	<div id="content">
		<section id="main">
			<?php the_post() ?>
			<h1><?php wp_title('') ?></h1>
			<?php if(empty($tracks)): ?>
				<h2>Hmm...it looks like Last.Fm aren't responding right now.</h2>
				<p>The Last.fm webservice has failed to provide a list of my tracks, if reloading doesn't help I guess you'll have to try again later.</p>
			<? else: ?>
				<?php the_content() ?>
				<?php foreach ($tracks as $item): ?>
					<article class="post music-track">
						<?php if($item['image-large']): ?>
							<img class="album-art" src="<?php echo $item['image-large'] ?>" width="<?php echo $scale ?>" height="<?php echo $scale ?>" alt="<?php echo $item['name'] ?> album image">
						<?php else: ?>
							<img class="album-art" src="<?php echo get_template_directory_uri().'/gfx/album_default.jpg' ?>" width="<?php echo $scale ?>" height="<?php echo $scale ?>" alt="{{name}} album image">
						<?php endif ?>
						<h3 class="trackname"><a href="<?php echo $item['url'] ?>" title="View this track on Last.FM" target="_blank"><?php echo $item['name'] ?></a></h3>
						<p class="trackartist">By <b><?php echo $item['artist'] ?></b>
						<br><small>Played: <?php echo $item['date'] ?></small></p>
					</article>
				<?php endforeach  ?>
			<?php endif ?>
		</section>
	</div>
<?php get_footer() ?>