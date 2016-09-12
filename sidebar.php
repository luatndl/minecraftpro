	<div class="sidebar-ads">
		<div style="width:auto; height:280px; border:1px solid red;text-align:center;margin:0px 15px 10px 0px"> Ads here</div>
	</div>
	<?php if ( is_active_sidebar( 'sidebar_1' ) ) : ?>
	<div class="list-group">
		<ul>
		<?php dynamic_sidebar( 'sidebar_1' ); ?>
		</ul>
	</div>
   <?php endif; ?>
 <?php if ( is_active_sidebar( 'sidebar_3' ) ) : ?>
		<div class="list-group">
			<ul><?php dynamic_sidebar( 'sidebar_3' ); ?></ul>
		</div>
  <?php endif; ?>