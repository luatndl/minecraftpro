<?php
/**
 * The main template file.
 *
 * This is  template file in a crazyminecraft.net
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://crazyminecraft.net
 *
 * @package WordPress
 * @subpackage crazyminecraft.net
 * @since crazyminecraft.net 1.0
 */
?>
<?php
global $query_string;
$iIndex =0;
$type= $_GET['t'];
switch ($type)
{
	case 'create':
		query_posts($query_string . '&orderby=date&order=DESC');
		break;
	case 'hot':
	case 'update':
	default:
		query_posts($query_string . '&orderby=modified');
		break;
	
}
	if ( have_posts() ) {
		while ( have_posts() ) { the_post();
		
?>
	<article>
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
	</article>
<?php if($iIndex == 2) {?>
<div class="ads"></div>
<?php } //end if ads
	$iIndex ++;
}//end while
?>
	<?php
	 if(function_exists('wp_paginate')) {
	 	echo '<div class="col-sm-12 col-md-12">';
	    wp_paginate();
	    echo '</div>';
	}?>
<?php 
	} //end if post
?>