<?php
if($pagevalue['admin']):
	echo '<div id="admin-panel">';
	echo '<span>Вы вошли как Администратор</span>
		 <a class="logout" href="'.$pagevalue['baseurl'].'admin/logoff">Завершить сеанс</a>';
	echo '</div>';
endif;
?>
<div id="header">
	<div class="container_16">
		<div id="logo" class="grid_4 suffix_5">
		<?php echo anchor('','<img src="'.$pagevalue['baseurl'].'images/logo.png" alt="Samoilovi.ru"/>'); ?>
		</div>
		<div class="grid_7">
			<ul id="header-menu">
			<?php echo "<li>".anchor('about','О нас')."</li>"; ?>
			<?php echo "<li>".anchor('friends','Друзья')."</li>"; ?>
			<?php echo "<li>".anchor('events','События')."</li>"; ?>
			<?php echo "<li>".anchor('photo-albums','Фотографии')."</li>"; ?>							
			</ul>
		</div>	
	</div>
	<div class="clear"></div>
</div>