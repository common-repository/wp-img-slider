<?php
function wpslider_addimages($galeryID)
{
	global $wpdb;	
	$query = "SELECT guid,post_title,id " . 
	     	  " FROM $wpdb->posts " . 
	     	 " WHERE post_mime_type like '%image%' ORDER BY post_date DESC";

	$result = $wpdb->get_results( $query );
	
	$images = "";
	$i = 0;
	foreach ($result as $row) 
	{	
		if ($_POST["wpslider_image" . $row->id])
		{
			$images .= "(" . $row->id .",$galeryID),";
			$i++;
		}
	}
	
	global $wpimageslider_table_name;
	global $wpimageslider_table_images;
	
	if ($i > 0)
	{	
		$images = substr($images,0,strlen($images)-1);
		
		$wpdb->query("DELETE FROM " . $wpdb->prefix . $wpimageslider_table_images .
					  " WHERE id_galery = $galeryID");
		
		$query = "INSERT INTO " . $wpdb->prefix . $wpimageslider_table_images 
					. " ( id_image, id_galery )" .
						"VALUES " . $images;
		
						
		$wpdb->query($query);
	}
			
	
}
?>