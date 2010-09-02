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
		echo '<script type="text/javascript" src="'.$data1['baseurl'].'js/jquery.confirm.js"></script>';
		?> 
		<script type="text/javascript"> 
			$(function(){
				$("div.blog-content").each(function(){
					$(this).parents("div.blog-center:first").css('height', $(this).height()+10);
				});
			});
			$(document).ready(function() {	
				$('a.delcmnt').confirm();
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
					<a href="<?php echo $data1['baseurl'].'admin/blogview'; ?>">&laquo; Вернуться к списку событий</a>
				</div>
			</div>
			<?php
			foreach ($data2 as $row){
			?>
			<div class="container_16">
				<div id="blog" class="grid_12">
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
								<?php
									echo '<div class="post-title">
										<a name="blog_'.$row->blg_id.'"></a>'.$row->blg_title.'</div>';
									echo '<div class="post-date">'.$row->blg_date.'</div>';
								?>
							</div>
							<div class="text">
								<?php echo $row->blg_text;?>
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
			<?php
			}
			foreach($data3 as $row){
			?>
			<div class="container_12">
				<div class="comment grid_10">
					<?php
						echo '<span class="user" id="'.$row->cmnt_id.'">';
						if (!empty($row->cmnt_web)){
							echo '<a title="" target="_blank" href="'.$row->cmnt_web.'">'.$row->cmnt_usr_name.'</a></span>';
						}else{
							echo $row->cmnt_usr_name.'</span>';	
						}						
						echo '<span class="dates">'.$row->cmnt_usr_date.'</span>';
						echo '<p>'.$row->cmnt_text.'</p>';
						$text = 'Редактировать';
						$str_uri = '/admin/commentedit/'.$row->cmnt_blog_id.'/'.$row->cmnt_id;
						echo '<div>'.anchor($str_uri,$text).' | ';
						$text = 'Удалить';
						$str_uri = '/admin/commentdestroy/'.$row->cmnt_blog_id.'/'.$row->cmnt_id;
						$attr = array('class'=>'delcmnt');
						echo anchor($str_uri,$text,$attr).'</div>';						
					?>
				<div class="clear"></div>
				</div>			
			</div>
			<?php
			}
				echo form_error('user_name').'<div class="clear"></div>';
				echo form_error('user_email').'<div class="clear"></div>';
				echo form_error('cmnt_text').'<div class="clear"></div>';
			?>
			<div class="container_16">
				<div id="comment-form-content" class="grid_10 form-content">
					<?php
						$attr = array('name' => 'comment-form','id' => 'comment-form', 'accept-charset' => 'UTF-8');
						echo form_open('admin/commentsinsert',$attr);
						echo form_hidden('blog_id',$this->uri->segment(3));
					?>
							<div id="edit-name-wrapper" class="form-item">
							<?php
								echo form_label('Ваше имя: <span title="Это поле обязательно для заполнения." class="form-required">*</span>','edit-name');
								$attr = array(
									'name' => 'user_name',
									'id'   => 'username',
									'value'=> $data4['firstname'].' '.$data4['secondname'],
									'maxlength'=> '60',
									'size' => '30',
									'class' => 'form-text required'
								);
								echo form_input($attr);					
							?>
							</div>
							<div id="edit-mail-wrapper" class="form-item">
								<?php
									echo form_label('E-mail: <span title="Это поле обязательно для заполнения." class="form-required">*</span>','edit-mail');
									$attr = array(
										'name'		=> 'user_email',
										'id'   		=> 'useremail',
										'value'		=> $data4['email'],
										'maxlength'	=> '64',
										'size' 		=> '30',
										'class' 	=> 'form-text required'
									);
									echo form_input($attr);		
								?>
								<div class="description">
									Содержимое данного поля сохранится в нашей базе данных и не 
									будет выводиться на экран.
								</div>
							</div>
							<div id="edit-homepage-wrapper" class="form-item">
								<?php
									echo form_label('Веб-сайт:','edit-homepage');
									$attr = array(
										'name' 		=> 'homepage',
										'id'   		=> 'edit-homepage',
										'value'		=> '',
										'maxlength'	=> '255',
										'size' 		=> '30',
										'class' 	=> 'form-text'
									);
									echo form_input($attr);		
								?>
							</div>
							<div id="edit-comment-wrapper" class="form-item">
							<?php
								echo form_label('Комментарий: <span title="Это поле обязательно для заполнения." class="form-required">*</span>','edit-comment');
							?>														
								<div class="resizable-textarea">
									<span>
									<?php
										$attr =array(
											'name' 	=> 'cmnt_text',
											'id'   	=> 'cmnttext',
											'value'	=> '',
											'cols'	=> '60',
											'rows' 	=> '15',
											'class' => 'form-textarea resizable required textarea-processed'
										);
										echo form_textarea($attr);
									?>										
									</span>
								</div>
							</div>
							<?php
								$attr =array(
									'name' 	=> 'op',
									'id'   	=> 'ajax-comments-submit',
									'value'	=> 'Добавить комментарий',
									'class' => 'senden'
								);
								echo form_submit($attr);
							?>
					<?php
						echo form_close(); 							
					?>
				</div>
				<div class="clear"></div>
			</div>			
		</div>
		<div class="push"></div>
	</div>