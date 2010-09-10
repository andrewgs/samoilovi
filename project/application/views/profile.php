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
		echo '<link rel="shortcut icon" type="image/x-icon" href="http://sk-stroikov.ru/favicon.ico"/>'.CRLT;
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/reset.css" type="text/css" media="screen"/>'.CRLT;
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/960.css" type="text/css" media="screen"/>'.CRLT;
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/style.css" type="text/css" media="screen"/>'.CRLT;
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.min.js"></script>';
		?> 
		<script type="text/javascript"> 
			$(function(){
				$("div.blog-content").each(function(){
					$(this).parents("div.blog-center:first").css('height', $(this).height()+10);
				});
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
				<div class="grid_7"></div>	
			</div>
			<div class="clear"></div>
		</div>
		<div class="container_12">
			<div id="internal_nav" class="grid_4 suffix_8">
				<a href="<?php echo $data1['baseurl'].'admin'; ?>">&laquo; Вернуться назад</a>
			</div>
		</div>
		<div class="clear"></div>
		
		<div class="container_16">
			<div id="comment-form-content" class="grid_6 form-content">
			<?php
				foreach ($data2 as $users){
					$attr = array('name' => 'formprofile','id' => 'formprofile');
					echo form_open('admin/profileupdate',$attr);
					echo form_hidden('id',$users->usr_id);
					
					echo form_error('oldpass').'<div class="clear"></div>';
					echo '<div id="edit-name-wrapper" class="form-item">';
					echo form_label('Старый пароль','userslabel');
					$attr = array(
							'name'		 => 'oldpass',
							'id'  		 => 'oldpass',
							'value'		 => '',
							'maxlength'	 => '70',
        					'size' 		 => '40',
							'class' => 'form-text required'
							);
					echo '<div class="dd">'.form_password($attr).'</div>';
					echo form_error('newpass').'<div class="clear"></div>';
					echo form_label('Новый пароль','userslabel');
					$attr =array(
							'name'	 	=> 'newpass',
							'id'  		=> 'newpass',
							'value'		=> '',
							'maxlength'	=> '70',
        					'size' 		=> '40',
							'class' => 'form-text required'
							);
					echo '<div class="dd">'.form_password($attr).'</div>';
					echo form_error('confirmpass').'<div class="clear"></div>';
					echo form_label('Подтверждение пароля','userslabel');
					$attr =array(
							'name'	 	=> 'confirmpass',
							'id'  		=> 'confirmpass',
							'value'		=> '',
							'maxlength'	=> '70',
        					'size' 		=> '40',
							'class' => 'form-text required'
							);
					echo '<div class="dd">'.form_password($attr).'</div>';
					echo '</div>';
					echo '<div class="clear"></div>';
					$attr =array(
							'name'	=> 'btsabmit',
							'id'   	=> 'btnsabmit',
							'value'	=> 'Сохранить',
							'class'	=> 'senden'
							);
					echo '<div id="bt_submit">'.form_submit($attr).'</div>';							
					echo form_close();
				}
				?>			
			</div>
		</div>
		<div class="clear"></div>
	</div>