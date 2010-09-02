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
		echo '<link rel="stylesheet" href="'.$data1['baseurl'].'css/datepicker/jquery.ui.all.css" type="text/css" media="screen"/>';
		
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/datepicker/jquery-1.4.2.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/datepicker/jquery.bgiframe-2.1.1.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/datepicker/jquery.ui.core.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/datepicker/jquery.ui.datepicker-ru.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/datepicker/jquery.ui.datepicker.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/datepicker/jquery.ui.widget.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'ckeditor/ckeditor.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'ckeditor/adapters/jquery.js"></script>';
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'ckfinder/ckfinder.js"></script>';
		?> 
		<script type="text/javascript"> 
			$(function(){
			
				$("#userdate").datepicker($.datepicker.regional['ru']);
				
				$("div.blog-content").each(function(){
					$(this).parents("div.blog-center:first").css('height', $(this).height()+10);
				});
				
				var config = {
					skin : 'v2',
					removePlugins : 'scayt',
					resize_enabled: false,
					toolbar:
					[
						['Source','-','Preview','-','Templates'],
						['Cut','Copy','Paste','PasteText'],
						['Undo','Redo','-','SelectAll','RemoveFormat'],
						'/',
						['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
						['NumberedList','BulletedList','-','Outdent','Indent'],
						['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
						['Link','Unlink'],
						'/',
						['Format','-'],
						['Image','Table','HorizontalRule','SpecialChar','-'],
						['Maximize', 'ShowBlocks']
					]
				};

				$('#idblogtext').ckeditor(config);
				var editor = $('#idblogtext').ckeditorGet();

				CKFinder.setupCKEditor( editor, '<?php echo $data1['baseurl'].'ckfinder/'; ?>') ;
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
		<div class="container_12">
			<div id="internal_nav" class="grid_4 suffix_8">
				<a href="<?php echo $data1['backuri']; ?>">&laquo; Вернуться к событию</a>
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
								foreach ($data2 as $row){
									
										$attr = array('name' => 'formcommentedit','id' => 'formcomment');
										echo form_open('admin/commentupdate',$attr);
											
											echo form_hidden('id',$this->uri->segment(4));
											echo form_hidden('blog_id',$this->uri->segment(3));
											echo '<div>'.form_label('Выполните изменения','cmntlabel').'</div>';
											$attr = array(
													'name' => 'user_name',
              										'id'   => 'username',
              										'value'=> $row->cmnt_usr_name,
              									'maxlength'=> '40',
              										'size' => '20'
											);
											echo '<div>'.form_input($attr);
											$attr = array(
													'name' => 'user_email',
              										'id'   => 'useremail',
              										'value'=> $row->cmnt_usr_email,
              									'maxlength'=> '45',
              										'size' => '20'
											);											
											echo form_input($attr);
											$attr = array(
													'name' => 'homepage',
              										'id'   => 'edit-homepage',
              										'value'=> $row->cmnt_web,
              									'maxlength'=> '255',
              										'size' => '20'
											);											
											echo form_input($attr);
											$attr = array(
													'name' => 'user_date',
              										'id'   => 'userdate',
              										'value'=> $row->cmnt_usr_date,
              									'maxlength'=> '45',
              										'size' => '15'
											);
											echo form_input($attr).'</div>';      
									?>
									<a name="wedding_waltz"></a></div>
									<div class="post-date"></div>
								</div>
								<div class="text">
								<?php
									$attr =array(
											'name' => 'cmnt_text',
              								'id'   => 'cmnttext',
              								'value'=> $row->cmnt_text,
              								 'cols'=> '81',
              								'rows' => '10'
									);
								}
								echo '<div>'.form_textarea($attr).'</div>';
								$attr =array(
											'name' => 'btsabmit',
              								'id'   => 'btnsabmit',
              								'value'=> 'Сохранить изменения',
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