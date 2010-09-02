<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru"> 
	<head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        <meta http-equiv="Pragma" content="no-cache"/> 
        <meta http-equiv="Cache-Control" content="no-cache"/> 
        <meta http-equiv="Expires" content="1 Jan 2000 0:00:00 GMT"/> 
		<meta name="language" content="ru" /> 
        <meta name="description" content=<?php echo $data['desc'] ?>/>
        <meta name="keywords" content=<?php echo $data['keyword'] ?>/>
        <title><?php echo $data['title'] ?></title> 
        	
		<?php	
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/reset.css" type="text/css" media="screen"/>'; 
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/960.css" type="text/css" media="screen"/>'; 
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/style.css" type="text/css" media="screen"/>'; 
		
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/jquery.min.js"></script>';
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
			if($data['islogin']){
				echo '<div id="admin-panel">';
				echo '<span>Вы вошли как Администратор</span>
					 <a class="logout" href="'.$data['baseurl'].'admin/logoff">Завершить сеанс</a>';
				echo '</div>';
			}
		?>
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
					<?php echo anchor('','<img src="'.$data['baseurl'].'images/logo.png" alt="Samoilovi.ru"/>'); ?>
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
				<div id="about-us" class="grid_12">
					<div id="text-about">
						<span>Мы молоды и гостеприимны, любим путешествовать, </span><br/>
						<span>смотреть кино, ходить в театр, кушать суши и </span><br/>
						<span>ночевать на крыше. </span><br/>
						<span>Любовь – это бесконечное чудо и ничем другим</span><br/>
						<span>его не объяснишь. </span>
					</div>				

				</div>				
			</div>
			<div class="clear"></div>
		</div>
		<div class="push"></div>	 
	</div>