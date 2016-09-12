<?php
/**
 * Minecraft Pro template
 *
 * @package WordPress
 * @subpackage Wp_Bootstrap
 * @since Wp Bootstrap 1.0
 *
 */
global $aDownloadFiles;
?>
<?php get_header()?>
<body>
	<?php require_once 'nav.php';?>
	<div class="container">
		<div class="row">
			<?php include_once 'breadcrumb.php';?>
			<div class="main">
				<div class="col-md-8">
					<?php 	if ( have_posts() ) {
						while ( have_posts() ) { the_post();
							setPostViews(get_the_ID());
							$a_Cats = get_the_category( get_the_ID());
							$versions = getGameVersion(get_the_ID());
							global $wpdb;
							
							$aDownloadFiles = $wpdb->get_row(
									$wpdb->prepare(
											"SELECT listing_files,newest_file FROM wp_downloads
												WHERE post_id = %d
												",get_the_ID()));
							?>
						<?php 
							if($a_Cats[0]->cat_ID !=3)
							{
								require_once 'template_parts/single-normal.php';
							}else{
								require_once 'template_parts/single-skins.php';
							}
						?>
					<?php comments_template(); ?>
					<?php }//end if 
					}//end while ?>
					
				</div>
				<div class="col-md-4 sidebar">
					<?php require_once 'sidebar.php';?>
				</div>
			</div>
		 </div>	
    </div> <!-- /container -->
<?php get_footer();?>