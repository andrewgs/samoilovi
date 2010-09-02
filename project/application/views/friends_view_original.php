<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru"> 
	<head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        <meta http-equiv="Pragma" content="no-cache"/> 
        <meta http-equiv="Cache-Control" content="no-cache"/> 
        <meta http-equiv="Expires" content="1 Jan 2000 0:00:00 GMT"/> 
		<meta name="language" content="ru" /> 
        <meta name="description" content=<?php echo $data1['desc'] ?>/>
        <meta name="keywords" content=<?php echo $data1['keyword'] ?>/>
        <title><?php echo $data1['title'] ?></title> 
        	
		<?php	
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/reset.css" type="text/css" media="screen"/>'; 
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/960.css" type="text/css" media="screen"/>'; 
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/style.css" type="text/css" media="screen"/>'; 
		
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.min.js"></script>';
		?> 
		<script type="text/javascript"> 
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-17193616-1']);
			_gaq.push(['_trackPageview']);
 
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
    </head>
    <body>
    <div id="main-wrap">
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
					<?php 
						echo '<a href="'.$data1['baseurl'].'admin" title="На страницу администрирования" style="color: #FFFF00; font-size: 30px;">Администрирование</a>';
					?>
				</div>
				<div class="grid_7"></div>				
			</div>
			<div class="clear"></div>
		</div>
		<div id="content">
			<center>
			<?php
				echo '<a href="'.$data1['baseurl'].'admin/friendnew" title="" style="color: #FFFFFF; font-size: 20px;">Добавить карточку друга</a>';
			?>
			</center>
			<div class="container_16 friend-vcards">
			<?php
				foreach ($data2 as $friends){
			?>		
				<div class="grid_5 vcard">
					<div class="friend-info left">
						<?php
						 echo '<img src="'.$data1['baseurl'].$friends->fr_image.'" alt="'.$friends->fr_name.'"/>';
						?>
					</div>
					<div class="friend-specs left">
						<div class="friend-name">
							<?php echo $friends->fr_name; ?>
						</div>
						<div class="friend-profession">
							<?php echo $friends->fr_profession; ?>
						</div>
						<?php
						if($friends->fr_social != 0){
							echo '<div class="friend-social">';
							foreach ($data3 as $social){
								if ($social->soc_fr_id == $friends->fr_id)	
									echo anchor($social->soc_href,$social->soc_name,array('target'=>'_blank')).'&nbsp;&nbsp;';
							}
							echo '</div>';
						}
						?>						
						<hr/>
					</div>
					<div class="clear"></div>
					<div class="friend-note">
					<?php
						echo $friends->fr_note;
					?>
					</div>
				<?php
					$text = 'Редактировать';
					$str_uri = '/admin/friendedit/'.$friends->fr_id;
					echo '<div>'.anchor($str_uri,$text).' | ';
					$text = 'Удалить';
					$str_uri = '/admin/frienddestroy/'.$friends->fr_id;
					echo anchor($str_uri,$text).'</div>'; 
					echo '</div>';
				}
				?>
			</div>
			<div class="clear"></div>						
		</div>
		<div class="push"></div>	 
	</div>