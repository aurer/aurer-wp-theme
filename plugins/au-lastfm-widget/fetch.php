<?php

$source = strtolower($_GET['source']);
if($source != 'recenttracks' && $source != 'lovedtracks'){
	$source = 'recenttracks';
}
$user = $_GET['user'];
$key = isset($_GET['key']) ? $_GET['key'] : "ccfce33b35f8480c2413f2a642fa2c6a";

$xml = @simplexml_load_file("http://ws.audioscrobbler.com/2.0/?method=user.get$source&user=$user&api_key=$key");
		
if(!$xml){
	echo 0;
	return;
}

$tracks = array();
foreach ( $xml->$source->track as $key => $value) {
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

echo json_encode( array("items"=>$tracks) );