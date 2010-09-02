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
		define("CRLT", "\n");
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/reset.css" type="text/css" media="screen"/>'.CRLT;
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/960.css" type="text/css" media="screen"/>'.CRLT;
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/style.css" type="text/css" media="screen"/>'.CRLT;
		echo '<link rel="stylesheet" href="'.$data['baseurl'].'css/datepicker/jquery.ui.all.css" type="text/css" media="screen"/>'.CRLT;
		
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/datepicker/jquery-1.4.2.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/datepicker/jquery.bgiframe-2.1.1.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/datepicker/jquery.ui.core.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/datepicker/jquery.ui.datepicker-ru.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/datepicker/jquery.ui.datepicker.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'js/datepicker/jquery.ui.widget.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'ckeditor/ckeditor.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'ckeditor/adapters/jquery.js"></script>';
		echo '<script type="text/javascript" src="'.$data['baseurl'].'ckfinder/ckfinder.js"></script>';
		?>
		<script type="text/javascript">
		$(function() {
			$("#blogdate").datepicker($.datepicker.regional['ru']);
			
			$("div.blog-content").each(function(){
					$(this).parents("div.blog-center:first").css('height', $(this).height()+150);
				});
				
			var config = {
				skin : 'v2',
				removePlugins : 'scayt',
				resize_enabled: false,
				height: '200px',
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

			// Initialize the editor.
			// Callback function can be passed and executed after full instance creation.
			$('#blogtext').ckeditor(config);
			var editor = $('#blogtext').ckeditorGet();

			CKFinder.setupCKEditor( editor, "<?php echo $data['baseurl'].'ckfinder/'; ?>") ;
		});
		</script>
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
		<div class="container_12">
			<div id="internal_nav" class="grid_4 suffix_8">
				<a href="<?php echo $data['baseurl'].'admin/blogview'; ?>">&laquo; Вернуться к списку событий</a>
			</div>
		</div>
		<div class="container_16">
			<?php
				echo form_error('title').'<div class="clear"></div>';
				echo form_error('text').'<div class="clear"></div>';
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
						<?php
							$attr = array('name' => 'formblognew','id' => 'formblog');
							echo form_open('admin/bloginsert',$attr);
						?>
						<div class="post-header">
							<div class="post-title">
						<?php	
								echo '<div>'.form_label('Оглавление','bloglabel').'</div>';
								$attr = array(
											'name' 		=> 'title',
											'id'   		=> 'blogtitle',
											'class'		=> 'textfield',
											'value'		=> set_value('title'),
											'maxlength'	=> '200',
											'size' 		=> '40',
									);
								echo '<div>'.form_input($attr); 
								echo form_label(' Дата: ','bloglabel');    
								$attr = array(
									'name' => 'date',
									'id'   => 'blogdate',
									'value'=> date("d/m/Y"),
								'maxlength'=> '20',
									'size' => '10',
								'readonly' => TRUE
								);
								echo form_input($attr).'</div>';
						?>
							</div>
						</div>
						<div class="text">
						<?php
							echo '<div class="post-title">'.form_label(' Содержимое: ','bloglabel').'</div>';
							$attr =array(
										'name' => 'text',
										'id'   => 'blogtext',
										'class'=> 'textfield',
										'value'=> set_value('text'),
										'cols'=> '81',
										'rows' => '10'
								);
							echo '<div>'.form_textarea($attr).'</div>';
							
							$attr =array(
										'name' 	=> 'btsabmit',
										'id'   	=> 'btnsabmit',
										'class' => 'senden',
										'value'	=> 'Создать запись'
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