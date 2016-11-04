<?php
?>
<?php if ( $cat_id == 1){
	$per_page = 5;
?>
<div class="row group-category"><h1><a class="name_box" href="<?php echo get_category_link( $cat_item->term_id )?>" title="View all post in <?php echo $cat_item->name;?>"><?php echo $cat_item->name;?></a></h1></div>
<?php 
	query_posts("cat=$cat_id&posts_per_page=$per_page&orderby=modified");
	if ( have_posts() ) {
		while ( have_posts() ) { the_post();
?>
<div class="row article">
	<div class="col-sm-3 col-md-3 thumbnail"><img src="http://localhost/minecraft-mods.info/wp-content/uploads/2016/05/surgeon.png"><?php //the_post_thumbnail('thumbnail'); ?></div>
	<div class="col-sm-9 col-md-9">
		<div class="row">
			<div class="col-sm-9 col-md-9">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p>
					<?php
						$content = get_the_content();
						$trimmed_content = wp_trim_words( $content, 30);
						echo $trimmed_content;
					?>
				</p>
				<div class="versions"><?php echo getGameVersion(get_the_ID())?></div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="row row-info">
					<div class="glyphicon glyphicon-eye-open"> <b class="stat"><?php echo $v= getPostViews(get_the_ID());?></b></div>
					<div class="glyphicon glyphicon-download-alt"> <b class="stat"><?php $d = getDownloaded(get_the_ID()); echo ((($v > $d) && $d ==0 ) ? $v: $d) ;?></b></div>
					<div class="glyphicon glyphicon-user"><span><?php echo getAuthor(get_the_ID())?></span></div>
				</div>
				
			</div>
			
		</div>
	</div>
</div>
<?php } //end if 
	}//end while?>
<?php }else { ?>
<div class="row group-category"><h2><a class="name_box" href="<?php echo get_category_link( $cat_item->term_id )?>" title="View all post in <?php echo $cat_item->name;?>"><?php echo $cat_item->name;?></a></h2></div>
<div class="row article">
<?php 
	$per_page=2;
	query_posts("cat=$cat_id&posts_per_page=$per_page&orderby=modified");
	if ( have_posts() ) {
		while ( have_posts() ) { the_post();
?>
<div class="col-sm-6 col-md-6">
	<div class="col-sm-4 col-md-4 thumbnail"><img src="http://localhost/minecraft-mods.info/wp-content/uploads/2016/05/surgeon.png"><?php //the_post_thumbnail('thumbnail'); ?>
		<div class="versions"><?php echo getGameVersion(get_the_ID())?></div>
		<div class="col-sm-12 col-md-12 glyphicon glyphicon-user"><?php echo getAuthor(get_the_ID())?></div>
	</div>
	<div class="col-sm-8 col-md-8">
		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
		<p>
			<?php
				$content = get_the_content();
				$trimmed_content = wp_trim_words( $content, 30);
				echo $trimmed_content;
			?>
		</p>
	</div>
</div>
<?php } //end if 
	}//end while?>
</div>
<?php }?>


