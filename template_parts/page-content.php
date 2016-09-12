<?php if ( have_posts() ){ while ( have_posts() ) { the_post(); ?>
<div class="row group-category"> 
		<?php if (!is_front_page()) :?>
			<h1><p><?php the_title();?></p></h1>
		<?php endif;?>
</div>
<div class="row article">
	<?php the_content();?>
</div>
<?php } //end if 
	}//end while?>

