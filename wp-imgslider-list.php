<?php

function wpimageslider_list_page()
	{
			
	  global $wpdb;
	  global $wpimageslider_table_name;
	  global $wpimageslider_table_images;
?>
<div class="wrap">

<script type="text/javascript">
	function delete_galery(idmessage, title)
	{
		if (confirm("Are you sure you want to delete the galery " +  title + " ?\n" + 
					" (the images will not be deleted) "))
		{
			document.forms["wpimageslider_listform"].wpimageslider_gallerytodelete.value = idmessage;
			document.forms["wpimageslider_listform"].wpimageslider_action.value = "delete";
			document.forms["wpimageslider_listform"].submit();
		}
	}
</script>
<?php 
	if ( $_POST["wpimageslider_action"] == "delete" )
	{
		$wpdb->query("DELETE FROM " . $wpdb->prefix .  $wpimageslider_table_images .
					 " WHERE id_galery = " . $_POST["wpimageslider_gallerytodelete"] );	
			
		$wpdb->query("DELETE FROM " . $wpdb->prefix .  $wpimageslider_table_name .
					 " WHERE id_galery = " . $_POST["wpimageslider_gallerytodelete"] );	
	}
	
?>
<h2>WordPress Image Slider</h2>
<form name="wpimageslider_listform" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"
	  method="post" >
	<input type="hidden" name="wpimageslider_gallerytodelete" value="">
	<input type="hidden" name="wpimageslider_action" value="">
	
	<?
		$query = "SELECT count(*) " . 
	     	              " FROM $wpdb->posts " . 
	     	             " WHERE post_mime_type like '%image%' ";

		if ($wpdb->get_var( $query ))
		{
	?>			   
	<p class="submit">
		<input type="button" value="Add New Gallery" 
			   onclick="document.location='options-general.php?page=wpimageslider&wpimageslider_addnew=ok'">
		<br>
	</p>
	<? } else { ?>
		<p>
		<i><b>Sorry, you dont have any image yet.</b></i><br>
			This plug-in uses the images that you have added in <b>the media section</b> of your Word Press blog.<br>
			For add a new image slider gallery, you must upload some images first.
		</p> 
	<? }?>
</form>		
<br>
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col" class="manage-column column-name" style="">Gallery Title</th>
	<th scope="col" class="manage-column column-name" style="">For use, copy & paste this text in your post</th>
	<th scope="col" class="manage-column column-email" style="">&nbsp;</th>
	<th scope="col" class="manage-column column-email" style="">&nbsp;</th>
</tr>
</thead>

<tbody id="users" class="list:user user-list">
<?php
	
	$query = " SELECT *  FROM " .
			  $wpdb->prefix . $wpimageslider_table_name .
			  " ORDER BY galery_title ";
			  
	$myGaleries = $wpdb->get_results($query);
	
	foreach ($myGaleries as $gallery)
	{
 ?>
        <tr id='user-1' class="alternate">
			<td class="username column-username">
				<?php echo $gallery->galery_title ?>
		    </td>
		    <td>	
		   		<span class="stuffbox.inside">
		   			
				   		<input style="background: lightblue; text-align:center;" type="text" size="30" 
				   			   value='[getmygallery  id=(<?php echo $gallery->id_galery ?>)]'>
			   	    
		   	    </span>
		   </td>
		   <td class="username column-username">
				<a href="options-general.php?page=wpimageslider&wpimageslider_id_galery=<?php echo $gallery->id_galery; ?>">
				Edit Galery</a>
		   </td>
		   <td class="username column-username">
				<a href="javascript:delete_galery(<?php echo $gallery->id_galery ?>,'<?php echo $gallery->galery_title ?>');">
				Delete Galery</a>
		   </td>		
		</tr>
        
        <?
    }

?>
	
    </tbody>
</table>
<?php 
	}

?>