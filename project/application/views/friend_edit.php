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
					<a href="<?php echo $data1['baseurl'].'admin/friendsview'; ?>">&laquo; Вернуться к списку друзей</a>
				</div>
			</div>
			<div class="container_16">
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
							<div class="post-header">
								<div class="post-title">
								<?php
								foreach ($data2 as $friend){
									$attr = array('name' => 'formfriendedit','id' => 'formfriend');
									echo form_open_multipart('admin/friendupdate',$attr);
										
										echo form_hidden('fr_id',$friend->fr_id);
										echo form_hidden('oldphoto',$friend->fr_image);
										echo form_hidden('soc_id1',$data3[0]['id']);
										echo form_hidden('soc_id2',$data3[1]['id']);
										$attr = array(
													'name' => 'name',
              										'id'   => 'friendname',
              										'value'=> $friend->fr_name,
              									'maxlength'=> '40',
              										'size' => '25'
										);
										echo '<div>'.form_input($attr);
										$attr = array(
													'name' => 'profession',
              										'id'   => 'friendprof',
              										'value'=> $friend->fr_profession,
              									'maxlength'=> '50',
              										'size' => '25'
										);
										echo form_input($attr);
										$attr = array(
													'type' => 'file',
													'name' => 'userfile',
              										'id'   => 'albumphoto',
												  'accept' => 'image/jpeg,png,gif',
              									'maxlength'=> '250',
              										'size' => '15'
										);
										echo form_input($attr).'</div>';
										
										$attr = array(
													'name' => 'social1',
              										'class'   => 'friendsocial',
              										'value'=> $data3[0]['social'],
              									'maxlength'=> '40',
              										'size' => '15'
										);
										echo '<div>'.form_input($attr);
										$attr = array(
													'name' => 'hrefsocial1',
              										'class'   => 'friendhrefsoc',
              										'value'=> $data3[0]['href'],
              									'maxlength'=> '50',
              										'size' => '25'
										);
										echo form_input($attr);
										
										$attr = array(
													'name' => 'social2',
              										'class'   => 'friendsocial',
              										'value'=> $data3[1]['social'],
              									'maxlength'=> '40',
              										'size' => '15'
										);
										echo form_input($attr);
										$attr = array(
													'name' => 'hrefsocial2',
              									 'class'   => 'friendhrefsoc',
              										'value'=> $data3[1]['href'],
              									'maxlength'=> '50',
              										'size' => '25'
										);
										echo form_input($attr).'</div>';
								}
								?>
								<a name="wedding_waltz"></a></div>
								<div class="post-date"></div>
							</div>
							<div class="text">
							<?php
								$attr =array(
											'name' => 'note',
              								'id'   => 'friendnote',
              								'value'=> $friend->fr_note,
              								 'cols'=> '81',
              								'rows' => '10'
								);
								echo '<div>'.form_textarea($attr).'</div>';
								
								$attr =array(
											'name' => 'btsabmit',
              								'id'   => 'btnsabmit',
              								'value'=> 'Сохранить карточку',
											'class' => 'senden'
								);
								echo form_submit($attr);
							/*	$attr =array(
									'name' 		=> 'btcancel',
									'id'   		=> 'btcancel',
									'value'		=> 'Отмена',
									'class'		=> 'senden',
									'type'		=> 'button',
									'onclick'	=> 'history.go(-1)'
								);
								echo form_input($attr);*/
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