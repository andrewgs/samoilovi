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
		define("CRLT", "\n");
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/reset.css" type="text/css" media="screen"/>'.CRLT; 
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/960.css" type="text/css" media="screen"/>'.CRLT; 
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/style.css" type="text/css" media="screen"/>'.CRLT;
		 
		echo '<link type="text/css" rel="stylesheet" href="'.$data1['baseurl'].'css/pirobox.css" media="screen" class="piro_style" title="white"/>'.CRLT; 
 
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.min.js"></script>'.CRLT;
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/pirobox.min.js"></script>'.CRLT;
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.confirm.js"></script>'.CRLT;
		?> 
		<script type="text/javascript"> 
			$(document).ready(function() {
				$().piroBox({
					my_speed: 400, 
					bg_alpha: 0.1, 
					slideShow : true, 
					slideSpeed : 4,
					close_all : '.piro_close,.piro_overlay'
				});
			});
			$(document).ready(function() {	
					$('a.delalimg').confirm();
			});
		</script> 
</head>
<body>
	<div id="main-wrap">
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
					<a href="<? echo $data1['baseurl'].'admin'; ?>">Администрирование</a>
				</div>
				<div id="global_nav" class="grid_7">
					<a class="logout" href="<?php echo $data1['baseurl'].'admin/logoff'; ?>">Завершить сеанс</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="content">
			<div class="container_12">
				<div id="internal_nav" class="grid_6">
					<a href="<?php echo $data1['baseurl'].'admin/albumsview'; ?>">&laquo; Вернуться к альбомам</a>
				</div>
				<div id="internal_nav" class="grid_6">
					<a href="<?php echo $data1['baseurl'].'admin/album/'.$data3.'/addphoto'; ?>">Добавить фотографию &raquo;</a>
				</div>
			</div>
			<div class="clear"></div>
			<div class="container_12">
			<?php
			if(isset($data2) and !empty($data2)){
			?>
				<div id="photo-gallery" class="container_12">
				<?php
					foreach ($data2 as $image){
				?>
					<div class="grid_3 photo-album">
						<div class="album-background images">
						<?php
							echo '<a class="pirobox" href="'.$data1['baseurl'].$image->img_src.'" 
								title="'.$image->img_title.'">
								<img src="'.$data1['baseurl'].$image->img_src.'" 
								alt="'.$image->img_title.'"/></a>';
							?>
						</div>
						<div class="images-text"> 
							<div class="image-title"><?php echo $image->img_title; ?></div>
						</div>
						<div class="album-controls">
							<?php
								$text = 'Удалить фото';
								$str_uri = 'admin/album/'.$data3.'/deletephoto/'.$image->img_id;
								$attr = array('class'=>'delalimg');
								echo anchor($str_uri,$text,$attr);
							?>
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