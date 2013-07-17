<?php

// Some simple html methods
class HTML{
	
	// Create a stylesheet link
	public static function enqueue_style($href, $dependancies=false, $version=false, $media="screen")
	{	
		preg_match("/[a-zA-Z0-9_-]+.css$/", $href, $matches);
		$name = str_replace('.', '', (substr($matches[0],0 ,-4) ) );
		wp_register_style($name, self::get_path($href), $dependancies, $version, $media);
		
		if( gettype($dependancies) == "string" && strpos($dependancies, "IE") !== false ){
			global $wp_styles;
			$wp_styles->add_data($name, 'conditional', $dependancies);
		}

		wp_enqueue_style($name);
	}

	// Create a script with src
	public static function enqueue_script($href, $dependancies=false, $version=false, $in_footer=false)
	{
		preg_match("/[a-zA-Z0-9_.-]+.js$/", $href, $matches);
		$name = str_replace('.', '-', (substr($matches[0],0 ,-3) ) );
		wp_register_script($name, self::get_path($href), $dependancies, $version, $in_footer);
		wp_enqueue_script($name);
	}

	// Create a favicon link with optional touch icon
	public static function favicon($favicon, $touch=false)
	{
		$html = '<link rel="shortcut icon" href="'. self::get_path($favicon) .'" />' . "\n";
		if($touch){
			$html .= '<link rel="apple-touch-icon" href="'. self::get_path($touch) .'" />' . "\n";
		}
		return $html;
	}

	// Work out if we're referencing the theme root or the actual root of the site
	// If the path starts with / we're referencing the site root
	// Otherwise we're referencing the theme root
	private static function get_path($file)
	{
		if( strpos($file, '/') !== 0 ){
			return get_template_directory_uri() . "/" . $file;
		} else {
			return $file;
		}
	}

}