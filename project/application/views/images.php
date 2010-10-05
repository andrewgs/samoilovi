<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <meta http-equiv="Pragma" content="no-cache"/> 
    <meta http-equiv="Cache-Control" content="no-cache"/> 
    <meta http-equiv="Expires" content="1 Jan 2000 0:00:00 GMT"/> 
	<meta name="language" content="ru" /> 
    <meta name="description" content=<?php echo $pagevalue['desc']; ?>/>
    <meta name="keywords" content=<?php echo $pagevalue['keyword']; ?>/>
    <title><?php echo $pagevalue['title']; ?></title> 
		        	
<?php
	define("CRLT", "\n");
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/reset.css" type="text/css" />'.CRLT; 
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/960.css" type="text/css" />'.CRLT; 
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/style.css" type="text/css" />'.CRLT;
	echo '<link type="text/css" rel="stylesheet" href="'.$data1['baseurl'].'css/pirobox.css" />'.CRLT; 
 
	echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.min.js"></script>'.CRLT;
	echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/pirobox.min.js"></script>'.CRLT;
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
	</script> 
</head>
<body>
	<div id="main-wrap">
		<?php $this->load->view('header',array('pagevalue'=>$pagevalue)); ?>
		<div id="content">
			<div class="container_12">
				<div id="internal_nav" class="grid_6">
				<a href="<?php echo $pagevalue['baseurl'].$pagevalue['backpath']; ?>">&nbsp;&larr;&nbsp;Вернуться назад</a>
				</div>
			</div>
			<div class="clear"></div>
			<div class="container_12">
			<?php if(isset($images) and !empty($images)):?>
				<div id="photo-gallery" class="container_12">
				<?php for($i = 0;$i < count($images);$i++): ?>
					<div class="grid_3 photo-images">
						<div class="album-background images">
						<?php echo '<a class="pirobox" href="'.$pagevalue['baseurl'].$images['img_src'].'" 
								title="'.$images['img_title'].'">
								<img src="'.$pagevalue['baseurl'].$images['img_src'].'" 
								alt="'.$images['img_title'].'"/></a>'; ?>
						</div>
						<div class="images-text"> 
							<div class="image-title"><?php echo $images['img_title']; ?></div>
						</div>
					</div>
				<?php endfor;?>
				<div class="clear"></div>
				</div>
		<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="push"></div>
	</div>
	<?php $this->load->view('footer'); ?>