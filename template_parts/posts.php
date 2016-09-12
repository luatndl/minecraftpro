<?php
?>
<?php if ( $cat_id == 1){?>
<div class="row group-category"><h1><a class="name_box" href="<?php echo get_category_link( $cat_item->term_id )?>" title="View all post in <?php echo $cat_item->name;?>"><?php echo $cat_item->name;?></a></h1></div>
<?php }else { ?>
<div class="row group-category"><h2><a class="name_box" href="<?php echo get_category_link( $cat_item->term_id )?>" title="View all post in <?php echo $cat_item->name;?>"><?php echo $cat_item->name;?></a></h2></div>
<?php }?>

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
		while ( have_posts() ) { the_post();
?>
<div class="row article">
	<div class="col-md-3 thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<div class="col-md-9">
		<div class="row">
			<h2 class="nopadding"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p>
				<?php
					$content = get_the_content();
					$trimmed_content = wp_trim_words( $content, 30);
					echo $trimmed_content;
				?>
			</p>
			<div class="versions"><?php echo getGameVersion(get_the_ID())?></div>
			<div class="col-md-4"><span class="glyphicon glyphicon-eye-open"> <b class="stat"><?php echo $v= getPostViews(get_the_ID());?></b></span> views</div>
			<div class="col-md-4"><span class="glyphicon glyphicon-download-alt"> <b class="stat"><?php $d = getDownloaded(get_the_ID()); echo ((($v > $d) && $d ==0 ) ? $v: $d) ;?></b></span> downloads</div>
			<div class="col-md-4">Author: <?php echo getAuthor(get_the_ID())?></div>
		</div>
	</div>
</div>
<?php } //end if 
	}//end while?>
