<?php
	
	class Admin extends Controller{
	
		var $months = array("01"=>"января",
							"02"=>"февраля",
							"03"=>"марта",
							"04"=>"апреля",
							"05"=>"мая",
							"06"=>"июня",
							"07"=>"июля",
							"08"=>"августа",
							"09"=>"сентября",
							"10"=>"октября",
							"11"=>"ноября",
							"12"=>"декабря"
						);		
		
		var $message = array(
							'error' 		=> '',
							'saccessfull' 	=> '',
							'message' 		=> '',
							'status'		=> 0
							);
		
		function Admin(){
		
			parent::Controller();
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->model('blogmodel');
			$this->load->model('cmntmodel');
			$this->load->model('authentication');
			$this->load->model('albummodel');
			$this->load->model('friendsmodel');
			$this->load->model('socialmodel');
			$this->load->model('unionmodel');
			$this->load->model('imagesmodel');
			$this->load->library('session');
			$this->load->library('upload');
			$this->load->library('image_lib');
			$this->load->library('pagination');
			$this->load->library('form_validation');
			if ($this->session->userdata('logon') == '76f1847d0a99a57987156534634a1acf') return;
			if ($this->uri->segment(2)==='login') return;
			redirect('admin/login');
		}
		
		function index(){
			$data = array(
							'title' => "Samoilovi.ru | Администрирование",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$this->load->view('admin', array('data'=>$data));
			$this->load->view('footer');	
		}
		
		function blognew(){
			$data = array(
							'title' => "Samoilovi.ru | Администрирование | Создание блога",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$this->load->view('blog_new',array('data'=>$data));
			$this->load->view('footer');
		}
				
		function bloginsert(){
		
			$this->form_validation->set_rules('title', '"Оглавление"', 'required');
			$this->form_validation->set_rules('text', '"Содержимое"', 'required');
			
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			
			if ($this->form_validation->run() == FALSE){
				
				$this->blognew();
			}else{
			
				$pattern = "/(\d+)\/(\w+)\/(\d+)/i";
				$replacement = "\$3-\$2-\$1";
				$_POST['date'] = preg_replace($pattern, $replacement, $_POST['date']);
				
				$this->blogmodel->insert_record_to_blog($_POST);
				redirect('admin/blogview');
			}
		}
		
		function blogedit(){
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Редактирование блога",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$id = $this->uri->segment(3);
			$blog = $this->blogmodel->get_blog_record($id);
			$data2 = array();
			
			foreach ($blog as $blg){
				
				$data2['date'] = $this->operation_date_slash($blg->blg_date);
				$data2['id'] = $id;
				$data2['cnt'] = $blg->blg_cnt_cmnt;
				$data2['text'] = $blg->blg_text;
				$data2['title'] = $blg->blg_title;
			}
						
        	$this->load->view('blog_edit',array('data1'=>$data1,'data2'=>$data2));
			$this->load->view('footer');			
		}
		
		function blogupdate(){
		
			$pattern = "/(\d+)\/(\w+)\/(\d+)/i";
			$replacement = "\$3-\$2-\$1";
			$_POST['date'] = preg_replace($pattern, $replacement, $_POST['date']);
			
			$this->blogmodel->update_record_to_blog($_POST);
			redirect('admin/blogview');
		}
		
		function blogdestroy(){
		
			$id = $this->uri->segment(3);
			$this->blogmodel->delete_record_to_blog($id);
			$this->cmntmodel->delete_records_to_comments($id);
			redirect('admin/blogview');
		}
		
		function blogview(){
			
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Просмотр блогов",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			
			$data3 = $this->blogmodel->count_records();
			
			$config['base_url'] = base_url().'/admin/blogview';	 		
        	$config['total_rows'] = $data3;							 	
        	$config['per_page'] =  5;   								
        	$config['num_links'] = 2;   	 							
        	$config['uri_segment'] = 3;								
			$config['first_link'] = 'В начало';
			$config['last_link'] = 'В конец';
			$config['next_link'] = 'Далее &raquo;';
			$config['prev_link'] = '&laquo; Назад';
			$config['cur_tag_open'] = '<b>';
			$config['cur_tag_close'] = '</b>';
						
			$from = intval($this->uri->segment(3));			
			$data2['query'] = $this->blogmodel->get_blog_limit_records(5,$from);
			
			foreach ($data2['query'] as $data){
				
				$data->blg_date = $this->operation_date($data->blg_date);
			}
			
			$this->pagination->initialize($config);
			$data2['pager'] = $this->pagination->create_links();
			
			if (empty($data2)) redirect('admin/blognew');
			
			$this->load->view('blog_view',array('data1'=>$data1,'data2'=>$data2,'data3'=>$data3));
			$this->load->view('footer');
		}
		
		function commentsview(){
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Просмотр коментариев блога",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$data4 = array(
						'firstname' => '',
						'secondname' => '',
						'email' => '',
						);
			$userinfo = $this->authentication->get_users_info('admin');
			
			foreach($userinfo as $value){
				$data4['firstname'] = $value->usr_first_name;
				$data4['secondname'] = $value->usr_second_name;
				$data4['email'] = $value->usr_email;
			}
			$id = $this->uri->segment(3);
			$data2 = $this->blogmodel->get_blog_record($id);
			
			foreach ($data2 as $data){
				
				$data->blg_date = $this->operation_date($data->blg_date);
			}
			
			$data3 = $this->cmntmodel->get_comments_to_blog($id);
			
			foreach ($data3 as $data){
				
				$data->cmnt_usr_date = $this->operation_date_slash($data->cmnt_usr_date);
			}
			
			$this->load->view('comments_view',array('data1'=>$data1,'data2'=>$data2,'data3'=>$data3,'data4'=>$data4));
			$this->load->view('footer');
		}
		
		function commentsinsert(){
		
			$this->form_validation->set_rules('user_name', '"Ваше имя"', 'required');
			$this->form_validation->set_rules('user_email', '"E-mail"', 'required|valid_email');
			$this->form_validation->set_rules('cmnt_text', '"Комментарий"', 'required');
			
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			
			if ($this->form_validation->run() == FALSE){

				redirect('admin/commentsview/'.$_POST['blog_id']);
			}else{
				
				if(isset($_POST['homepage']) and !empty($_POST['homepage'])){
					
					if(strncmp(strtolower($_POST['homepage']),'http://',7) != 0)
						$_POST['homepage'] = 'http://'.$_POST['homepage'];
				}
				
				$this->blogmodel->increment_cnt_comments_to_blog($_POST['blog_id']);			
				$this->cmntmodel->insert_record_to_comments($_POST);
				redirect('admin/commentsview/'.$_POST['blog_id']);
			}
		}
		
		function commentedit(){
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Редактирование комментария",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'backuri' => base_url().'admin/commentsview/'.$this->uri->segment(3)
						);
			$id = $this->uri->segment(4);
			$data2 = $this->cmntmodel->get_comment_record($id);
			
			foreach ($data2 as $date){
				
				$date->cmnt_usr_date = $this->operation_date_slash($date->cmnt_usr_date);				 
			}
			
        	$this->load->view('comment_edit',array('data1'=>$data1,'data2'=>$data2));
			$this->load->view('footer');			
		}
		
		function commentdestroy(){
			$blog_id = $this->uri->segment(3);
			$comment_id = $this->uri->segment(4);
			$this->blogmodel->decrement_cnt_comments_to_blog($blog_id);
			$this->cmntmodel->delete_record_to_comments($comment_id);
			redirect('admin/commentsview/'.$blog_id);
		}
		
		function commentupdate(){
			
			if(empty($_POST['user_name']) or (empty($_POST['user_email'])) or (empty($_POST['cmnt_text']))){
				
				if (!empty($_SERVER['HTTP_REFERER'])) 
    				header('Location: '.$_SERVER['HTTP_REFERER']);
					return FALSE;
			}
			
			$pattern = "/(\d+)\/(\w+)\/(\d+)/i";
			$replacement = "\$3-\$2-\$1";
			$_POST['user_date'] = preg_replace($pattern, $replacement, $_POST['user_date']);
			
			$this->cmntmodel->update_record_to_comments($_POST);
			redirect('admin/commentsview/'.$_POST['blog_id']);
		}
		
		function login(){
		
			$data1 = array(
						'title' => "Samoilovi.ru | Администрирование | Аутентификация пользователя",
						'desc' => "\"\"",
						'keyword' => "\"\"",
						'baseurl' => base_url(),
						);
			
			$this->setmessage('','','',0);
			
			if (isset($_POST['password']) and isset($_POST['login'])){
				
				if (empty($_POST['password']) or empty($_POST['password'])){
					
					$msg = $this->setmessage('Поля "Логин" и "Пароль" не могут быть пустымы!','','Ошибка авторизации!',1);
					
					$this->load->view('login',array('data1'=>$data1,'data2'=>'','msg'=>$msg));
					$this->load->view('footer');
					return FALSE;
				}
				
				$userinfo = $this->authentication->get_users_info($_POST['login']);
				if(empty($userinfo)){
						
					$text = 'Пользователь '.$_POST['login'].' не зарегистрирован в системе!';
					$msg = $this->setmessage($text,'','Ошибка авторизации!',1);
					
					$this->load->view('login',array('data1'=>$data1,'data2'=>'','msg'=>$msg));
					$this->load->view('footer');
					return FALSE;
				}else{
					foreach($userinfo as $value){
						$usrinfo['usr_password'] = $value->usr_password;
						$usrinfo['usr_login'] = $value->usr_login;
					}
					if ($usrinfo['usr_password'] === md5($_POST['password'])){
						$session_data = array('logon' => '76f1847d0a99a57987156534634a1acf');
                    	$this->session->set_userdata($session_data);
                    	redirect('admin');	
					}else{
						$msg = $this->setmessage('Введен не верный пароль.','','Ошибка авторизации!',1);
			
						$this->load->view('login',array('data1'=>$data1,'data2'=>$_POST['login'],'msg'=>$msg));
						$this->load->view('footer');
						return FALSE;
					}
				}
				$this->load->view('login',array('data1'=>$data1,'data2'=>'','msg'=>$this->message));
				$this->load->view('footer');
				return;
			}
			$msg = $this->setmessage('','','Введите логин и пароль для авторизации',1);
			
			$this->load->view('login',array('data1'=>$data1,'data2'=>'','msg'=>$msg));
			$this->load->view('footer');
		}
		
		function logoff(){
        	$this->session->sess_destroy();
            redirect('');
        }
		
		function albumsview(){
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Фоторепортажи",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'basepath' => getcwd()
						);
			$data2 = $this->albummodel->get_albums_info_list();

			$this->load->view('albums_view',array('data1' => $data1, 'data2' => $data2));
			$this->load->view('footer');			
		}
		
		function albumnew(){
			
			$pagevalue = array(
							'title' => "Samoilovi.ru | Администрирование | Создание нового фотоальбома",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$this->load->view('album_new',array('pagevalue'=>$pagevalue));
			$this->load->view('footer');
		}
		
		function albuminsert(){			
			
			$this->form_validation->set_rules('name', '"Каталог альбома"', 'required|callback_albumname_check');
			$this->form_validation->set_rules('title', '"Название"', 'required');
			$this->form_validation->set_rules('photo_title', '"Подпись"', 'required');
			$this->form_validation->set_rules('annotation', '"Описание альбома"', 'required');
			
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			
			if ($this->form_validation->run() == FALSE){
				$this->albumnew();
				$this->load->view('footer');
				return FALSE;
			}
			$uploaddirpath = getcwd().'/images';
			
			$albumdir = getcwd().'/albums/'.$_POST['name'];
			mkdir($albumdir) or die('Не возможно создать каталог!'); 
			
			$config['upload_path'] = $uploaddirpath;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = FALSE;			
							
			$this->upload->initialize($config);
							
			if ($this->upload->do_upload()){    
				
				$upload_data = $this->upload->data();			
				$_POST['userfile'] = 'images/'.$upload_data['file_name'];
				
				$config['image_library'] = 'gd2';
				$config['source_image']	= getcwd().'/images/'.$upload_data['file_name']; 
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['width']	 = 186;
				$config['height']	= 186;
				
				$this->image_lib->initialize($config);
				if (!$this->image_lib->resize()){
					// Обработка если рисунок не изменился.
					$_POST['userfile'] = 'images/albumempty.png';	
				}											
			}else{
				// обработка ошибки загрузки или не указан
				$_POST['userfile'] = 'images/albumempty.png';	
			}		
							
			$this->albummodel->insert_record_to_album($_POST);
			redirect('admin/albumsview');	
		}
		
		function albumname_check($name){
			
			$albumdir = getcwd().'/albums/'.$name;
			if (is_dir($albumdir)){
				$this->form_validation->set_message('albumname_check', 'Каталог альбома уже существует.');
				return FALSE;
			}else
				return TRUE;
		}
		
		function albumdestroy(){
			
			$id = $this->uri->segment(3);			
			$data = $this->albummodel->get_album_info($id);			
			
			foreach ($data as $album){
				
				$dirpath = getcwd().'/albums/'.$album->alb_name.'/';
				if(is_dir($dirpath)){				
					foreach (new DirectoryIterator($dirpath) as $file){
						if (!$file->isDot())						
							if(!unlink($dirpath.$file)){							
								//обработка события если не удалился файл						
							}					
					}
					if(!rmdir($dirpath)){					
						//обработка события если не удалилась папка
					}
				} 
				
				$photopath = getcwd().'/'.$album->alb_photo;
				
				if ($album->alb_photo != 'images/albumempty.png')
					if (file_exists($photopath))
						if(!unlink($photopath)){							
							//обработка события если не удалился файл						
						}					
			}
			$this->imagesmodel->image_album_delete($id);
			$this->albummodel->delete_record_to_album($id);
			redirect('admin/albumsview');
		}
		
		function albumedit(){
			$pagevalue = array(
							'title' => "Samoilovi.ru | Администрирование | Редактирование альбома",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$id = $this->uri->segment(3);
			$albuminfo = $this->albummodel->get_album_info($id);
			foreach ($albuminfo as $album){
				$oldphoto = $album->alb_photo;	
			}
        	$this->load->view('album_edit',array(
											'pagevalue'=>$pagevalue,
											'albuminfo'=>$albuminfo,
											'oldphoto'=>$oldphoto
											));
			$this->load->view('footer');	
		}
		
		function albumupdate(){			
			
			$uploaddirpath = getcwd().'/images';
			
			$config['upload_path'] = $uploaddirpath;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = FALSE;			
							
			$this->upload->initialize($config);
							
			if ($this->upload->do_upload()){    
				
				$upload_data = $this->upload->data();			
				$_POST['userfile'] = 'images/'.$upload_data['file_name'];
				
				if($_POST['oldphoto']!=$_POST['userfile'] and $_POST['oldphoto'] != 'images/albumempty.png'){
					
					$photopath = getcwd().'/'.$_POST['oldphoto'];
					if (file_exists($photopath))
						if(!unlink($photopath)){							
							//обработка события если не удалился файл						
						}					
				}
				$config['image_library'] = 'gd2';
				$config['source_image']	= getcwd().'/images/'.$upload_data['file_name']; 
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['width']	 = 186;
				$config['height']	= 186;
				
				$this->image_lib->initialize($config);
				if (!$this->image_lib->resize()){
				
					if($_POST['oldphoto'] != 'images/albumempty.png'){
					
						$photopath = getcwd().'/'.$_POST['oldphoto'];
						if (file_exists($photopath))
							if(!unlink($photopath)){							
								//обработка события если не удалился файл						
							}					
					}
					// Обработка если рисунок не изменился.
					$_POST['userfile'] = 'images/albumempty.png';	
				}											
			}else{
			
				// обработка ошибки загрузки или не указан
				$_POST['userfile'] = $_POST['oldphoto'];	
			}
			$this->albummodel->update_record_to_album($_POST);
			redirect('admin/albumsview');
		}
		
		function addphoto(){
			
			$data1 = array(
							  'title' => "Samoilovi.ru | Администрирование | Загрузка картинок",
							   'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
						);
			
			$msg = $this->setmessage('','','Выбирите картинку и нажмите кнопку "Загрузить".<br />(Поддерживаемые форматы: jpeg, png, gif)',1);
			$alb_id = $this->uri->segment(3);
			$data2['form'] = 'admin/album/'.$alb_id.'/addphoto';
			$data2['back'] = 'admin/album/'.$alb_id.'/images';
			
			if(isset($_POST['btsabmit'])){
				
				$albuminfo = $this->albummodel->get_album_info($alb_id);
				
				foreach ($albuminfo as $album){
					
					$uploadpath = 'albums/'.$album->alb_name;
					$uploaddir = getcwd().'/'.$uploadpath;
					$amt = $album->alb_amt;
					$_POST['alb_id'] = $album->alb_id;
					$alb_title = $album->alb_photo_title;
				}			
				$config['upload_path'] = $uploaddir;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;
				$config['overwrite'] = TRUE;
				
				$this->upload->initialize($config);
				
				$msg = $this->setmessage('Картинка не загружена!<br />Возможные ошибки:<p>- Вы не выбрали картинку</p><p>- Размер картинки более 5 Мб.</p><p>- Формат картинки не поддерживается.</p><p>- Отсутствуют права на загрузку файлов.</p><br /><b>Проверьте условия и повторите загрузку снова.</b>','','Ошибка загрузки!',1);
				
				if ($this->upload->do_upload()){    
					
					$upload_data = $this->upload->data();			
					
					$config['image_library'] = 'gd2';
					$config['source_image']	= $uploaddir.'/'.$upload_data['file_name']; 
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	 = 640;
					$config['height']	= 480;
					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
					
					$this->albummodel->increment_amt_to_album($alb_id);
					$_POST['file'] = $uploadpath.'/'.$upload_data['file_name'];
					
					if(!isset($_POST['imagetitle']) or empty($_POST['imagetitle']))
						$_POST['imagetitle'] = $alb_title;
						
					$this->imagesmodel->insert_record($_POST);
					
					$textmessage = 'Картинка '.$upload_data['file_name'].' загружена в каталог '.$uploadpath;
					$msg = $this->setmessage('',$textmessage,'Загрузка выполнилась успешно!',1);
				}
			}
			
			$this->load->view('photo_add',array('data1' => $data1,'data2'=>$data2,'msg'=>$msg));
			$this->load->view('footer');
		}
		
		function friendsview(){
		
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Страница друзей",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'basepath' => getcwd()
						);
			$data2 = $this->friendsmodel->get_friends_info_list();
			$data3 = $this->socialmodel->get_friend_social_info_list();
			
			$i = 0; $y = 0; $key = 0;
			$data4[$i][$y] = array(
								'id' => 0,
							  'name' => '',
						'profession' => '',
						    'social' => 0,
							  'note' => '',
							 'image' => ''
								);
			foreach ($data2 as $friends){
				
				$key += 1;				
				$data4[$i][$y]['id'] = $friends->fr_id;
				$data4[$i][$y]['name'] = $friends->fr_name;
				$data4[$i][$y]['profession'] = $friends->fr_profession;
				$data4[$i][$y]['social'] = $friends->fr_social;
				$data4[$i][$y]['note'] = $friends->fr_note;
				$data4[$i][$y]['image'] = $friends->fr_image;
				
				if ($key % 3 == 0){
					$i += 1;
					$y = 0;
				}else{
					$y += 1;	
				}
			}
			$this->load->view('friends_view',array(
												'data1' => $data1,
												'data2' => $data4,
												'data3' => $data3,
												'data4' => $key
												));
			$this->load->view('footer');
		} 
		
		function friendnew(){
			
			$pagevalue = array(
							'title' => "Samoilovi.ru | Администрирование | Добавление карточки друга",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			$this->load->view('friend_new',array('pagevalue'=>$pagevalue));
			$this->load->view('footer');
		}
		
		function friendinsert(){
			
			$this->form_validation->set_rules('name', '"Имя друга"', 'required');
			$this->form_validation->set_rules('profession', '"Профессия"', 'required');
			$this->form_validation->set_rules('note', '"Описание друга"', 'required');
			$this->form_validation->set_rules('hrefsocial1', '"Ссылка на страницу"', 'prep_url');
			$this->form_validation->set_rules('hrefsocial2', '"Ссылка на страницу"', 'prep_url');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			
			if ($this->form_validation->run() == FALSE){
				$this->friendnew();
				return FALSE;
			}
			
			$config['upload_path'] = getcwd().'/images';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = FALSE;			
							
			$this->upload->initialize($config);
							
			if ($this->upload->do_upload()){    
				
				$upload_data = $this->upload->data();			
				$_POST['userfile'] = 'images/'.$upload_data['file_name'];
				
				$config['image_library'] = 'gd2';
				$config['source_image']	= getcwd().'/'.$_POST['userfile']; 
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['height'] = 70;
				$config['width'] = 100;
								
				$this->image_lib->initialize($config);
				if (!$this->image_lib->resize()){
					// Обработка если рисунок не изменился.
					$_POST['userfile'] = 'images/friendempty.png';	
				}											
			}else{
				// обработка ошибки загрузки или не указан
				$_POST['userfile'] = 'images/friendempty.png';	
			}
			
			$social = 0;
			
			$data[0] = array('friend_id' => 0,'social' => '','href' => '','flag' => 0);
			$data[1] = array('friend_id' => 0,'social' => '','href' => '','flag' => 0);
						
			if(empty($_POST['note'])) $_POST['note'] = 'Описание не указано';
						
			if(!empty($_POST['social1']) and !empty($_POST['hrefsocial1'])){
			
				$social += 1;
				$cntsocial = 1;
				$data[0]['friend_id'] = 0;
				$data[0]['social'] = $_POST['social1'];
				$data[0]['href'] = $_POST['hrefsocial1'];
				$data[0]['flag'] = 1;
			}
				
			if(!empty($_POST['social2']) and !empty($_POST['hrefsocial2'])){
			
				$social += 1;
				$cntsocial = 2;
				$data[1]['friend_id'] = 0;
				$data[1]['social'] = $_POST['social2'];
				$data[1]['href'] = $_POST['hrefsocial2'];
				$data[1]['flag'] = 1;
			}
			
			$friend_id = $this->friendsmodel->insert_record_to_friends($_POST,$social);
			
			if (isset($cntsocial))
				for ($i = 0; $i < $cntsocial; $i++){
					if ($data[$i]['flag'] == 0) continue;
					$data[$i]['friend_id'] = $friend_id;
					$this->socialmodel->insert_record_to_social($data[$i]);				
				}
			redirect('admin/friendsview');
		}
		
		function frienddestroy(){
			
			$id = $this->uri->segment(3);			
			$data = $this->friendsmodel->get_friend_info($id);
					
			foreach ($data as $friend){
			
				if(($friend->fr_image!='images/friendempty.png')and(file_exists(getcwd().'/'.$friend->fr_image)))
					if(!unlink(getcwd().'/'.$friend->fr_image)){							
						//обработка ошибки удаления файла						
					}									
			}
			$this->socialmodel->delete_record_to_social($id);
			$this->friendsmodel->delete_record_to_friend($id);
			redirect('admin/friendsview');
		}
		
		function friendedit(){
			
			$id = $this->uri->segment(3);
			$pagevalue = array(
							'title' => "Samoilovi.ru | Администрирование | Редактирование карточки друга",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'basepath' => getcwd()
						);
			$friendinfo = $this->friendsmodel->get_friend_info($id);
			$socinfo = $this->socialmodel->get_friend_social_info($id);
			
			$sociallist[0] = array('id' => 0, 'social' => '', 'href' => '');
			$sociallist[1] = array('id' => 0, 'social' => '', 'href' => '');			
			$i = 0;			
			foreach ($socinfo as $social){
				
				$sociallist[$i]['id'] = $social->soc_id;
				$sociallist[$i]['social'] = $social->soc_name;
				$sociallist[$i]['href'] = $social->soc_href;
				$i +=1;
			}			
			$this->load->view('friend_edit',array('pagevalue'=>$pagevalue,'friendinfo'=>$friendinfo,'sociallist' => $sociallist));
			$this->load->view('footer');
		}
		
		function friendupdate(){
			
			if(empty($_POST['name'])){
				
				redirect('admin/friendedit/'.$_POST['fr_id']);
				return FALSE;
			}
			
			$config['upload_path'] = getcwd().'/images';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = FALSE;			
							
			$this->upload->initialize($config);
							
			if ($this->upload->do_upload()){    
				
				$upload_data = $this->upload->data();			
				$_POST['userfile'] = 'images/'.$upload_data['file_name'];
				
				if($_POST['oldphoto']!=$_POST['userfile'] and $_POST['oldphoto'] != 'images/friendempty.png'){
					
					$photopath = getcwd().'/'.$_POST['oldphoto'];
					if (file_exists($photopath))
						if(!unlink($photopath)){							
							//обработка события если не удалился файл						
						}					
				}
				$config['image_library'] = 'gd2';
				$config['source_image']	= getcwd().'/'.$_POST['userfile']; 
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['height'] = 70;
				$config['width'] = 100;
								
				$this->image_lib->initialize($config);
				if (!$this->image_lib->resize()){
					
					if($_POST['oldphoto'] != 'images/friendempty.png'){
					
						$photopath = getcwd().'/'.$_POST['oldphoto'];
						if (file_exists($photopath))
							if(!unlink($photopath)){							
							//обработка события если не удалился файл						
						}					
					}
					// Обработка если рисунок не изменился.
					$_POST['userfile'] = 'images/friendempty.png';	
				}											
			}else{
			
				// обработка ошибки загрузки или не указан
				$_POST['userfile'] = $_POST['oldphoto'];	
			}
			
			if(empty($_POST['note'])) $_POST['note'] = 'Описание не указано';
						
			$social = 0;			
			$data[0] = array('id' => 0, 'friend_id' => 0,'social' => '','href' => '','flag' => 0);
			$data[1] = array('id' => 0, 'friend_id' => 0,'social' => '','href' => '','flag' => 0);
									
			if(!empty($_POST['social1']) and !empty($_POST['hrefsocial1'])){
			
				$social += 1;
				$cntsocial = 1;
				$data[0]['id'] = $_POST['soc_id1'];
				$data[0]['friend_id'] = $_POST['fr_id'];
				$data[0]['social'] = $_POST['social1'];
				$data[0]['href'] = $_POST['hrefsocial1'];
				$data[0]['flag'] = 1;
			}
				
			if(!empty($_POST['social2']) and !empty($_POST['hrefsocial2'])){
			
				$social += 1;
				$cntsocial = 2;
				$data[1]['id'] = $_POST['soc_id2'];
				$data[1]['friend_id'] = $_POST['fr_id'];
				$data[1]['social'] = $_POST['social2'];
				$data[1]['href'] = $_POST['hrefsocial2'];
				$data[1]['flag'] = 1;
			}
			$this->friendsmodel->reset_social_count($_POST['fr_id']);
			$this->socialmodel->delete_record_to_social($_POST['fr_id']);
			
			$this->friendsmodel->update_record_to_friends($_POST,$social);			
			if (isset($cntsocial))
				for ($i = 0; $i < $cntsocial; $i++){
					if ($data[$i]['flag'] == 0)continue;
					$data[$i]['friend_id'] = $_POST['fr_id'];
				//	if($this->socialmodel->update_record_to_social($data[$i]));
					$this->socialmodel->insert_record_to_social($data[$i]);				
				}
			redirect('admin/friendsview');
		}
		
		function operation_date($field){
			
			$list = preg_split("/-/",$field);
			$nmonth = $this->months[$list[1]];
			$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
			$replacement = "\$5 $nmonth \$1 г."; 
			return preg_replace($pattern, $replacement,$field);
		}
		
		function operation_date_slash($field){
			
			$list = preg_split("/-/",$field);
			$nmonth = $this->months[$list[1]];
			$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
			$replacement = "\$5/\$3/\$1"; 
			return preg_replace($pattern, $replacement,$field);
		}
		
		function commentslist(){
			
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Список комментариев за 3 недели",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url()
						);
			
			$data3 = $this->unionmodel->get_count_cmnt_blog_record(21);
			
			$config['base_url'] = base_url().'/admin/commentslist';	
        	$config['total_rows'] = $data3;
			$config['per_page'] =  5;
        	$config['num_links'] = 2;
        	$config['uri_segment'] = 3;
			$config['first_link'] = 'В начало';
			$config['last_link'] = 'В конец';
			$config['next_link'] = 'Далее &raquo;';
			$config['prev_link'] = '&laquo; Назад';
			$config['cur_tag_open'] = '<b>';
			$config['cur_tag_close'] = '</b>';
		
			$from = intval($this->uri->segment(3));			
			$data2['query'] = $this->unionmodel->select_cmnt_blog_from_list(21,5,$from);
			
			
						
			foreach ($data2['query'] as $data){
				
				$data->blg_date = $this->operation_date($data->blg_date);
				$data->cmnt_usr_date = $this->operation_date_slash($data->cmnt_usr_date);
			}
			
			$this->pagination->initialize($config);
			$data2['pager'] = $this->pagination->create_links();
			
			$this->load->view('comments_list',array('data1'=>$data1, 'data2'=>$data2,'data3'=>$data3));
			$this->load->view('footer'); 
		}
		
		function setmessage($data1,$data2,$data3,$data4){
			
			$this->message['error'] = $data1;
			$this->message['saccessfull'] = $data2;
			$this->message['message'] = $data3;
			$this->message['status'] = $data4;
			
			return $this->message;
			
		}

		function profile(){
			
			$data1 = array(
							'title' => "Samoilovi.ru | Администрирование | Редактирование профиля",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
						);
			$login = $this->session->userdata('login');
			$data2 = $this->authentication->get_users_info($login);
			
        	$this->load->view('profile',array('data1'=>$data1, 'data2'=>$data2));
			$this->load->view('footer');
		}
		
		function profileupdate(){
			
			$this->form_validation->set_rules('oldpass','"Старый пароль"','required|callback_oldpass_check');
			$this->form_validation->set_rules('newpass','"Новый пароль"','required|min_length[6]|matches[confirmpass]');
			$this->form_validation->set_rules('confirmpass','"Подтверждение пароля"','required');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			$this->form_validation->set_message('min_length', 'Минимальная длина пароля — 6 символов.');
			$this->form_validation->set_message('matches', 'Поля "Новый пароль" и "Подтверждение пароля" должны совпадать');
			
			if ($this->form_validation->run() == FALSE){
				$this->profile();
				return FALSE;
			}else{
				$_POST['pass_crypt'] = $this->encrypt->encode($_POST['newpass']);
				$this->authentication->changepassword($_POST);
				redirect('admin');
			}
		}
		
		function oldpass_check($pass){
			
			$login = $this->session->userdata('login');
			$userinfo = $this->authentication->get_users_info($login);
			
			foreach ($userinfo as $user){
			
				$userpass = $user->usr_password;
			}
			if(md5($pass) == $userpass){
				return TRUE;
			}else{
				$this->form_validation->set_message('oldpass_check', 'Введен не верный пароль!');
				return FALSE;
			}
		}

		function imageslist(){
			
			$pagevalue = array(
							'title' => "Samoilovi.ru | Администрирование | Просмотр фотографий",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'basepath' => getcwd()
						);
			$alb_id = $this->uri->segment(3);
			
			$images = array();
			$images = $this->imagesmodel->get_data($alb_id);
			
			$this->load->view('photo_list',array('pagevalue'=>$pagevalue,'images'=>$images,'album'=>$alb_id));
			$this->load->view('footer');	
		}
		
		function deletephoto(){
			
			$alb_id = $this->uri->segment(3);
			$img_id = $this->uri->segment(5);
			$redirect = 'admin/album/'.$alb_id.'/images';
			
			$photoinfo = $this->imagesmodel->get_image($img_id);
			
			foreach($photoinfo as $photo){
				$photopath = getcwd().'/'.$photo->img_src;
			}
			
			$this->imagesmodel->image_delete($img_id);
			if (file_exists($photopath))
				if(!unlink($photopath)){
				//обработка события если не удалился файл						
			}
			$this->albummodel->decrement_amt_to_album($alb_id);
			
			redirect($redirect);
		}

		function uploadify(){
			
		}
	}
?>