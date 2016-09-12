<?php
global $aDownloadFiles;
wp_footer();
?>
<script src="http://minecraft-mods.info/wp-content/themes/minecraftpro/js/jquery.min.js"></script>
<script src="http://minecraft-mods.info/wp-content/themes/minecraftpro/js/bootstrap.min.js"></script>
<footer>
	<div class="col-md-4">
		<h2>minecraft-mods.info</h2>
		<p>Minecraft mods reviews, downloads and guides. Updated regularly with the latest and greatest Mods for Minecraft. </p>
		<p>On this website you can find the latest updates of minecraft, as well as to download free mods, resource packs, maps, skins for 1.9, 1.8, 1, 1.7.10, 1.6... </p>
	</div>
	<div class="col-md-4">
		<h2>Partners</h2>
		<img src="http://minecraft-mods.info/wp-content/themes/minecraftpro/images/curse.png"/>
		<img src="http://minecraft-mods.info/wp-content/themes/minecraftpro/images/minecraft-forum.png"/>
	</div>
	<div class="col-md-4">
		<ul class="ab">
			<li><a href="http://www.dmca.com/Protection/Status.aspx?ID=0bd61387-9fb6-43cf-b242-4538ddb13609" title="DMCA.com Protection Status" class="dmca-badge"> <img src="//images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=0bd61387-9fb6-43cf-b242-4538ddb13609" alt="DMCA.com Protection Status"></a></li>
			<li class="first"><a href="http://minecraft-mods.info/about">About us</a></li>
			<li><a href="http://minecraft-mods.info/copyrights">Copyrights</a></li>
			<li><a href="http://minecraft-mods.info/privacy-policy">Privacy policy</a></li>
			<li><a href="http://minecraft-mods.info/sitemap.xml">Sitemap</a></li>
			<li><a href="http://minecraft-mods.info/tag/minecraft-1-11-mods" title="Minecraft Mods 1.11">Minecraft Mods 1.11</a></li>	
		</ul>
	</div>
	<div class="col-md-12"><center><p>Copyright ©2014 – <?php echo date('Y'); ?> – minecraft-mods.info. All rights reserved. Minecraft-mods.info is not affiliated with Mojang AB.</p></center></div>
	<?php if($aDownloadFiles && $aDownloadFiles->listing_files) {?>
		<script src="http://minecraft-mods.info/wp-content/themes/minecraftpro/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
			var dataSet = <?php echo $aDownloadFiles->listing_files?>;
			$(document).ready(function() {
				$('#listing-files').DataTable( {
					data: dataSet,
					"order": [[ 4, 'desc' ]],
					"lengthMenu": [5, 10, 15, 20],
					columns: [
						{ title: "File Name" },
						{ title: "Status" },
						{ title: "Version" },
						{ title: "Downloads",
						"searchable": false,},
						{ title: "Date"}
					],
					
					"columnDefs": [ {
						"targets": 0,
						"render": function ( data, type, full, meta ) {
						  var href ='/p-download.php?id='+full[7]+'&v='+full[2]+'&file=' + full[6] +'&d='+full[4] +'&t='+ full[3];
						  return '<a title="'+data+'" href="'+href+'" target="_blank">'+data+'</a>';
						}
					  }]
				} );
				
				$('#listing-files tbody').on( 'click', 'tr', function () {
					$(this).find('a').click();
				} );
			} );
		</script>
	<?php }?>
	<div id="fb-root"></div>
	<script type="text/rocketscript">
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.async=true;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
</footer>
</body>
</html>