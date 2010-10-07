<?php
	
class Admin extends Controller{
	
	var $months = array("01"=>"января", "02"=>"февраля",
					"03"=>"марта",	"04"=>"апреля",
					"05"=>"мая",	"06"=>"июня",
					"07"=>"июля",	"08"=>"августа",
					"09"=>"сентября", "10"=>"октября",
					"11"=>"ноября",	"12"=>"декабря"
				);		
		
	var $message = array(
						'error' 		=> '',
						'saccessfull' 	=> '',
						'message' 		=> '',
						'status'		=> 0
						);
		
	function Admin(){
		
		parent::Controller();
		$this->load->model('eventsmodel');
		$this->load->model('commentsmodel');
		$this->load->model('authentication');
		$this->load->model('albummodel');
		$this->load->model('friendsmodel');
		$this->load->model('socialmodel');
		$this->load->model('imagesmodel');
		$this->load->model('unionmodel');
		$this->load->library('upload');
		$this->load->library('image_lib');
		if ($this->session->userdata('logon') == '76f1847d0a99a57987156534634a1acf') return;
		if ($this->uri->segment(2)==='login') return;
		redirect('admin/login');
	}
		
	function index(){
	
		$pagevalue = array(
					'title' => "Samoilovi.ru | Администрирование",
					'desc' => "\"\"",
					'keyword' => "\"\"",
					'baseurl' => base_url()
				);
		$this->session->set_userdata('backpage','admin');
		$this->session->unset_userdata('commentlist');
		$msg = $this->setmessage('','','',0);
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/adminpanel',array('pagevalue'=>$pagevalue,'msg'=>$msg));
	}
	
	function login(){
		
		$backpath = $this->session->userdata('backpage');	
		$pagevalue = array(
					'title'		=> "Samoilovi.ru | Администрирование | Аутентификация пользователя",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'backpath' 	=> $backpath,
					'baseurl' 	=> base_url(),
					);
		$msg = $this->setmessage('','','',0);
		
		if($this->input->post('btsabmit')):
			if (empty($_POST['password']) or empty($_POST['password'])):
				$msg = $this->setmessage('Логин и пароль не могут быть пустымы!','','Ошибка авторизации!',1);
				$this->load->view('admin/login',array('pagevalue'=>$pagevalue,'msg'=>$msg));
				return FALSE;
			endif;
				
			$userinfo = $this->authentication->user_info($_POST['login']);
			if(empty($userinfo)):
				$text = 'Пользователь '.$_POST['login'].' не зарегистрирован!';
				$msg = $this->setmessage($text,'','Ошибка авторизации!',1);
				$this->load->view('admin/login',array('pagevalue'=>$pagevalue,'msg'=>$msg));
				return FALSE;
			else:
				if($userinfo['usr_password'] === md5($_POST['password'])):
					$session_data = array('logon'=>'76f1847d0a99a57987156534634a1acf');
                   	$this->session->set_userdata($session_data);
                   	redirect('admin');	
				else:
					$msg = $this->setmessage('Введен не верный пароль.','','Ошибка авторизации!',1);
					$this->load->view('admin/login',array('pagevalue'=>$pagevalue,'msg'=>$msg));
					return FALSE;
				endif;
			endif;
			$this->load->view('admin/login',array('pagevalue'=>$pagevalue,'msg'=>$msg));
			return FALSE;
		endif;
		$msg = $this->setmessage('','','Введите логин и пароль для авторизации',1);
		$this->load->view('admin/login',array('pagevalue'=>$pagevalue,'msg'=>$msg));
	}
		
	function logoff(){
		
       	$this->session->sess_destroy();
		redirect('');
	}

	function events(){
		
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Просмотр записей блога",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'baseurl' 	=> base_url()
					);
		$this->session->set_userdata('backpage','admin/events');
		$msg = $this->setmessage('','','',0);
		$this->session->unset_userdata('commentlist');
		$events = array();
		$count = $this->eventsmodel->count_records();
		
		$config['base_url'] 		= base_url().'/admin/events';	 		
       	$config['total_rows'] 		= $count;							 	
       	$config['per_page'] 		= 5;   								
       	$config['num_links'] 		= 2;   	 							
       	$config['uri_segment'] 		= 3;								
		$config['first_link'] 		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open'] 	= '<b>';
		$config['cur_tag_close'] 	= '</b>';
					
		$from = intval($this->uri->segment(3));
		if(isset($from) and !empty($from))
			$this->session->set_userdata('backpage','admin/events/'.$from);
		$events = $this->eventsmodel->events_limit(5,$from);
		
		if (!count($events)) redirect('admin/event-new');
		
		for($i = 0;$i < count($events);$i++)
			$events[$i]['evnt_date'] = $this->operation_date($events[$i]['evnt_date']);
		
		$this->pagination->initialize($config);
		$pages = $this->pagination->create_links();
		
		$this->pagination->initialize($config);
		$pages = $this->pagination->create_links();
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/admin-events',array('pagevalue'=>$pagevalue,'events'=>$events,'pages'=>$pages,'msg'=>$msg));		
	}
	
	function event($event_id = 0,$error = FALSE){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Коментарии к записи",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'backpath' 	=> $backpath,
					'valid'		=> $error,
					'formuri' 	=> $this->uri->uri_string(),
					'baseurl' 	=> base_url()
				);
		$userinfo = array(
					'firstname' 	=> '',
					'secondname' 	=> '',
					'email' 		=> '',
				);
		$userinfo = $this->authentication->user_info('admin');
		$msg = $this->setmessage('','','',0);
		if($event_id == 0 or empty($event_id))
			$event_id = $this->uri->segment(3);
		
		if($this->input->post('op')):
			$this->form_validation->set_rules('user_name','"Ваше имя"','required');
			$this->form_validation->set_rules('user_email','"E-mail"','required|valid_email');
			$this->form_validation->set_rules('cmnt_text','"Комментарий"','required');
			$this->form_validation->set_rules('homepage','"Веб-сайт"','');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if(!$this->form_validation->run()):
				$_POST['op'] = NULL;
				$this->event($_POST['event_id'],TRUE);
				return FALSE;
			else:
				if(isset($_POST['homepage']) and !empty($_POST['homepage']))
					if(strncmp(strtolower($_POST['homepage']),'http://',7) != 0)
						$_POST['homepage'] = 'http://'.$_POST['homepage'];
				$this->eventsmodel->insert_comments($_POST['event_id']);			
				$this->commentsmodel->insert_record($_POST);
				$_POST['op'] = NULL;
				$this->event($_POST['event_id'],FALSE);
				return TRUE;
			endif;
		endif;
		$event = array();
		$comments = array();
		$event = $this->eventsmodel->event_record($event_id);
		if(isset($event) and !empty($event))
			$event['evnt_date'] = $this->operation_date($event['evnt_date']);
		
		$comments = $this->commentsmodel->comments_records($event_id);
		for($i = 0;$i < count($comments);$i++)
			$comments[$i]['cmnt_usr_date'] = $this->operation_date_slash($comments[$i]['cmnt_usr_date']);
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/admin-event',array('pagevalue'=>$pagevalue,'event'=>$event,'comments'=>$comments,'user'=>$userinfo,'msg'=>$msg));
	}
			
	function eventnew(){
	
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
						'title' 	=> "Samoilovi.ru | Администрирование | Создание записи блога",
						'desc' 		=> "\"\"",
						'keyword' 	=> "\"\"",
						'backpath' 	=> $backpath,
						'baseurl'	=> base_url()
					);
		$this->session->unset_userdata('commentlist');
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('title','"Оглавление"','required');
			$this->form_validation->set_rules('text','"Содержимое"','required');
			$this->form_validation->set_rules('date','"Дата"','required');
			
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->eventnew();
				return FALSE;
			else:
				$pattern = "/(\d+)\/(\w+)\/(\d+)/i";
				$replacement = "\$3-\$2-\$1";
				$_POST['date'] = preg_replace($pattern, $replacement, $_POST['date']);
				$this->eventsmodel->insert_record($_POST);
				$this->session->set_flashdata('operation_error',' ');
				$this->session->set_flashdata('operation_message','Название новой записи - '.$_POST['title']);
				$this->session->set_flashdata('operation_saccessfull','Новая запись создана успешно');
				redirect($backpath);
			endif;
		endif;
		$this->load->view('admin/admin-event-new',array('pagevalue'=>$pagevalue));
	}
	
	function eventedit($event_id = 0,$error = FALSE){
	
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Редактирование записи блога",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'formuri' 	=> $this->uri->uri_string(),
					'backpath' 	=> $backpath,
					'valid'		=> $error,
					'baseurl' 	=> base_url()
				);
		if($event_id == 0 or empty($event_id))
			$event_id = $this->uri->segment(3);
		$this->session->unset_userdata('commentlist');
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('title','"Оглавление"','required');
			$this->form_validation->set_rules('text','"Содержимое"','required');
			$this->form_validation->set_rules('date','"Дата"','required');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->eventedit($event_id,TRUE);
				return FALSE;
			else:
				$pattern = "/(\d+)\/(\w+)\/(\d+)/i";
				$replacement = "\$3-\$2-\$1";
				$_POST['date'] = preg_replace($pattern,$replacement,$_POST['date']);
				$this->eventsmodel->update_record($_POST);
				redirect('admin/events');
			endif;
		endif;
			
		$event = array();
		$event = $this->eventsmodel->event_record($event_id);
		
		if(isset($event) and !empty($event))
			$event['evnt_date'] = $this->operation_date_slash($event['evnt_date']);
					
        $this->load->view('admin/admin-event-edit',array('pagevalue'=>$pagevalue,'event'=>$event));
	}				
	
	function eventdestroy(){
		
		$backpath = $this->session->userdata('backpage');
		$event_id = $this->uri->segment(3);
		$event = $this->eventsmodel->event_record($event_id);
		$this->eventsmodel->delete_record($event_id);
		$this->commentsmodel->delete_records($event_id);
		$this->session->set_flashdata('operation_error',' ');
		$this->session->set_flashdata('operation_message','Название удаленной записи - '.$event['evnt_title']);
		$this->session->set_flashdata('operation_saccessfull','Запись удалена успешно');
		redirect($backpath);
	}
	
	function commentedit($comment_id = 0,$event_id = 0,$error = FALSE){
		
		$commentlist = $this->session->userdata('commentlist');
		$pagevalue = array(
					'title' 		=> "Samoilovi.ru | Администрирование | Редактирование комментария",
					'desc' 			=> "\"\"",
					'keyword' 		=> "\"\"",
					'baseurl' 		=> base_url(),
					'backpath' 		=> '',
					'commentlist' 	=> $commentlist,
					'formuri' 		=> $this->uri->uri_string(),
					'valid'			=> $error,
				);
		$comment = array();
		if($comment_id == 0 or empty($comment_id)):
			$comment_id = $this->uri->segment(4);
			$event_id 	= $this->uri->segment(3);
		endif;
		$pagevalue['backpath'] = 'admin/event/'.$event_id.'#comment_'.$comment_id;
		if(isset($pagevalue['commentlist']) and !empty($pagevalue['commentlist'])) 
			$pagevalue['backpath'] = $pagevalue['commentlist'].'#comment_'.$comment_id;
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('user_name','"Имя"','required');
			$this->form_validation->set_rules('user_email','"E-mail"','required|valid_email');
			$this->form_validation->set_rules('cmnt_text','"Текст комментария"','required');
			$this->form_validation->set_rules('user_date','"Дата"','');
			$this->form_validation->set_rules('homepage','"Веб-сайт"','');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->commentedit($_POST['id'],$_POST['event_id'],TRUE);
				return FALSE;
			else:
				$pattern = "/(\d+)\/(\w+)\/(\d+)/i";
				$replacement = "\$3-\$2-\$1";
				$_POST['user_date'] = preg_replace($pattern, $replacement, $_POST['user_date']);
				$this->commentsmodel->update_record($_POST);
				redirect($pagevalue['backpath']);
			endif;
		endif;
		$comment = $this->commentsmodel->comment_record($comment_id);
		$comment['cmnt_usr_date'] = $this->operation_date_slash($comment['cmnt_usr_date']);				 
		$this->load->view('admin/admin-comment-edit',array('pagevalue'=>$pagevalue,'comment'=>$comment));
	}	
		
	function commentdestroy(){
		
		$event_id = $this->uri->segment(3);
		$comment_id = $this->uri->segment(4);
		$backpath = 'admin/event/'.$event_id;
		$commentlist = $this->session->userdata('commentlist');
		if(isset($commentlist) and !empty($commentlist)) 
			$backpath = $commentlist;
		$comment = $this->commentsmodel->comment_record($comment_id);
		$this->eventsmodel->delete_comments($event_id);
		$this->commentsmodel->delete_record($comment_id);
		$this->session->set_flashdata('operation_error',' ');
		$this->session->set_flashdata('operation_message','Комментарий от "'.$comment['cmnt_usr_name'].'"');
		$this->session->set_flashdata('operation_saccessfull','Комментарий удален успешно');
		redirect($backpath);
	}
		
	function comments(){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Список комментариев за 3 недели",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'backpath' 	=> $backpath,
					'baseurl' 	=> base_url()
			);
		$msg = $this->setmessage('','','',0);
		$this->session->set_userdata('commentlist','admin/comments');
		$count = $this->unionmodel->count_record(21);
		$config['base_url'] 		= base_url().'/admin/comments';	
       	$config['total_rows'] 		= $count;
		$config['per_page'] 		= 5;
       	$config['num_links'] 		= 2;
       	$config['uri_segment'] 		= 3;
		$config['first_link'] 		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open'] 	= '<b>';
		$config['cur_tag_close']	= '</b>';
	
		$from = intval($this->uri->segment(3));
		if(isset($from) and !empty($from))
			$this->session->set_userdata('commentlist','admin/comments/'.$from);
					
		$from = intval($this->uri->segment(3));			
		$comments = $this->unionmodel->select_comments(21,5,$from);			
		for($i = 0;$i < count($comments);$i++):
			$comments[$i]['evnt_date'] = $this->operation_date($comments[$i]['evnt_date']);
			$comments[$i]['cmnt_usr_date'] = $this->operation_date_slash($comments[$i]['cmnt_usr_date']);
		endfor;
		$this->pagination->initialize($config);
		$pages = $this->pagination->create_links();
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/comments',array('pagevalue'=>$pagevalue,'comments'=>$comments,'pages'=>$pages,'msg'=>$msg));
	}
			
	function setmessage($error,$saccessfull,$message,$status){
			
		$this->message['error'] = $error;
		$this->message['saccessfull'] = $saccessfull;
		$this->message['message'] = $message;
		$this->message['status'] = $status;
		
		return $this->message;
	}		//установка сообщения;
	
	function albums(){
	
		$pagevalue = array(
					'title' => "Samoilovi.ru | Администрирование | Фоторепортажи",
					'desc' => "\"\"",
					'keyword' => "\"\"",
					'baseurl' => base_url(),
					'basepath' => getcwd()
				);
		$this->session->set_userdata('backpage','admin/album-gallary');
		$msg = $this->setmessage('','','',0);
		$this->session->unset_userdata('commentlist');
		
		$albums = array();
		$albums = $this->albummodel->albums_records();
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/admin-albums',array('pagevalue'=>$pagevalue,'albums'=>$albums,'msg'=>$msg));
	}

	function albumnew(){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Создание нового фотоальбома",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'backpath' 	=> $backpath,
					'baseurl' 	=> base_url()
				);
		$msg = $this->setmessage('','','',0);
		$this->session->unset_userdata('commentlist');
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('title','"Название альбома"','required');
			$this->form_validation->set_rules('photo_title','"Подпись"','required');
			$this->form_validation->set_rules('userfile','"Фото"','callback_userfile_check');
			$this->form_validation->set_rules('annotation','"Описание альбома"','required|prep_for_form');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->albumnew();
				return FALSE;
			else:
				$img = $this->resize_img($_FILES,186,186,'admin/album-new');
				$_POST['image'] = $img['image'];
				$this->albummodel->insert_record($_POST);
				$this->session->set_flashdata('operation_error',' ');
				$this->session->set_flashdata('operation_message','Название альбома - '.$_POST['title']);
				$this->session->set_flashdata('operation_saccessfull','Альбом создан успешно');
				redirect($backpath);
			endif;
		endif;
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/admin-album-new',array('pagevalue'=>$pagevalue,'msg'=>$msg));
	}

	function albumedit($album_id = 0,$error = FALSE){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Редактирование альбома",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'formuri' 	=> $this->uri->uri_string(),
					'backpath' 	=> $backpath,
					'valid'		=> $error,
					'baseurl' 	=> base_url()
				);
				
		if($album_id == 0 or empty($album_id))
			$album_id = $this->uri->segment(3);
		$this->session->unset_userdata('commentlist');
		$album = $this->albummodel->album_record($album_id);
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('title','"Название альбома"','required');
			$this->form_validation->set_rules('photo_title','"Подпись"','required');
			if($_FILES['userfile']['error'] != 4)
				$this->form_validation->set_rules('userfile','"Фото"','callback_userfile_check');
			$this->form_validation->set_rules('annotation','"Описание альбома"','required|prep_for_form');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->albumedit($album_id,TRUE);
				return FALSE;
			else:
				if($_FILES['userfile']['error'] != 4):
					$img = $this->resize_img($_FILES,186,186,'admin/album-new');
					$_POST['image'] = $img['image'];
				else:
					$_POST['image'] = $album['alb_photo'];
				endif;
				$this->albummodel->update_record($_POST);
				$this->session->set_flashdata('operation_error',' ');
				$this->session->set_flashdata('operation_message','Название альбома - '.$_POST['title']);
				$this->session->set_flashdata('operation_saccessfull','Альбом изменен успешно');
				redirect($backpath);
			endif;
		endif;
		
        $this->load->view('admin/admin-album-edit',array('pagevalue'=>$pagevalue,'album'=>$album));
	}
		
	function albumdestroy(){
			
		$backpath = $this->session->userdata('backpage');
		$album_id = $this->uri->segment(3);
		$album = $this->albummodel->album_record($album_id);
		$this->albummodel->delete_record($album_id);
		$this->imagesmodel->images_delete($album_id);
		$this->session->set_flashdata('operation_error',' ');
		$this->session->set_flashdata('operation_message','Название удаленного альбома - '.$album['alb_title']);
		$this->session->set_flashdata('operation_saccessfull','Альбом удален успешно');
		redirect($backpath);	
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

	
	function userfile_check($file){
		
		$tmpName = $_FILES['userfile']['tmp_name'];
		
		if ($_FILES['userfile']['error'] == 4):
			$this->form_validation->set_message('userfile_check','Не указана фотография!');
			return FALSE;
		endif;
		if(!$this->case_image($tmpName)):
			$this->form_validation->set_message('userfile_check','Формат картинки не поддерживается!');
			return FALSE;
		endif;
		if($_FILES['userfile']['error'] == 1):
			$this->form_validation->set_message('userfile_check','Размер картинки более 10 Мб!');
			return FALSE;
		endif;
		return TRUE;
	}
	
	function resize_img($picture,$wgt,$hgt,$backfunc){
			
		$image['filename'] 	= $picture['userfile']['name'];
		$tmpName  			= $picture['userfile']['tmp_name'];
		$fileSize 			= $picture['userfile']['size'];
		
		chmod($tmpName, 0777);
		$img = getimagesize($tmpName);		
		$size_x = $img[0];
		$size_y = $img[1];
		
		$wight = $wgt;
		$height = $hgt; 
		
		if(($size_x < $wgt) or ($size_y < $hgt)):
			$this->resize_image($tmpName,$wgt,$hgt,FALSE);
			$file = fopen($tmpName,'rb');
			$image['image'] = fread($file,filesize($tmpName));
			fclose($file);
			return $image;
		endif;
		if($size_x > $size_y)
			$this->resize_image($tmpName,$size_x,$hgt,TRUE);
		else
			$this->resize_image($tmpName,$wgt,$size_y,TRUE);
		$img = getimagesize($tmpName);		
		$size_x = $img[0];
		$size_y = $img[1];
		switch ($img[2]){
			case 1: $image_src = imagecreatefromgif($tmpName); break;
			case 2: $image_src = imagecreatefromjpeg($tmpName); break;
			case 3:	$image_src = imagecreatefrompng($tmpName); break;
			default: return FALSE;	
		}
		$x = round(($size_x/2)-($wgt/2));
		$y = round(($size_y/2)-($hgt/2));
		if($x < 0):
			$x = 0;	$wight = $size_x;
		endif;
		if($y < 0):
			$y = 0; $height = $size_y;
		endif;
		
		$image_dst = ImageCreateTrueColor($wight,$height);
		imageCopy($image_dst,$image_src,0,0,$x,$y,$wgt,$hgt);
		imagePNG($image_dst,$tmpName);
		imagedestroy($image_dst);
		imagedestroy($image_src);
		
		$file = fopen($tmpName,'rb');
		$image['image'] = fread($file,filesize($tmpName));
		fclose($file);
		/*header('Content-Type: image/jpeg' );
		echo $image['image'];
		exit();*/
		return $image;
	}	//функция меняет размер фотографии;
	
	function case_image($file){
			
		$info = getimagesize($file);
		switch ($info[2]):
			case 1	: return TRUE;
			case 2	: return TRUE;
			case 3	: return TRUE;
			default	: return FALSE;	
		endswitch;
	}			//функция проверяет, является файл - картинкой;
											 
	function resize_image($image,$wgt,$hgt,$ratio){
			
		$this->image_lib->clear();
		$config['image_library'] 	= 'gd2';
		$config['source_image']		= $image; 
		$config['create_thumb'] 	= FALSE;
		$config['maintain_ratio'] 	= $ratio;
		$config['width'] 			= $wgt;
		$config['height'] 			= $hgt;
				
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}
	
	}
?>