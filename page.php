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
				<div class="col-md-8">
					<?php require_once 'template_parts/page-content.php';?>
				</div>
				<div class="col-md-4 sidebar">
					<?php require_once 'sidebar.php';?>
				</div>
			</div>
		 </div>	
    </div> <!-- /container -->
<?php get_footer();?>