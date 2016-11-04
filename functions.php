<?php
ob_start();
@ini_set( 'upload_max_size' , '1024M' );
@ini_set( 'post_max_size', '1024M');
@ini_set( 'max_execution_time', '1000');

/** Tell WordPress to run crazyminecraft_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'crazyminecraft_setup' );
add_action( 'widgets_init', 'arphabet_widgets_init' );

if ( ! function_exists( 'crazyminecraft_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override crazyminecraft_setup() in a child theme, add your own tadin_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails, custom headers and backgrounds, and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Tadin.co.il 1.0
 */
function crazyminecraft_setup() {
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'crazyminecraft.net' ),
	) );
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 146, 146 ); // Unlimited height, soft crop
}
endif;
if ( ! function_exists( 'arphabet_widgets_init' ) ):
/**
 * Register our sidebars and widgetized areas.
 *
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name' => 'Sidebar Right',
		'id' => 'sidebar_1',
		'description' => '',
		'class' => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<label>',
		'after_title' => "</label>",
	) );

	register_sidebar( array(
		'name' => 'Sidebar Footer',
		'id' => 'sidebar_2',
		'description' => '',
		'class' => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<label>',
		'after_title' => "</label>",
	) );

	register_sidebar( array(
		'name' => 'Ads Sidebar Right',
		'id' => 'sidebar_3',
		'description' => '',
		'class' => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<label>',
		'after_title' => "</label>",
	) );


}
endif;

add_filter( 'manage_media_columns', 'download_link' );
function download_link($columns) {
    $columns['meta_download_link'] = __('<span style="font-weight:bold;font-size:15px;color:green;">Download link</span>');
    return $columns;
}

add_filter( 'manage_media_custom_column', 'download_link_row', 10, 2 );
function download_link_row($columnName, $id){
	global $wpdb;
	$data = $wpdb->get_row(
				$wpdb->prepare(
					"
					SELECT meta_download_link FROM wp_postmeta
					WHERE post_id = %d
					",$id
				)
			);
    if($columnName == 'meta_download_link'){
		if($data->meta_download_link !='')
			echo '<textarea>'.$data->meta_download_link .'</textarea>';
    }
}

add_action('manage_users_columns','yoursite_manage_users_columns');

function yoursite_manage_users_columns($column_headers) {
    unset($column_headers['posts']);
    $column_headers['custom_posts'] = 'Assets';
    return $column_headers;
}

add_action('manage_users_custom_column','yoursite_manage_users_custom_column',10,3);
function yoursite_manage_users_custom_column($custom_column,$column_name,$user_id) {
    if ($column_name=='custom_posts') {
        $counts = _yoursite_get_author_post_type_counts();
        $custom_column = array();
        if (isset($counts[$user_id]) && is_array($counts[$user_id]))
            foreach($counts[$user_id] as $count) {
                $link = admin_url() . "edit.php?post_type=" . $count['type']. "&author=".$user_id;
                // admin_url() . "edit.php?author=" . $user->ID;
                $custom_column[] = "\t<tr><th><a href={$link}>{$count['label']}</a></th><td>{$count['count']}</td></tr>";
            }
        $custom_column = implode("\n",$custom_column);
        if (empty($custom_column))
            $custom_column = "<th>[none]</th>";
        $custom_column = "<table>\n{$custom_column}\n</table>";
    }
    return $custom_column;
}

function _yoursite_get_author_post_type_counts() {
    static $counts;
    if (!isset($counts)) {
        global $wpdb;
        global $wp_post_types;
        $sql = <<<SQL
        SELECT
        post_type,
        post_author,
        COUNT(*) AS post_count
        FROM
        {$wpdb->posts}
        WHERE 1=1
        AND post_type NOT IN ('revision','nav_menu_item')
        AND post_status IN ('publish','pending', 'draft')
        GROUP BY
        post_type,
        post_author
SQL;
        $posts = $wpdb->get_results($sql);
        foreach($posts as $post) {
            $post_type_object = $wp_post_types[$post_type = $post->post_type];
            if (!empty($post_type_object->label))
                $label = $post_type_object->label;
            else if (!empty($post_type_object->labels->name))
                $label = $post_type_object->labels->name;
            else
                $label = ucfirst(str_replace(array('-','_'),' ',$post_type));
            if (!isset($counts[$post_author = $post->post_author]))
                $counts[$post_author] = array();
            $counts[$post_author][] = array(
                'label' => $label,
                'count' => $post->post_count,
                'type' => $post->post_type,
                );
        }
    }
    return $counts;
}

// Show only posts and media related to logged in author
add_action('pre_get_posts', 'query_set_only_author' );
function query_set_only_author( $wp_query ) {
    global $current_user;
    if( is_admin() && !current_user_can('edit_others_posts') ) {
        $wp_query->set( 'author', $current_user->ID );
        add_filter('views_edit-post', 'fix_post_counts');
        add_filter('views_upload', 'fix_media_counts');
    }
}

// Fix post counts
function fix_post_counts($views) {
    global $current_user, $wp_query;
    unset($views['mine']);
    $types = array(
        array( 'status' =>  NULL ),
        array( 'status' => 'publish' ),
        array( 'status' => 'draft' ),
        array( 'status' => 'pending' ),
        array( 'status' => 'trash' )
    );
    foreach( $types as $type ) {
        $query = array(
            'author'      => $current_user->ID,
            'post_type'   => 'post',
            'post_status' => $type['status']
        );
        $result = new WP_Query($query);
        if( $type['status'] == NULL ):
            $class = ($wp_query->query_vars['post_status'] == NULL) ? ' class="current"' : '';
            $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),
                admin_url('edit.php?post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'publish' ):
            $class = ($wp_query->query_vars['post_status'] == 'publish') ? ' class="current"' : '';
            $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),
                admin_url('edit.php?post_status=publish&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'draft' ):
            $class = ($wp_query->query_vars['post_status'] == 'draft') ? ' class="current"' : '';
            $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),
                admin_url('edit.php?post_status=draft&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'pending' ):
            $class = ($wp_query->query_vars['post_status'] == 'pending') ? ' class="current"' : '';
            $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),
                admin_url('edit.php?post_status=pending&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'trash' ):
            $class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';
            $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),
                admin_url('edit.php?post_status=trash&post_type=post'),
                $result->found_posts);
        endif;
    }
    return $views;
}

// Fix media counts
function fix_media_counts($views) {
    global $wpdb, $current_user, $post_mime_types, $avail_post_mime_types;
    $views = array();
    $count = $wpdb->get_results( "
        SELECT post_mime_type, COUNT( * ) AS num_posts
        FROM $wpdb->posts
        WHERE post_type = 'attachment'
        AND post_author = $current_user->ID
        AND post_status != 'trash'
        GROUP BY post_mime_type
    ", ARRAY_A );
    foreach( $count as $row )
        $_num_posts[$row['post_mime_type']] = $row['num_posts'];
    $_total_posts = @array_sum($_num_posts);
    $detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
    if ( !isset( $total_orphans ) )
        $total_orphans = $wpdb->get_var("
            SELECT COUNT( * )
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
            AND post_author = $current_user->ID
            AND post_status != 'trash'
            AND post_parent < 1
        ");
    $matches = wp_match_mime_types(@array_keys($post_mime_types), @array_keys($_num_posts));
    foreach ( $matches as $type => $reals )
        foreach ( $reals as $real )
            $num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
    $class = ( empty($_GET['post_mime_type']) && !$detached && !isset($_GET['status']) ) ? ' class="current"' : '';
    $views['all'] = "<a href='upload.php'$class>" . sprintf( __('All <span class="count">(%s)</span>', 'uploaded files' ), number_format_i18n( $_total_posts )) . '</a>';
    foreach ( $post_mime_types as $mime_type => $label ) {
        $class = '';
        if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )
            continue;
        if ( !empty($_GET['post_mime_type']) && wp_match_mime_types($mime_type, $_GET['post_mime_type']) )
            $class = ' class="current"';
        if ( !empty( $num_posts[$mime_type] ) )
            $views[$mime_type] = "<a href='upload.php?post_mime_type=$mime_type'$class>" . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), $num_posts[$mime_type] ) . '</a>';
    }
    $views['detached'] = '<a href="upload.php?detached=1"' . ( $detached ? ' class="current"' : '' ) . '>' . sprintf( __( 'Unattached <span class="count">(%s)</span>', 'detached files' ), $total_orphans ) . '</a>';
    return $views;
}

add_filter('upload_mimes','add_custom_mime_types');
	function add_custom_mime_types($mimes){
		return array_merge($mimes,array (
			'jar' => 'application/octetstream'
		));
	}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true) + getDownloaded($postID);
    if($count==''){
        return "0";
    }
    return $count;
}

// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}


add_filter( 'manage_posts_columns', 'edit_pots_columns' ) ;

function edit_pots_columns( $columns ) {
	$columns['post_modified'] = __('Modified');
	return $columns;
}

add_action( 'manage_posts_custom_column', 'edit_manage_post_columns', 10, 2 );

function edit_manage_post_columns( $column, $post_id ) {
	if ( 'post_modified' != $column ){
		return;
	}
	$post_modified = get_post_field('post_modified', $post_id);
	if ( !$post_modified ){
		$post_modified = '' . __( 'undefined') . '';
	}
	echo $post_modified;
}
// Register the column as sortable
function post_modified_column_register_sortable( $columns ) {
	$columns['post_modified'] = 'post_modified';
	return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'post_modified_column_register_sortable' );

//add a toggle shortcode
 //toggle shortcode
 function toggle_shortcode( $atts, $content = null ) {
 extract( shortcode_atts(
 array(
   'title' => 'Click To Open',
   'color' => ''
 ),
 $atts ) );
 return '<h3 class="trigger toggle-gray"><a href="#">'. $title .'</a></h3><div class="toggle_container">' . do_shortcode($content) . '</div>';
 }
 add_shortcode('toggle', 'toggle_shortcode');

// function to count views.
function setDownloaded($postID) {
    $count_key = 'download_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// function to display number of posts.
function getDownloaded($postID){
    $count_key = 'download_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        return "0";
    }
    return $count;
}

// function to display number of posts.
function getFavorited($postID){
    $count_key = 'post_favorited_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        return "0";
    }
    return $count;
}

// function to count views.
function setFavorited($postID) {
    $count_key = 'post_favorited_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// function to display number of posts.
function getStarRating($postID){
	$count = 'star_rating';
    $count = get_post_meta($postID, $count, true);
    if($count=='' || $count== 0){
        $count=5;
    }
	$html ='';
    for($i=1;$i<=$count;$i++)
    {
		$html .='<span class="glyphicon glyphicon-star"></span>';
    }
    $n = 5 - $count;
    if($n >0)
    {
    	for($i=1;$i<=$n;$i++)
   	 	{
			$html .='<span class="glyphicon glyphicon glyphicon-star-empty"></span>';
    	}
    }
    return $html;
}

// function to display number of posts.
function getAuthor($postID){
	$author = 'post_author';
    $author = get_post_meta($postID, $author, true);
    if($author==''){
        return "minecraft-mods.info";
    }
    return $author;
}

// function to display number of posts.
function getResolution($postID){
	$resolution = 'resolution';
    $resolution = get_post_meta($postID, $resolution, true);
    return $resolution !='' ? $resolution : '';
}

function getGameVersion($postID, $html =false){
	$version = 'game_version';
    $version = get_post_meta($postID, $version, true);
    if($version==''){
        return "<span class='version popular'>All</span>";
    }else{
    	
    	if($html)
    	{
    		return $version;
    	}
    	$v = '';
    	$a_Versions = explode(',', $version);
    	
    	if($a_Versions)
    	{
    		foreach ($a_Versions as $k=>$sz_Version){
    			$cl = '';
    			if($k==0){
    				$cl = ' newest';
    			}
    			
    			if($k==1){
    				$cl = ' latest';
    			}
    			
    			if($k==2)
    			{
    				$cl= ' popular';
    			}
    			
    			$v .= "<span class='version$cl'>$sz_Version</span>";
    		}
    	}
    }
    return $v;
}

function getCreated($id){
	$create = 'created';
    return get_post_meta($id, $create, true);
}

// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_download');

add_action('manage_posts_custom_column', 'posts_custom_column_download',5,2);
function posts_column_download($defaults){
    $defaults['post_downloaded'] = __('Downloaded');
    return $defaults;
}
function posts_custom_column_download($column_name, $id){
	if($column_name === 'post_downloaded'){
        echo getPostViews(get_the_ID());
    }
}

function add_image_responsive_class($content) {
   global $post;
   $pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
   $replacement = '<img$1class="$2 img-responsive"$3>';
   $content = preg_replace($pattern, $replacement, $content);
   $html ='<div class="col-xs-24 col-sm-24">
   						<a name="download"></a>
						<h2><strong>Download Mods:</strong></h2>
						<table id="listing-files" class="display" width="100%"></table>
					</div>';
   $content =str_replace("LISTING_FILES_DATATABLE", $html , $content);
   return $content;
}
add_filter('the_content', 'add_image_responsive_class');

function wp_paginate()
{
	$currentPage = null;
	$totalPage = null;
    global $wp_query;
    $currentPage = intval(get_query_var('paged'));
    if(empty($currentPage))
    {
        $currentPage = 1;
    }
    $totalPage = intval(ceil($wp_query->found_posts / intval(get_query_var('posts_per_page'))));
    if($totalPage <= 1)
    {
        return '';
    }
    $paginateResult = '<ul class="pagination pagination-lg pagination-sm">';

    if ($currentPage > 1)
    {
        $paginateResult .= '<li><a href="'.get_pagenum_link($currentPage - 1).'">&laquo;</a></li>';
    }
    $paginateResult .= ListLink(1, $totalPage, $currentPage);
    if ($currentPage < $totalPage)
    {
        $paginateResult .= "<li><a href='" . get_pagenum_link($currentPage + 1) . "' class='spaginate-next'>&raquo;</a></li>";
    }
    $paginateResult .= "</ul>";
    echo $paginateResult;
    return $paginateResult;
}

function ListLink($intStart, $totalPage, $currentPage)
{
	$pageHidden='';
	$linkResult = "";
    $hiddenBefore = false;
    $hiddenAfter = false;
    for ($i = $intStart; $i <= $totalPage; $i++)
    {
        if($currentPage === intval($i))
        {
            $linkResult .='<li class="active"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
        }
        else if(($i <= 5 && $currentPage < 10) || $i == $totalPage || $i == 1 || $i < 4 || ($i <= 5 && $totalPage <= 5) || ($i > $currentPage && ($i <= ($currentPage + 2))) || ($i < $currentPage && ($i >= ($currentPage - 2))) || ($i >= ($totalPage - 2) && $i < $totalPage))
        {
            $linkResult .= '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
            if($i <= 5 && $currentPage < 10)
            {
                $hiddenBefore = true;
            }
        }
        else
        {
            if(!$hiddenBefore)
            {
                $linkResult .= $pageHidden;
                $hiddenBefore = true;
            }
            else if(!$hiddenAfter && $i > $currentPage)
            {
                $linkResult .= $pageHidden;
                $hiddenAfter = true;
            }
        }
    }
    return $linkResult;
}
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_headers', 'crz_remove_x_pingback' );
function crz_remove_x_pingback( $headers )
{
    unset( $headers['X-Pingback'] );
    return $headers;
}

function jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }
}
 
add_action( 'loop_start', 'jptweak_remove_share' );

function generate_download_link($post_id, $post){
	$sz_LinkDownload = get_post_meta($post_id, 'link_download', true);
	$sz_Author = get_post_meta($post_id, 'post_author', true);
	//$sz_Versions = get_post_meta($post_id, 'game_version', true);
	$sz_Youtubes = get_post_meta($post_id, 'youtube_url', true);
	$a_Cats = wp_get_post_categories( get_the_ID());
	$cat = array(1=>'Mod', 2=>'Map', 7=>'Texture packs');;
	$data = array('ID'=>$post_id,'link_download' =>$sz_LinkDownload);
	$spotlights = '<a name="spotlights"></a>
                   <h2><strong>'.$cat[end($a_Cats)].' Spotlights:</strong></h2>';
	
	if($sz_Youtubes)
	{
		$a_Youtubes = explode(',', $sz_Youtubes);
		foreach($a_Youtubes as $y_l){
			$spotlights .= "\n".$y_l . "\n";
		}
	}else{
		$spotlights .= 'Update soon...';
	}
	$title = $post->post_title;
//	if($a_Cats[0]->cat_ID == 1)
	if (in_array(1, $a_Cats))
	{
		$howTo = '
	            	 <a name="howTo"></a>
	                 <h2><strong>How to install:</strong></h2>
	                 <ol>
	                     <li>Install the version of Forge that corresponds with the mod (http://files.minecraftforge.net/ Choose the installer version of Forge)</li>
	                     <li>Download the <strong>NAME_MOD</strong>.</li>
	                     <li>Drop the entire zipped file into your mods folder (Search %appdata% on your PC then go into .minecraft, then mods(create this folder if it is not there))</li>
	                     <li>Open Minecraft and make sure your profile is set to Forge</li>
	                     <li>Start Minecraft and enjoy!</li>
	                </ol>
	            ';
	
		$howTo = str_replace('NAME_MOD',$title, $howTo);
	}
	
	
	 
	if($data && $sz_LinkDownload){
		if(!strpos($sz_LinkDownload,"curse.com") === FALSE)
		{
			$download_link  ='LISTING_FILES_DATATABLE';
			v_fUpdate($data);
		}else{
			$download_link  ='<a name="download"></a>
                     <h2><strong>Download latest file:</strong></h2>';
			$sz_Versions = get_post_meta($post_id, 'game_version', true);
			if($sz_Versions){
				$a_Versions = explode(',', $sz_Versions);
				foreach($a_Versions as $v)
				{
					$v = trim($v);
					$download_link .="<blockquote><a href='http://minecraft-mods.info/p-download.php?id=$post_id&v=$v' title='$title $v' target='_blank'>$title $v</a></blockquote>";
				}
			}
		}
	}
	 
	$officialSource = "<p>This ".strtolower($cat[end($a_Cats)])." is made by <strong>$sz_Author</strong>, all credit to modder. Visit the <a href='$sz_LinkDownload' target='_blank' rel='nofollow'>original ".strtolower($cat[end($a_Cats)])." thread here</a> for all info.</p>";
	 
	$post->post_content =  str_replace('LINK_DOWNLOAD',$spotlights. $howTo.$download_link . $officialSource, $post->post_content);
	 
	if(!( wp_is_post_revision( $post_id))) {
		 
		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', 'generate_download_link',13,2);
		 
		// update the post, which calls save_post again
		wp_update_post( (array)$post );

		// re-hook this function
		add_action('save_post', 'generate_download_link',13,2);
	}
}

add_action( 'save_post', 'generate_download_link',13,2 );

function v_fUpdate($data){
	global $wpdb;
	$a_Error = array();
	$cxContext = null;
	$html = @file_get_html($data['link_download'], false, $cxContext);
	$aDownloadFiles = array();
	$aVersions = array();
	if($html){
		$newestFile = trim(str_replace('Newest File:','', $html->find('ul.details-list li.newest-file', 0)->plaintext));
		//echo $newestFile;
		foreach($html->find("table.project-file-listing tbody tr") as $key=>$tr){
			if ($key !=0){
				if($tr){
					$filename = $tr->find('td',0)->find('a',0)->plaintext;
					$link = 'http://mods.curse.com'.$tr->find('td',0)->find('a',0)->href;
					$html1 = @file_get_html($link, false, $cxContext);
					if($html1)
					{
						$a = "data-epoch";
						$file_path = $html1->find('div.countdown a', 0);
						$link_download = $file_path->getAttribute('data-href');
						unset($html1);
						$status = trim($tr->find('td',1)->plaintext);
						$version = trim($tr->find('td',2)->plaintext);
						$download = trim($tr->find('td',3)->plaintext);
						$dateString = $tr->find('td abbr',0)->$a;
						$aVersions[] = $version;
						$aDownloadFiles[] = array(
								$filename,
								$status,
								$version,
								str_replace(array(',',' '), "", $download),
								date('m/d/Y H:i:s',$dateString),
								$link,
								base64_encode(str_replace('http://addons.curse.cursecdn.com/', '', $link_download)),
								$data['ID']
						);
					}
				}
			}
		}

		unset($html);
		$aVersions = array_unique($aVersions);
		rsort($aVersions);
		$last_updated  = date("Y-m-d H:i:s");
		$original_thread = $data['link_download'];
		$listing_files= json_encode($aDownloadFiles);
		$sz_Version = implode(', ', $aVersions);
		$post_id  = $data['ID'];

		$insertSql = "REPLACE INTO wp_downloads(post_id,listing_files,newest_file,versions,original_thread,last_updated)
		VALUES($post_id,'$listing_files','$newestFile','$sz_Version','$original_thread', '$last_updated');";
		$result = $wpdb->query($insertSql);
		return json_encode($aDownloadFiles);
	}
}

function topTen_function() {
	
	global $wpdb;
	
	$data = $wpdb->get_results(
			"SELECT post_id, sum(downloaded) as downloaded, wp_posts.post_title FROM wp_download_report 
				INNER JOIN wp_posts on wp_download_report.post_id=wp_posts.ID 
			GROUP BY post_id ORDER BY downloaded DESC LIMIT 0,10"
	);
	$html = '';
	$s_table ='<table class="table table-condensed topTen">
				<tbody>
					<tr class="info">
						<th>#</th>
						<th class="toptenimg">&nbsp;</th>
						<th>Mod Name</th>
						<th>Supports</th>
						<th>Daily Downloaded</th>
					</tr>';
	$tr = '';
	$e_table = '</tbody></table>';

	if($data){
		foreach($data as $k=>$v){
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($v->post_id), 'thumbnail' );
			$version = get_post_meta($v->post_id, 'game_version', true);
			$num = $k+1;
			$tr .=	'<tr>
					<td> '. $num .'</td>
					<td><img class="img-responsive" src="'.$thumb["0"].'" width="150" height="150" alt="'.$v->post_title.'"/></td>
					<td><a href="'.get_permalink($v->post_id).'" title="'.$v->post_title.'"><h2>'. $v->post_title .'</h2></a></td>
					<td><strong>'. $version .'</strong></td>
					<td>'.$v->downloaded.'</td>
				</tr>';
		}
	}
	$html = $s_table .$tr . $e_table;
	return $html;
}

add_shortcode('TOP_TEN', 'topTen_function');

function url(){
	return sprintf(
			"%s://%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME']
	);
}

/*function my_after_upload($metadata, $attachment_id) {
	if(!$metadata)
	{
		//http://www.minecraft-mods.info/download.php?act=view&id=81
		$dl = url().'/download.php?act=view&id='.$attachment_id;
		global $wpdb;
		$wpdb->update(
				'wp_postmeta',
				array(
						'meta_download_link' => $dl	// string
				),
				array( 'post_id' => $attachment_id ),
				array(
						'%s',	// value1
				),
				array( '%d' )
		);
	}
}

add_filter( 'wp_generate_attachment_metadata', 'my_after_upload', 10, 2 );
*/