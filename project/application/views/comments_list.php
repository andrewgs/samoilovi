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
					<a href="<?php echo $data1['baseurl'].'admin'; ?>">&laquo; Вернуться назад</a>
					<?php if($data3 > 5){?>
					<div class="grid_3 omega">
						<div class='pagination'><?php echo $data2['pager']; ?></div>
					</div>
				<?php } ?>
				</div>
			</div>
		<?php
			foreach ($data2['query'] as $comments){
		?>
			<div class="container_16">
				<div class="comment grid_10">
					<?php
						echo '<div class="post-date">'.$comments->blg_title;
						echo '&nbsp;&nbsp;'.$comments->blg_date.'</div>';
						echo '<hr />';
						echo '<p><span class="user" id="'.$comments->cmnt_id.'">';
						if (!empty($comments->cmnt_web)){
							echo '<a title="" target="_blank" href="'.$comments->cmnt_web.'">'.$comments->cmnt_usr_name.'</a></span>';
						}else{
							echo $comments->cmnt_usr_name.'</span>';	
						}						
						echo '<span class="dates">&nbsp;'.$comments->cmnt_usr_date.'</span></p>';
						echo '<p>'.$comments->cmnt_text.'</p>';
						$text = 'Редактировать';
						$str_uri = '/admin/commentedit/'.$comments->cmnt_blog_id.'/'.$comments->cmnt_id;
						echo '<div>'.anchor($str_uri,$text).' | ';
						$text = 'Удалить';
						$str_uri = '/admin/commentdestroy/'.$comments->cmnt_blog_id.'/'.$comments->cmnt_id;
						$attr = array('class'=>'delcmnt');
						echo anchor($str_uri,$text,$attr).'</div>';
					?>
				</div>
				<div class="clear"></div>			
			</div>
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