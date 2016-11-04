<?php
/**
 * Minecraft Pro template
 *
 * @package WordPress
 * @subpackage Wp_Bootstrap
 * @since Wp Bootstrap 1.0
 *
 */
?>
<?php get_header();?>
<body>
	<?php require_once 'nav.php';?>
	<div class="container">
		<div class="row">
			<?php include_once 'breadcrumb.php';?>
			<div class="main">
				<div class="col-sm-8 col-md-8">
					<div class="row group-category"><h1><p><?php echo single_cat_title( '', false );?></p></h1></div>
					<?php $cats = get_the_category();
			    		if($cats && $cats[0]->term_id == 1){
			    	?>
						<ul class="nav nav-tabs">
						  <li role="presentation" <?php if($_GET['t'] !='create'){echo "class='active'";}?>><a href="?t=update">Recently Updated</a>
						  </li>
						  <li role="presentation" <?php if($_GET['t'] =='create'){echo "class='active'";}?>><a href="?t=create">Newest</a>
						  </li>
						</ul>
						<?php if($_GET['t'] !='create'){?>
						  <h2>The list recently updated Minecraft mods.</h2>
						  <hr>
						<?php }else{?>
							<h2>The list latest Minecraft mods.</h2>
							<hr>
						<?php }?>
					<?php }?>
					<?php require_once 'template_parts/loop-post.php';?>
				</div>
				<div class="col-sm-4 col-md-4 sidebar">
					<?php require_once 'sidebar.php';?>
				</div>
			</div>
		 </div>	
    </div> <!-- /container -->
<?php get_footer();?>