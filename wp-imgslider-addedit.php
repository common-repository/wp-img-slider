<?php
 include_once("wp-imgslider-images.php");
 
function wpimageslider_addedit_page() {
	global $wpdb;
	global $wpimageslider_table_name;
    global $wpimageslider_table_images;
    
	// *** Post-It Info
	$id_galery     = $_REQUEST["wpimageslider_id_galery"];
	$galery_title  = "";
	$galery_width  = "350";
	$galery_height = "150";
	$galery_align  = "none";
	
	$galery_align_left  = "";
	$galery_align_right = "";
	$galery_align_none  = "";
	
	// *******  SAVE POST-IT ********
	if ( !($_POST["wpimgslider_submit"] == "ok") )
	{
		if ($id_galery)
		{   $query = "SELECT * FROM " . $wpdb->prefix . $wpimageslider_table_name .  
							 			     " WHERE id_galery = $id_galery ";
			
		    $galeryInfo = $wpdb->get_results($query);
			
			$galery_title  = $galeryInfo[0]->galery_title;
			$galery_width  = $galeryInfo[0]->galery_width;
			$galery_height = $galeryInfo[0]->galery_height;
			$galery_align  = $galeryInfo[0]->galery_align;
			
			if ($galery_align == "left")
			{
			   $galery_align_left = "selected";
			  
			}
			else if ($galery_align == "right")
			{
			  $galery_align_right = "selected";
			  
			}
									
		}
			
		
	}
	else
	{	  
			
			
		   // *** Posted Data
		   $galery_title    = str_replace("\'"," ",$_POST["wpimgslider_galery_title"]);
		   $galery_width    = str_replace("\'"," ",$_POST["wpimgslider_galery_width"]);
		   $galery_height   = str_replace("\'"," ",$_POST["wpimgslider_galery_height"]);
		   $galery_align   = str_replace("\'"," ",$_POST["wpimgslider_galery_align"]);
		   
			if ($galery_align == "left")
			{
			   $galery_align_left = "selected";
			  
			}
			else if ($galery_align == "right")
			{
			  $galery_align_right = "selected";
			  
			}
			
		   if ($id_galery)
		   {
			    	 $query = " UPDATE " . $wpdb->prefix . $wpimageslider_table_name .  
				   				  " SET galery_title   = '$galery_title',  " .
			    	 				  " galery_width   = '$galery_width',  " .
			    	 				  " galery_height  = '$galery_height', " .
			    	 				  " galery_align   = '$galery_align'   " . 
			    	 		 " WHERE id_galery = $id_galery ";
			    	 
			    	$wpdb->query($query);
					wpslider_addimages  ($id_galery);  	
		   }
		   else
		   {
				    $query = "INSERT INTO " . $wpdb->prefix . $wpimageslider_table_name  .  
				   					 "( galery_title, galery_width, galery_height, galery_align  )  " . 
				   				"VALUES ('$galery_title', '$galery_width', '$galery_height', '$galery_align' ) "; 
			    
			     	 
			   		 $wpdb->query($query);
			   		 
			   		 
			    	 $lastID = $wpdb->get_results("SELECT MAX(id_galery) as lastid_galery " .
			    		  						   " FROM " . $wpdb->prefix . $wpimageslider_table_name .
			    							 	  " WHERE galery_title = '$galery_title'");
			    	 
			    	 $id_galery = $lastID[0]->lastid_galery;
			    	 wpslider_addimages  ($id_galery);
		  }
			    
	}
	 

?>
<div class="wrap">
<script type="text/javascript">
	function validateInfo(forma)
	{
		if (forma.wpimgslider_galery_title.value == "")
		{
			alert("You must type a title");
			forma.wpimgslider_galery_title.focus();
			return false;
		}
		
				
		
	return true;
}
</script>

<form name="wpimgslider_form" method="post" onsubmit="return validateInfo(this);" 
	  action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	  

<?php
    // Now display the options editing screen

    // header
	if ($id_galery)
    	echo "<h2>" . __( 'Edit Galery',    'mt_trans_domain' ) . "</h2>";
    else
       	echo "<h2>" . __( 'Add New Galery', 'mt_trans_domain' ) . "</h2>";

    // options form
    
 ?>
    <?php if ( $_POST["wpimgslider_submit"] == "ok" ) { ?>
    <div class="updated"><p><strong><?php _e('Gallery information saved.', 'mt_trans_domain' ); ?></strong></p></div><br>	
    <? }; ?>

 	
 	<span class="stuffbox" >
 		
		 
	     <br>
	     
	     Galery Name
		 <span class="inside">
		 	<input type="text" size="30" maxlength="50" id="wpimgslider_galery_title" name="wpimgslider_galery_title" 
		 	           value="<?php echo $galery_title ?>">
	     </span>&nbsp;&nbsp;&nbsp;&nbsp;
	     
	     Width
		 <span class="inside">	
		 	<input type="text" size="5" maxlength="3" id="wpimgslider_galery_width" name="wpimgslider_galery_width" 
		 	           value="<?php echo $galery_width ?>">px
	     </span>&nbsp;&nbsp;
	     
	     Height
		 <span class="inside">	
		 	<input type="text" size="5" maxlength="3" id="wpimgslider_galery_height" name="wpimgslider_galery_height" 
		 	           value="<?php echo $galery_height ?>">px
	     </span>&nbsp;&nbsp;<br><br>
	     
	     Alignment
		 	<select name="wpimgslider_galery_align" id="wpimgslider_galery_align">
		 		<option value="none"  <?php echo $galery_align_none  ?>>None</option>
		 		<option value="right" <?php echo $galery_align_right ?>>Right</option>
		 		<option value="left"  <?php echo $galery_align_left  ?>>Left</option>
		 	</select>
	     
	     <br>
	     
	     <?php
	     	if ($id_galery)
	     	{
	     ?>
	        For use, copy & paste this text in your post		
	     	<span class="stuffbox.inside">
		   			
				   		<input style="background: lightblue; text-align:center;" type="text" size="30" 
				   			   value='[getmygallery  id=(<?php echo $id_galery ?>)]'>
			   	    
		   	 </span>
	     <?php		
	     	}
	      ?><br><br>
	      <b>Select the images that you want to include in your gallery</b>
	     <table cellspacing="10">
	     <tr>
	     <?php
	     	$img_count = 0;
	     	$img_per_row = 10;
	     	$query = "SELECT guid,post_title,id " . 
	     	              " FROM $wpdb->posts " . 
	     	             " WHERE post_mime_type like '%image%' ORDER BY post_title";

			$result = $wpdb->get_results( $query );
			
			foreach ($result as $row) 
			{	
				if ($img_count == $img_per_row )
				{
					echo "</tr><tr>";	
					$img_count = 0;
				}
				$cheked = "";
				if ($id_galery)
				{
					if ($wpdb->get_var("SELECT COUNT(*) " . 
										" FROM " . $wpdb->prefix . $wpimageslider_table_images .
										" WHERE id_galery = " .  $id_galery . 
										  " AND id_image  = " . $row->id ))
					$cheked = "checked";
				};
				
				echo '<td><img src="' . $row->guid . '" width="50" height="50"><br>' .
					 '<p style="font-size:10px; text-align:center">' .
					 '<input type="checkbox" name="wpslider_image' . $row->id . '" value="' . $row->id .'" ' . $cheked .'>' .
				    $row->post_title .'</p></td>';
				    
				$img_count++;
			}
		?>
		</tr>
		</table>
 	</span>
 

<p class="submit">
	<input type="hidden" name="wpimgslider_submit" value="ok">
	<input type="hidden" name="wpimgslider_id_message" value="<?php echo $id_galery ?>">
	<input type="submit" name="Submit" value="<?php _e('Save Gallery Information', 'mt_trans_domain' ) ?>" />&nbsp;
	<input type="button" name="Return" value="<?php _e('Return to Gallery List', 'mt_trans_domain' ) ?>"
		   onclick="document.location='options-general.php?page=wpimageslider' " />
</p>

</form>

</div> <!-- **** DIV WRAPPER *** -->

<?php } ?>