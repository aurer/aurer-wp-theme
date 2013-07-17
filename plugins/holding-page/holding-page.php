<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Aurer.co.uk under reconstruction</title>
<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Merriweather);
	body,html{
		height: 100%;
	}
	body{
		background: #333;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAKElEQVQIW2NkYGD4b2xszHD27FkGEM0IJP7DOEBJBkasKkAyIABSCQBzhRHMKI3GLAAAAABJRU5ErkJggg==);
		padding: 0;
		margin: 0;
		font-family: Helvetica, sans-serif;
	}
	#outer {
		width: 100%;
		height: 100%;
		margin: 0 auto;
		display: table;
	}
	
	#inner {
		display: table-cell;
		vertical-align: middle;
		-webkit-perspective: 1000px;
		-moz-perspective: 1000px;
		-o-perspective: 1000px;
		-ms-perspective: 1000px;
		perspective: 1000px;
	}
	
	#card {
		width: 400px;
		height: 509px;
		margin: -40px auto 0;
		position: relative;
	
		-webkit-transform-style: preserve-3d;
		-moz-transform-style: preserve-3d;
		-ms-transform-style: preserve-3d;
		transform-style: preserve-3d;
	
		-webkit-transition: -webkit-transform 1s;
		-moz-transition: -moz-transform 1s;
		-o-transition: -o-transform 1s;
		-ms-transition: -ms-transform 1s;
		transition: transform 1s;
		cursor: pointer;
	}
	
	.card {
		background: #161616;
		background: #111;
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		margin: 0;
		-webkit-backface-visibility: hidden;
		-moz-backface-visibility: hidden;
		-o-backface-visibility: hidden;
		-ms-backface-visibility: hidden;
		backface-visibility: hidden;
	
		-webkit-transition: -webkit-transform .8s cubic-bezier(0.46, .12, .7, 1.35);
		-moz-transition: -moz-transform .8s cubic-bezier(0.46, .12, .7, 1.35);
		-o-transition: -o-transform .8s cubic-bezier(0.46, .12, .7, 1.35);
		-ms-transition: -ms-transform .8s cubic-bezier(0.46, .12, .7, 1.35);
		transition: transform .8s cubic-bezier(0.46, .12, .7, 1.35);
	
		-webkit-box-shadow: 0 2px 10px rgba(0,0,0,0.4);
		-moz-box-shadow: 0 2px 10px rgba(0,0,0,0.4);
		box-shadow: 0 2px 10px rgba(0,0,0,0.4);
		text-align: center;
		color: #eee;
	}
	
	.back {
		-webkit-transform: rotateY(180deg);
		-moz-transform: rotateY(180deg);
		-o-transform: rotateY(180deg);
		-ms-transform: rotateY(180deg);
		transform: rotateY(180deg);
		background: none;
		box-shadow: none;
	}
	
	#card.flip {
		-webkit-transform: rotateY(180deg);
		//-moz-transform: rotateY(180deg); // Firefox transform is misbehaving :(
		-o-transform: rotateY(180deg);
		-ms-transform: rotateY(180deg);
		transform: rotateY(180deg);
	}

	.card p {
		font-size: .8em;
		color: #999;
	}

	.back img{
		margin: 140px 0 20px;
	}

	p.statement{
		font-size: 1.2em;
		font-family: "Merriweather", Helvetica, sans-serif;
	}
</style>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
	jQuery(function($){
		$("#card").click(function(){
			$(this).toggleClass("flip");
		});
	});
</script>
</head>
<body>
	<div id="outer">
		<div id="inner">
			<div id="card">
				<div class="card back">
					<h1><img src="<?php echo get_template_directory_uri(); ?>/plugins/holding-page/aurer.png" alt="Aurer -  under development"></h1>
					<p class="statement">I'm moving the site to a new system,</p>
					<p class="statement">It should be ready in a few weeks.</p>
				</div>
				<div class="card front"><img src="<?php echo get_template_directory_uri(); ?>/plugins/holding-page/front.png" /></div>
			</div>
		</div>
	</div>
</body>
</html>