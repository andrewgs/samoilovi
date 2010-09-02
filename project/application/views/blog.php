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
		<script type="text/javascript"> 
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-17193616-1']);
			_gaq.push(['_trackPageview']);
 
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>	
</head>
<body>
	<div id="main-wrap">
		<?php
			if($data1['islogin']){
				echo '<div id="admin-panel">';
				echo '<span>Вы вошли как Администратор</span>
					 <a class="logout" href="'.$data1['baseurl'].'admin/logoff">Завершить сеанс</a>';
				echo '</div>';
			}
		?>
		<div id="header">
			<div class="container_16">
				<div id="logo" class="grid_4 suffix_5">
				<?php echo anchor('','<img src="'.$data1['baseurl'].'images/logo.png" alt="Samoilovi.ru"/>'); ?>
				</div>
				<div class="grid_7">
					<ul id="header-menu">
						<?php
							echo "<li>".anchor('about','О нас')."</li>";
							echo "<li>".anchor('friends','Друзья')."</li>";
							echo "<li>".anchor('blog','События')."</li>";
							echo "<li>".anchor('album','Фотографии')."</li>";							
						?>
					</ul>
				</div>	
			</div>
			<div class="clear"></div>
		</div>
		<div id="content">
			<?php if($data3 > 5){?>
				<div class="container_12">
					<div class="grid_3 omega">
						<div class='pagination'><?php echo $data2['pager']; ?></div>
					</div>
				</div>
				<div class="clear"></div>
			<?php
			}
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
									$str_uri = '/commentslist/'.$row->blg_id;
									echo '<div class="cnt_comments">'.anchor($str_uri,$text).'</div>';
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
		if($data3 > 5){?>
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
