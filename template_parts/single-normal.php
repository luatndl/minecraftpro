<?php 
function clean($string) {
	$string = preg_replace('/[^A-Za-z\- ]/', '', $string);// Replaces all spaces with hyphens.
	return ltrim($string," ");
}
$title = clean(get_the_title());
$sz_Versions= getGameVersion(get_the_ID(),true);
$latestversion = explode(',',$sz_Versions);
$extrakeywords = '[EXTKW] [VERSION] Download | How To Install [EXTKW] | [EXTKW] + tutorial installation | How to download and install [EXTKW] | mods for minecraft [VERSION] | minecraft mods [VERSION] download | Minecraft [EXTKW] [VERSION] | Minecraft [VERSION] [EXTKW]';
?>
<article>
	<h1><?php the_title()?></h1>
	<div style="width:auto; height:90px; border:1px solid red;text-align:center;margin:10px 15px 10px 0px"> Ads here</div>
	<div class="des"><?php echo get_post_meta(get_the_ID(),'_su_description',true)?></div>
	<div class="col-sm-6 col-md-6">
	<div style="width:auto; height:280px; border:1px solid red;text-align:center;margin:0px 15px 10px 0px"> Ads here</div>
	</div>
	<div class="col-sm-6 col-md-6">
		<?php if ( function_exists( 'sharing_display' ) ) {?>
			<div class="social">
				<?php sharing_display( '', true );?>
			</div>
		<?php }?>
		<div class="authors"><strong>Author:</strong> <?php echo getAuthor(get_the_ID())?></div>
		<ul class="details-list">
	                        <li class="game"><span class="glyphicon glyphicon-tags"><a href="<?php echo 'http://minecraft-mods.info/'.$a_Cats[0]->slugs ;?>"><?php echo $a_Cats[0]->name;?></a></span></li>
							<!-- <li class="average-downloads">406 Monthly Downloads</li> -->
	                        <li class="updated"><span class="glyphicon glyphicon-time"><span>Updated <?php the_modified_date('F j, Y');?></span></span></li>
	                        <li class="comments"><span class="glyphicon glyphicon-comment"><span><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span></span></li>
							<!-- <li class="downloads">9,241 Total Downloads</li> -->
							<!--<li class="favorited">0 Favorites</li> -->
							<!-- <li class="curseforge"><a href="//www.curseforge.com/projects/240640/">Project Site</a></li> -->
							<li class="newest-file"><span class="glyphicon glyphicon-certificate"><span><?php echo $aDownloadFiles->newest_file?></span></span></li>
	                        
	                        <li class="release"><span class="glyphicon glyphicon-refresh"><span>Release Type: Release</span></span></li>
	                        <li class="version-out-of-date"><span class="glyphicon glyphicon-globe"><span>Supports: <?php echo $sz_Versions;?></span></span></li>
	                        <li class="license"><span class="glyphicon glyphicon-registration-mark"><span>License: All Rights Reserved</span></span></li>
	     </ul>
	     <div class="cl"></div>
	     <div class="alert alert-info">
	  			<strong>Info!</strong> All files download are getting from server of <b>Curse.com</b> pattner through API. It's always latest and safe(no virus).
	     </div>
	</div>
	<div class="col-sm-12 col-md-12">
	     <?php the_content();?>
	</div>
	<?php if($a_Cats[0]->cat_ID == 1){?>
		<div class="cl"></div>
		<div class="extra-keywords">
			<strong>Extra Keywords:</strong>
			<?php echo str_replace(array("[EXTKW]","[VERSION]"), array($title,$latestversion[0]), $extrakeywords); ?>
		</div>
	<?php }?>
</article>
<?php require_once 'ads-related-posts.php';?>
