<div class="row group-category"><h2><a class="name_box" href="<?php echo get_category_link( $cat_item->term_id )?>" title="View all post in <?php echo $cat_item->name;?>"><?php echo $cat_item->name;?></a></h2></div>
<?php 
	$per_page=2;
	if ( $cat_id == 1)
	{
		$per_page = 5;
	}
		
	if ($cat_id == 41 )
	{
		$per_page = 3;
	}
	query_posts("cat=$cat_id&posts_per_page=$per_page&orderby=modified");
	if ( have_posts() ) {
		$iIndex = 0;
		while ( have_posts() ) { the_post();
?>
<div class="row article">
	<div class="col-md-7 thumbnail">
	<?php
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
	?>
		<img class="img-responsive" src="<?php echo $thumb['0'];?>" width="388" height="278" alt="<?php the_title(). ' skin';?>"/>
	</div>
	<div class="col-md-5">
		<div class="row">
			<h2 class="nopadding"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p>
				<?php
					$content = get_the_content();
					echo str_replace(array('Download','Change'), '', strip_tags($content,'<strong>'));
				?>
			</p>
		</div>
	</div>
</div>
<?php } //end if 
	}//end while?>