<?php
/*
Plugin Name: WP Img Slider
Plugin URI:  http://www.grupomayanguides.com/wp-image-slider/
Description: Let you create images gallerys and insert them in your post
Author: Adam Jones
Version: 1.0
Author URI: http://wwww.grupomayanguides.com/
*/


// *********** PARSER *********** //

register_activation_hook(__FILE__,'wpimageslider_install');

$wpimageslider_table_name   = "imgslider_galery";
$wpimageslider_table_images = "imgslider_images";

function wpimageslider_install () {
   global $wpdb;
   
   $wpimageslider_table_name   = "imgslider_galery";
   $wpimageslider_table_images = "imgslider_images";
	
   // **** GALERY'S TABLE *****   
   $table_name = $wpdb->prefix . $wpimageslider_table_name;
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
	  id_galery mediumint(9) NOT NULL AUTO_INCREMENT,
	  galery_title TEXT NOT NULL,
	  galery_width  varchar(5) NOT NULL,
	  galery_height varchar(5) NOT NULL,
	  galery_align   varchar(5) NOT NULL,
	  UNIQUE KEY id_galery (id_galery)
	);";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
   };
   
	// **** IMAGE'S TABLE *****   
   $table_name = $wpdb->prefix . $wpimageslider_table_images;
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
	  id_imggal mediumint(9) NOT NULL AUTO_INCREMENT,
      id_image  mediumint(9) NOT NULL,
	  id_galery mediumint(9) NOT NULL,
	  UNIQUE KEY id_imggal (id_imggal)
	);";

      
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
	$wpdb->query($query);
	
	 // update_option("wpimageslider_timeinterval", "3");
	 // update_option("wpimageslider_dateformat",   "m/d/Y");
	 // update_option("wpimageslider_linktext", "read more");
			   		 
   }
}


function wpimageslider_parsegalleries($contenido_post)
{	
	
	global $wpdb;
	global $wpimageslider_table_name;
	global $wpimageslider_table_images;
	preg_match_all('/\[(.*?)getmygallery(.*?)id(.*?)=(.*?)\((.*?)\)(.*?)\]/', $contenido_post, $GalleriesInPost);
	
	
	foreach ($GalleriesInPost[0] as $gallery)
	{
		$pcomilla = strpos ($gallery,'(');
		$ucomilla = strrpos($gallery,')');
		$id = substr($gallery,($pcomilla+1),($ucomilla-1) - ($pcomilla));
		
		
		$objGallery = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix .  $wpimageslider_table_name . 
							             " WHERE id_galery = '$id'");
		
		
		if ($objGallery[0])
		{	
			$gal_align  = $objGallery[0]->galery_align;
			$gal_width  = $objGallery[0]->galery_width;
			$gal_height = $objGallery[0]->galery_height;
			
			$img_width  = $gal_width - 20;
			$img_height = $gal_height -20;
			
			$strDivGal = '<div style="float:' . $gal_align .'; margin: 8px 8px 8px 8px; "><div class="slider" style="height:' 
							. $gal_height .'px; width:' . $gal_width .'px; margin-bottom:0px;"> ' .
				    		'<div class="slidercontent" id="imgslider' . $id .'">';

			
			$images = $wpdb->get_results("SELECT guid FROM "  . $wpdb->prefix .  $wpimageslider_table_images .
											" INNER JOIN "  . $wpdb->posts . " ON " . 
												$wpdb->posts . ".id = " . $wpdb->prefix . $wpimageslider_table_images .
												".id_image " .
							             " WHERE id_galery = '$id'");
			$imgIndex = 1;	
			$strLink = "";
			foreach ($images as $image)
			{
				$strDivGal .= '<div id="imgsection' . $id .'-' . $imgIndex .'" class="section upper">' .
        					  ' <img src="' . $image->guid . '"  height="' 
								. $img_height . 'px" width="' . $img_width .'px" alt="" /> ' .
				      		  '</div>';
				$strLink .= '<a class="link" href="javascript:slideContent(' . 
								"'" . "imgsection" . $id .'-' . $imgIndex . "'" . ')">' .
								$imgIndex .
							'&nbsp;</a>'; 
				$imgIndex++;
			} 
      
     		$strDivGal .=  '</div>' .  
  						'</div> <span class="link">Go to image&nbsp;&nbsp;</span>' 
     					. $strLink  . 
     					'<a href="http://www.grupomayanvacations.com" target="_TOP" title="grupo mayan">*</a>' . 
  					'</div>' . "\n"; 

     
			$contenido_post = str_replace($gallery,$strDivGal,$contenido_post);
		}
		
	}; 
	
	return $contenido_post;
}


function wpimageslider_setfiles()
{
	 echo '<script type="text/javascript" src="' . get_option("siteurl") . '/wp-content/plugins/' 
			. basename(dirname(__FILE__))  . '/wp-imgslider.js">' .
		 '</script>';

	 echo '<link rel="stylesheet" type="text/css" href="' . get_option("siteurl") . '/wp-content/plugins/' .
		        basename(dirname(__FILE__)) . '/wp-imgslider.css" />';
	
}


include ("wp-imgslider-list.php");
include ("wp-imgslider-addedit.php");

function wpimageslider_add_pages() {
    
	// Add a new submenu under Options:
		
	 if ($_REQUEST["wpimageslider_id_galery"] || $_REQUEST["wpimageslider_addnew"] )
   		add_options_page('WP Image Slider', 'WP News Slider', 8, 'wpimageslider', 'wpimageslider_addedit_page');
     else 
		add_options_page('WP Image Slider', 'WP Image Slider', 8, 'wpimageslider', 'wpimageslider_list_page');
}

add_filter("the_content", "wpimageslider_parsegalleries");
add_action('admin_menu',  "wpimageslider_add_pages");
add_action("wp_head",     "wpimageslider_setfiles");


?>