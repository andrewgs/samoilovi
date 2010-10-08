<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <meta http-equiv="Pragma" content="no-cache"/> 
    <meta http-equiv="Cache-Control" content="no-cache"/> 
    <meta http-equiv="Expires" content="1 Jan 2000 0:00:00 GMT"/> 
	<meta name="language" content="ru" /> 
    <meta name="description" content=<?php echo $pagevalue['desc']; ?>/>
    <meta name="keywords" content=<?php echo $pagevalue['keyword']; ?>/>
    <title><?php echo $pagevalue['title']; ?></title> 
<?php
	define("CRLT", "\n");
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/reset.css" type="text/css" />'.CRLT; 
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/960.css" type="text/css" />'.CRLT; 
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/style.css" type="text/css" />'.CRLT;
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/pirobox.css" type="text/css" />'.CRLT;
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/uploadify.css" type="text/css" />'.CRLT;
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/uploadify.styling.css" type="text/css" />'.CRLT;
	echo '<link rel="stylesheet" href="'.$pagevalue['baseurl'].'css/uploadify.jGrowl.css" type="text/css" />'.CRLT;
	
	echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/jquery.min.js"></script>'.CRLT;
	echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/pirobox.min.js"></script>'.CRLT;
	echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/jquery.confirm.js"></script>'.CRLT;
	echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/swfobject.js"></script>'.CRLT;
	echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/jquery.uploadify.js"></script>'.CRLT;
	echo '<script type="text/javascript" src="'.$pagevalue['baseurl'].'js/jquery.jgrowl_minimized.js"></script>'.CRLT;
?> 
	<script type="text/javascript"> 
		$(document).ready(function(){
			$().piroBox({
				my_speed: 400,
				bg_alpha: 0.1,
				slideShow : true,
				slideSpeed : 4,
				close_all : '.piro_close,.piro_overlay' 
			});
			$('a.delete').confirm();
			$("#showform").click(function(){
				if ($("fieldset.uploadform").is(":hidden")){
					$("fieldset.uploadform").slideDown("slow");
					$("#showform").text('Отменить загрузку');
					$("#showuploadif").hide(1000);
				}else{
					$("fieldset.uploadform").hide(1000);
					$("#showform").text('Добавить одну фотографию');
					$("#showuploadif").show(1000);
				}
		    });
			$("#showuploadif").click(function(){
				if ($("fieldset.multiupload").is(":hidden")){
					$("fieldset.multiupload").slideDown("slow");
					$("#showuploadif").text('Отменить загрузку');
					$("#showform").hide(1000);
				}else{
					$("fieldset.multiupload").hide(1000);
					$("#showuploadif").text('Добавить несколько фотографий');
					$("#showform").show(1000);
				}
		    });
			$("#fileupload").uploadify({
					'uploader'		: '<?= $pagevalue['baseurl']; ?>swf/uploadify.swf',
					'script'		: '<?= $pagevalue['baseurl']; ?>scripts/uploadify.php',/*admin/photo-multiupload',*/
					'cancelImg'		: '<?= $pagevalue['baseurl']; ?>images/uploadify/cancel.png',
					'displayData'	: 'speed',
					'folder'		: '../../../../images/',
					'buttonImg'		: '<?= $pagevalue['baseurl']; ?>images/uploadify/browseBtn.png',
					'fileDesc'		: 'Файлы рисунков',
					'fileExt'		: '*.jpg;*.jpeg;*.png;*.gif',
					'multi'			: true,
					'auto'			: true,
					'rollover'		: true,
					'height'        : 24,
					'width'         : 80,
					'sizeLimit'		: 10485760,
					onComplete: function (a, b ,c, d, e) {
						var size = Math.round(c.size/1024);
						$.jGrowl('<p></p>'+c.name+' - '+size+'KB', {
							theme: 	'success',
							header: 'Загрузка выполнена',
							life:	4000,
							sticky: false
						});
					},
					onCancel: function (a, b, c, d){
					var msg = "Cancelled uploading: "+c.name;
						$.jGrowl('<p></p>'+msg,{
							theme: 	'warning',
							header: 'Загрузка отменена',
							life:	4000,
							sticky: false
						});
					}
				});
		});
	</script>  	
</head>
<body>
	<div id="main-wrap">
		<?php $this->load->view('admin/header-admin',array('pagevalue'=>$pagevalue)); ?>
		<div id="content">
			<div class="container_12">
				<div id="internal_nav" class="grid_4">
					<a href="<?php echo $pagevalue['baseurl'].$pagevalue['backpath']; ?>">&nbsp;&larr;&nbsp; Вернуться назад</a>
				</div>
			</div>
			<div class="clear"></div>
			<div class="container_12">
				<div class="grid_12">
					<?php echo form_error('imagetitle').'<div class="clear"></div>'; ?>
					<?php echo form_error('userfile').'<div class="clear"></div>'; ?>
					<div class="grid_3">
						<button id="showform">Добавить одну фотографию</button>
					</div>
					<div class="grid_3">
						<button id="showuploadif">Добавить несколько фотографий</button>
					</div>
					<div class="clear"></div>
					<fieldset class="uploadform">
						<legend><strong>Загрузка одной фотографии</strong></legend>
					<?php echo form_open_multipart('admin/photo-gallary/'.$pagevalue['album']);?>
					<?php echo form_hidden('album',$pagevalue['album']); ?>
						<?php echo '<div>'.form_label('Описание: ','uploadlabel'); ?>
						<?php $attr = array(
								'name'		=> 'imagetitle',
								'id'  		=> 'imagetitle',
								'class'		=> 'textfield',
								'value'		=> set_value('imagetitle'),
								'maxlength'	=> '100',
								'size' 		=> '30'
							);
							echo form_input($attr).'</div>';?>
						<?php echo '<div>'.form_label('Фото: ','albumlabel'); ?>
						<?php $attr = array(
								'type'		=> 'file',
								'name'		=> 'userfile',
								'id'		=> 'uploadimage',
								'accept'	=> 'image/jpeg,png,gif',
						);
						echo form_input($attr).'</div>'; ?>
						<hr>
						<?php $attr =array(
								'name' 		=> 'btnsubmit',
								'id'   		=> 'btnsubmit',
								'class' 	=> 'senden',
								'value'		=> 'Загрузить'
							);
						echo form_submit($attr);?>							
					<?php echo form_close(); ?>
					</fieldset>
					<fieldset class="multiupload">
						<legend><strong>Загрузка нескольких фотографий</strong></legend>
						Выбрать фото:
						<div id="fileupload">У Вас проблемы с javascript</div> 
					</fieldset>
				</div>
				<?php if($msg['status'] == 1){
						echo '<div class="message">';
							echo $msg['message'].'<br/>'.$msg['error'];
							echo $msg['saccessfull'];
						echo '</div>';
						echo '<div class="clear"></div>';
					} ?>
			<?php if(count($images)):?>
				<div id="photo-gallery" class="container_12">
				<?php for($i = 0;$i < count($images);$i++): ?>
					<div class="grid_3 photo-album">
						<div class="album-background images">
						<?php $link = $pagevalue['baseurl'].'big/viewimage/'.$images[$i]['img_id']; ?>
						<?php $text = '<img src="'.$pagevalue['baseurl'].'small/viewimage/'.$images[$i]['img_id'].'" 
									alt="'.$images[$i]['img_title'].'" '.'title="'.$images[$i]['img_title'].'"/></a>'; ?>
						<?php $attr = array('class'=>'pirobox'); ?>
						<?php echo anchor($link,$text,$attr); ?>
						</div>
						<div class="images-text"> 
							<div class="image-title"><?php echo $images[$i]['img_title']; ?></div>
						</div>
						<div class="album-controls">
							<?php
								$text = 'Удалить';
								$str_uri = 'admin/photo-destory/'.$images[$i]['img_id'];
								$attr = array('class'=>'delete');
								echo anchor($str_uri,$text,$attr);
							?>
						</div>
						<div class="clear"></div>
					</div>
				<?php endfor; ?>
				</div>
				<div class="clear"></div>
			<?php endif; ?>
			</div>
		</div>
		<div class="push"></div>
	</div>
	<?php $this->load->view('footer'); ?>