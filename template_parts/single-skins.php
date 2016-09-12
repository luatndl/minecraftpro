<?php ?>
<article>
<h1><?php the_title()?></h1>
	<div style="width:auto; height:90px; border:1px solid red;text-align:center;margin:10px 15px 10px 0px"> Ads here</div>

<div class="col-sm-6 col-md-6">
<?php
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
?>
<img class="img-responsive lazy" src="<?php echo $thumb['0'];?>" width="388" height="278" alt="<?php the_title(). ' skin';?>"/>
	<p><strong>How to install <?php the_title();?>:</strong></p>

	<p><strong>1. Download <?php the_title()?> skin</strong></p>
	<p>Minecraft skins are very small images in PNG format. The uninstalled skins look like unassembled paper dolls.</p>
	<p>Here is an example:</p>
	<p><img src="http://minecraft-mods.info/wp-content/uploads/Alex skin.png" width="100" alt="Alex skin"/></p>
</div>
<div class="col-sm-6 col-md-6">
	<div class="social"></div>
     <div class="cl"></div>
     <div class="skins-info">
  			Simple guy wearing a blue t-shirt and blue snapback.Designed by SyNaxxx
  			<div class="controls"><div class="col-xs-24"><a class="btn btn-success btn-block" href="http://minecraft-mods.info/download.php?act=view&amp;id=69246" target="_blank">Download</a></div><div class="col-xs-24"><a class="btn btn-warning btn-block" href="http://www.minecraft.net/profile/skin/remote/?url=http://minecraft-mods.info/wp-content/uploads/2016/09/BlueGuy-skin.png" target="_blank" rel="nofollow">Change</a></div></div>
     </div>
     <div class="ads">
     	<div style="width:auto; height:280px; border:1px solid red;text-align:center;margin:0px 15px 10px 0px"> Ads here</div>
     </div>
</div>
<div class="primary-content">
<br/>
	<p>You can go to category skin to find & download skins. It doesn't matter where you save your skins, as long as you remember the name of the folder. Also, if you change file names, it'll make it easier to organize your collection.</p>
	<p><strong>2. Login to Minecraft.net</strong></p>

	<p>Once you have your desired skin, go to your profile on the official Minecraft website. Click on Profile and log in with your Mojang account (the username is the email you used to register).</p>
	<p><img class="img-responsive" src="http://minecraft-mods.info/wp-content/uploads/userpass-mc.jpg" width="568" alt="official Minecraft website"/></p>

	<p><strong>3. Upload your skin from the Profile page</strong></p>

	<p>Once you're on the profile page, click the Browse button and browse through the folders until you find your skin. After you've selected the skin, press Upload and wait for the confirmation message.</p>
	<p><img class="img-responsive" src="http://minecraft-mods.info/wp-content/uploads/upload-skin-mc.jpg" width="568" alt="official Minecraft website"/></p>
	<p><strong>4. Enter Minecraft and try out your skin</strong></p>

	<p>Now, all you need to do is enter Minecraft; if you're already in the game, leave and then enter again. After that, just load up a world (it doesn't matter which) and press F5 to see your new skin. Quite a change, don't you think?</p>
</div>
</article>
<?php require_once 'ads-related-posts.php';?>
