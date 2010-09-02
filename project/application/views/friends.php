﻿<!DOCTYPE html> 
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
		<?php
			if($data1['islogin']){
				echo '<div id="admin-panel">';
				echo '<span>Вы вошли как Администратор</span>
					 <a class="logout" href="'.$data1['baseurl'].'admin/logoff">Завершить сеанс</a>';
				echo '</div>';
			}
		?>
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
					<?php echo anchor('','<img src="'.$data1['baseurl'].'images/logo.png" alt="Samoilovi.ru"/>'); ?>
				</div>
				<div class="grid_7">
					<ul id="header-menu">
						<?php
							echo "<li>".anchor('about','О нас')."</li>";
							echo "<li>".anchor('friends','Друзья')."</li>";
							echo "<li>".anchor('blog','События')."</li>";
							echo "<li>".anchor('album','Фотографии')."</li>";							
						?>
					</ul>
				</div>				
			</div>
			<div class="clear"></div>
		</div>
		<div id="content">
			<?php
			$cnt = 0;
			for($i = 0; $i < $data4/3; $i++){
			?>	
			<div class="container_16 friend-vcards">
			<?php
				for($y = 0; $y < 3; $y++){
			?>	
				<div class="grid_5 vcard">
					<div class="friend-info left">
					<?php
					 echo '<img src="'.$data1['baseurl'].$data2[$i][$y]['image'].'" alt="'.$data2[$i][$y]['name'].'"/>';
					?>
					</div>
					<div class="friend-specs left">
						<div class="friend-name">
							<?php echo $data2[$i][$y]['name']; ?>
						</div>
						<div class="friend-profession">
							<?php echo $data2[$i][$y]['profession']; ?>
						</div>
						<?php
						if($data2[$i][$y]['social'] != 0){
							echo '<div class="friend-social">';
							foreach ($data3 as $social){
								if ($social->soc_fr_id == $data2[$i][$y]['id'])	
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
					echo $data2[$i][$y]['note'];
				?>
				</div>
			</div>
			<?php
				$cnt += 1;
				if ($cnt == $data4) break;
			}
			?>
			</div>
			<div class="clear"></div>
			<?php
			}
			?>						
		</div>
		<div class="push"></div>	 
	</div>