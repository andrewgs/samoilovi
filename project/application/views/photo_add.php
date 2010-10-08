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
		
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.min.js"></script>'.CRLT;
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
				<div id="global_nav" class="grid_7">
					<a class="logout" href="<?php echo $data1['baseurl'].'admin/logoff'; ?>">Завершить сеанс</a>
				</div>	
			</div>
			<div class="clear"></div>
		</div>
		<div id="content">
			<div class="container_12">
				<div id="internal_nav" class="grid_4 suffix_8">
				<a href="<?php echo $data1['baseurl'].$data2['back']; ?>">&laquo; Вернуться к фотографиям</a>
				</div>
			</div>		
			<div class="container_16">
				<div class="grid_12">
				<?php
					if($msg['status'] == 1){
						echo '<div class="message">';
							echo $msg['message'].'<br/>'.$msg['error'];
							echo $msg['saccessfull'];
						echo '</div>';
						echo '<div class="clear"></div>';
					}
				?>
				</div>
				<div class="clear"></div>
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
							<?php
								$attr = array('name' => 'formphotoadd','id' => 'formphoto');
								echo form_open_multipart($data2['form'],$attr);
							?>
							<div class="post-header">
								<div class="post-title">
								<?php
									echo form_label('Описание фото','imageslabel');
									$attr = array(
											'name'		 => 'imagetitle',
											'id'  		 => 'imagetitle',
											'value'		 => '',
											'maxlength'	 => '100',
											'size' 		 => '60'
											);
									echo '<div>'.form_input($attr).'</div>';
								?>
								<a name="wedding_waltz"></a></div>
								<div class="post-date"></div>
							</div>
							<div class="text">
							<?php
								echo '<hr>';
								$attr = array(
											'type' => 'file',
											'name' => 'userfile',
											'id'   => 'photos',
											'accept' => 'image/jpeg,png,gif',
											'maxlength'=> '250',
											'size' => '50'
										);
								echo '<div>'.form_input($attr).'</div>';
								echo '<hr>';
								$attr =array(
										'name' => 'btsabmit',
										'id'   => 'btnsabmit',
										'value'=> 'Загрузить',
										'class' => 'senden'
								);
								echo '<div id="bt_submit">'.form_submit($attr).'</div>';							
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
		<div class="push"></div>
	</div>