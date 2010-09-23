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
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/swfobject.js"></script>'; 
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/base.js"></script>'; 
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
			<div class="container_16">
				<div id="slogan" class="grid_16">
					<?php echo '<img src="'.$data1['baseurl'].'images/slogan.png" alt="Мы пестрыми нотами споем вам о любви"/>'; ?>
				</div>
			</div>
			<div class="clear"></div>
			<div id="content-info">
				<div class="container_16">
					<div class="grid_10">
						<div id="arrow-left"> </div>
						<div id="media-stream">
							<div id="vimeo_player_holder"> </div>
						 </div>
						<div id="arrow-right"> </div>
					</div>
					<div class="grid_4 suffix_2">						
						<div id="photo-stack"> </div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div id="footer">
			<div id="events" class="container_12">
			<?php foreach ($data2 as $blog){ ?>
				
				<div class="grid_4">
				<?php
					echo '<h2>'.$blog->blg_date.'</h2>';
					echo '<p>'.$blog->blg_text.'... <br />'.anchor('commentslist/'.$blog->blg_id,'Читать далее').'</p>';					?>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<div class="push"></div>	 
	</div>