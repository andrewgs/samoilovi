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
		?> 
		
    </head>
    <body>
    <div id="main-wrap">
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
					<a href="<? echo $data['baseurl'].'admin'; ?>">Администрирование</a>
				</div>
				<div id="global_nav" class="grid_7">
					<a class="logout" href="<?php echo $data['baseurl'].'admin/logoff'; ?>">Завершить сеанс</a>
				</div>				
			</div>
			<div class="clear"></div>
		</div>
		<div id="content">
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $data['baseurl'].'admin/blogview'; ?>">Управление событиями &raquo;</a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $data['baseurl'].'admin/commentslist'; ?>">Cписок комментариев &raquo;</a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $data['baseurl'].'admin/albumsview'; ?>">Управление альбомами &raquo;</a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $data['baseurl'].'admin/friendsview'; ?>">Управление друзьями &raquo;</a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $data['baseurl'].'admin/profile'; ?>">Смена пароля &raquo;</a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $data['baseurl']; ?>">На главную страницу &raquo;</a>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="push"></div>
	</div>