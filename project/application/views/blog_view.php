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
				$('a.delblog').confirm();
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
				<div id="internal_nav" class="grid_6">
					<a href="<?php echo $data1['baseurl'].'admin/blognew'; ?>">Создать новое событие &raquo;</a>
				</div>
				<?php if($data3 > 5){?>
					<div class="grid_3 omega">
						<div class='pagination'><?php echo $data2['pager']; ?></div>
					</div>
				<?php } ?>
			</div>	
			<div class="clear"></div>			
		<?php
			foreach ($data2['query'] as $row){
		?>
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
									echo '<a name="blog_'.$row->blg_id.'"></a>'.$row->blg_title.'</div>';
									echo '<div class="post-date">'.$row->blg_date.'</div>';
								?>
							</div>
							<div class="text">
								<?php
									echo $row->blg_text;
									$text = $row->blg_cnt_cmnt.' комментариев &raquo;';
									$str_uri = '/admin/commentsview/'.$row->blg_id;;
									echo '<div class="cnt_comments">'.anchor($str_uri,$text).' | ';
									$text = 'Редактировать';
									$str_uri = '/admin/blogedit/'.$row->blg_id;
									echo anchor($str_uri,$text).' | ';
									$text = 'Удалить';
									$str_uri = '/admin/blogdestroy/'.$row->blg_id;
									$attr = array('class'=>'delblog');
									echo anchor($str_uri,$text,$attr).'</div>';
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
			<div class="clear"></div>
		<?php
		}
		if($data3 > 5){
		?>
			<div class="container_12">
				<div class="grid_3 omega">
					<div class='pagination'><?php echo $data2['pager']; ?></div>
				</div>
			</div>		
			<div class="clear"></div>
		<?php 
		}
		?>
		</div>
		<div class="push"></div>	 
		</div>
	</div>
	