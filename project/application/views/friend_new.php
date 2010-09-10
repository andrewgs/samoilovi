<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru"> 
	<head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        <meta http-equiv="Pragma" content="no-cache"/> 
        <meta http-equiv="Cache-Control" content="no-cache"/> 
        <meta http-equiv="Expires" content="1 Jan 2000 0:00:00 GMT"/> 
		<meta name="language" content="ru" /> 
        <meta name="description" content=<?php echo $pagevalue['desc'] ?>/>
        <meta name="keywords" content=<?php echo $pagevalue['keyword'] ?>/>
        <title><?php echo $pagevalue['title'] ?></title> 
        	
		<?php	
		echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/reset.css" type="text/css" media="screen"/>'; 
		echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/960.css" type="text/css" media="screen"/>'; 
		echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/style.css" type="text/css" media="screen"/>'; 
		
		echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/jquery.maxlength-min.js"></script>';
		?> 
		<script type="text/javascript"> 
			$(document).ready(function(){
				$('#friendnote').maxlength({
					maxCharacters: 230,
					status: true,  
					statusText: " символов осталось.",
					slider: true
				});
				$("div.blog-content").each(function(){
					$(this).parents("div.blog-center:first").css('height', $(this).height()+15);
				});
			});
		</script>	
</head>
<body>
   <div id="main-wrap">
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
					<a href="<? echo $pagevalue['baseurl'].'admin'; ?>">Администрирование</a>
				</div>
				<div id="global_nav" class="grid_7">
					<a class="logout" href="<?php echo $pagevalue['baseurl'].'admin/logoff'; ?>">Завершить сеанс</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
					<a href="<?php echo $pagevalue['baseurl'].'admin/friendsview'; ?>">&laquo; Вернуться к списку друзей</a>
				</div>
			</div>
			<div class="container_16">
				<?php
					echo form_error('name').'<div class="clear"></div>';
					echo form_error('profession').'<div class="clear"></div>';
					echo form_error('note').'<div class="clear"></div>';
				?>
				<div id="blog" class="grid_16">
					<div class="blog-top"> 
						<div class="blog-tl"> </div>
						<div class="blog-t"> </div>
						<div class="blog-tr"> </div>
						<div class="clear"></div>
					</div>
					<div class="blog-center"> 
						<div class="blog-l"> </div>
						<div class="blog-content">
							<div class="post-header-friend">
								<div class="post-title">
								<?php
									$attr = array('name' => 'formfriendnew','id' => 'formfriend');
									echo form_open_multipart('admin/friendinsert',$attr);
										echo '<div>'.form_label('Имя друга: &nbsp;&nbsp;','friendlabel');
										$attr = array(
													'name' => 'name',
              										'id'   => 'friendname',
              										'value'=> set_value('name'),
													'class'=> 'textfield',
              									'maxlength'=> '40',
              										'size' => '25'
										);
										echo form_input($attr).'</div>';
										echo '<div>'.form_label('Профессия: ','friendlabel');
										$attr = array(
													'name' => 'profession',
              										'id'   => 'friendprof',
              										'value'=> set_value('profession'),
													'class'=> 'textfield',
              									'maxlength'=> '50',
              										'size' => '25'
										);
										echo form_input($attr);
										echo form_label('Фото ','friendlabel');
										$attr = array(
													'type' => 'file',
													'name' => 'userfile',
              										'id'   => 'albumphoto',
												  'accept' => 'image/jpeg,png,gif',
              									'maxlength'=> '250',
              										'size' => '15'
										);
										echo form_input($attr).'</div>';
								?>
								</div>
							</div>
							<div class="post-header-friend">
								<div class="post-title">
									<?php
										echo '<div>'.form_label('Соц.сеть:  ','friendlabel');
										$attr = array(
													'name' => 'social1',
              										'id'   => 'friendsocial1',
              										'value'=> set_value('social1'),
													'class'=> 'textfield',
              									'maxlength'=> '40',
              										'size' => '25'
										);
										echo form_input($attr);
										echo form_label('Ссылка:  ','friendlabel');
										$attr = array(
													'name' => 'hrefsocial1',
              										'id'   => 'friendhrefsoc1',
              										'value'=> set_value('hrefsocial1'),
													'class'=> 'textfield',
              									'maxlength'=> '50',
              										'size' => '40'
										);
										echo form_input($attr).'</div>';
										echo '<div>'.form_label('Соц.сеть:  ','friendlabel');
										$attr = array(
													'name' => 'social2',
              										'id'   => 'friendsocial2',
              										'value'=> set_value('social2'),
													'class'=> 'textfield',
              									'maxlength'=> '40',
              										'size' => '25'
										);
										echo form_input($attr);
										echo form_label('Ссылка:  ','friendlabel');
										$attr = array(
													'name' => 'hrefsocial2',
              										'id'   => 'friendhrefsoc2',
              										'value'=> set_value('hrefsocial2'),
													'class'=> 'textfield',
              									'maxlength'=> '50',
              										'size' => '40'
										);
										echo form_input($attr).'</div>';
									?>
								</div>
							</div>
							<div class="text">
							<?php
								echo '<div class="post-title">'.form_label('Описание друга:  ','friendlabel').'</div>';
								$attr =array(
											'name' 		=> 'note',
              								'id'   		=> 'friendnote',
              								'value'		=> set_value('note'),
											'class'		=> 'textfield',
              								'cols'		=> '81',
              								'rows' 		=> '6',
											'onkeypress'=> 'return isNotMax(event)'
								);
								echo '<div>'.form_textarea($attr).'</div>';
								$attr =array(
											'name' => 'btsabmit',
              								'id'   => 'btnsabmit',
              								'value'=> 'Добавить карточку',
											'class' => 'senden'
								);
								echo form_submit($attr);
								echo form_close(); 
							?>
							</div>
						</div>
						<div class="blog-r"> </div>
						<div class="clear"></div>
					</div>
					<div class="blog-bottom">
						<div class="blog-bl"></div>
						<div class="blog-b"></div>
						<div class="blog-br"></div>						
					 </div>
					 
					<div class="clear"></div>
				</div>
			</div>
		</div>