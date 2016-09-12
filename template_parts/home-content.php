<?php
	$args = array(1,3,2,7,41);
	foreach($args as $cat_id) {
		$cat_item = get_category($cat_id,false);
		$cat_id = $cat_item->term_id;
		
		if($cat_id ==3){
			include 'skins.php';
		}else{
			include 'posts.php';
		}
?>
<?php } //End Foreach Cats?>




