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
		 
		echo '<link type="text/css" rel="stylesheet" href="'.$data1['baseurl'].'css/pirobox.css" media="screen" class="piro_style" title="white"/>'; 
 
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/pirobox.min.js"></script>';
		?> 
		<script type="text/javascript"> 
		$(document).ready(function() {
			$().piroBox({
				my_speed: 400, //animation speed
				bg_alpha: 0.1, //background opacity
				slideShow : true, // true == slideshow on, false == slideshow off
				slideSpeed : 4, //slideshow duration in seconds(3 to 6 Recommended)
				close_all : '.piro_close,.piro_overlay'// add class .piro_overlay(with comma)if you want overlay click close piroBox
 
			});
		});
		</script> 
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
			<div class="container_12">
			<?php
			if(isset($data2) and !empty($data2)){
			?>
				<div id="photo-gallery" class="container_12">
				<?php
					foreach ($data2 as $album){
				?>
					<div class="grid_5 photo-album">
						<div class="album-background">
							<?php
								$text='<img class="album-main-photo" src="'.$data1['baseurl'].$album->alb_photo.'"
									alt="'.$album->alb_photo_title.'"/>';
								$str_uri = '/album/'.$album->alb_id.'/images';
								echo anchor($str_uri,$text);
							?>
						</div>
						<div class="album-text"> 
							<div class="album-title"><?php echo $album->alb_title; ?></div>
							<div class="album-amt"><?php echo 'Кадров - '.$album->alb_amt; ?></div>
							<div class="album-annotation"><?php echo $album->alb_annotation; ?></div>
						</div>
						<div class="clear"></div>
					</div>
				<?php
				}
				?>
				<div class="clear"></div>
				</div>
			<?php
			}
			?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="push"></div>	 
	</div>