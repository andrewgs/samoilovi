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
				redirect($pagevalue['formuri']);
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
					'baseurl' => base_url()
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
				$_POST['image'] = $this->resize_img($_FILES,186,186);
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
				if($_FILES['userfile']['error'] != 4)
					$_POST['image'] = $this->resize_img($_FILES,186,186);
				else
					$_POST['image'] = $album['alb_photo'];
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
	
	function photos($album_id = 0,$error = FALSE){
		
		$backpath = $this->session->userdata('backpage');
		if($album_id == 0 or empty($album_id))
			$album_id = $this->uri->segment(3);
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Галлерея",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'backpath' 	=> $backpath,
					'valid'		=> $error,
					'album'		=> $album_id,
					'baseurl' 	=> base_url(),
					'basepath' 	=> getcwd()
			);
		$images = array();
		$msg = $this->setmessage('','','',0);
		$this->session->unset_userdata('commentlist');
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('imagetitle','"Описание"','required');
			$this->form_validation->set_rules('userfile','"Фото"','callback_userfile_check');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->photos($album_id,TRUE);
				return FALSE;
			else:
				$_POST['big_image'] = $this->resize_img($_FILES,640,480);
				$_POST['image'] = $this->resize_img($_FILES,186,186);
				$this->imagesmodel->insert_record($_POST);
				$this->albummodel->insert_photo($_POST['album']);
				$this->session->set_flashdata('operation_error',' ');
				$this->session->set_flashdata('operation_message','Название фотографии - '.$_FILES['userfile']['name']);
				$this->session->set_flashdata('operation_saccessfull','Фотография загружена успешно');
				redirect('admin/photo-gallary/'.$_POST['album']);
			endif;
		endif;
		$images = $this->imagesmodel->get_data($album_id);
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);

		$this->load->view('admin/admin-photo-gallery',array('pagevalue'=>$pagevalue,'images'=>$images,'msg'=>$msg));
	}
	
	function multiupload(){
		
		if (!empty($_FILES)){
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'].$_REQUEST['folder'] . '/';
			$targetFile = str_replace('//','/',$targetPath).$_FILES['Filedata']['name'];
			copy($tempFile,$targetFile);
			if(file_exists($_FILES['Filedata']['tmp_name']) and is_uploaded_file($_FILES['Filedata']['tmp_name'])):
				unlink(file_exists($_FILES['Filedata']['tmp_name']));
			endif;
			return TRUE;
		}else{
			redirect('page404');
		}
		
		/*if (!empty($_FILES)):		
			print_r($_FILES); exit();
			$image['album']			= $this->uri->segment(3);
			$album 					= $this->albummodel->album_record($image['album']);
			$image['imagetitle'] 	= $album['alb_photo_title'];
			$image['big_image'] 	= $this->resize_img($_FILES,640,480);
			$image['image'] 		= $this->resize_img($_FILES,186,186);
			
			$this->imagesmodel->insert_record($image);
			$this->albummodel->insert_photo($image['album']);
		else:
			$this->session->set_flashdata('operation_error','Отсутствуют данные для загрузки!');
			$this->session->set_flashdata('operation_message',' ');
			$this->session->set_flashdata('operation_saccessfull','При загрузке фотографий произошла ошибка!');
			redirect('admin/photo-gallary/'.$_POST['album']);
			return FALSE;
		endif;*/
	}
	
	function photodestroy(){
			
		$image_id = $this->uri->segment(3);
		$image = $this->imagesmodel->get_image($image_id);
		$backpath = 'admin/photo-gallary/'.$image['img_album'];
		$this->imagesmodel->image_delete($image_id);
		$this->albummodel->delete_photo($image['img_album']);
		$this->session->set_flashdata('operation_error',' ');
		$this->session->set_flashdata('operation_message','Фотография - "'.$image['img_title'].'"');
		$this->session->set_flashdata('operation_saccessfull','Фотография удалена успешно');
		redirect($backpath);	
	}
	
	function friends(){
		
		$pagevalue = array(
					'title' => "Samoilovi.ru | Администрирование | Страница друзей",
					'desc' => "\"\"",
					'keyword' => "\"\"",
					'baseurl' => base_url(),
					'basepath' => getcwd()
			);
		$this->session->set_userdata('backpage','admin/friends');
		$msg = $this->setmessage('','','',0);
		$this->session->unset_userdata('commentlist');
		
		$friends = array();
		$socials = array();
		$friends = $this->friendsmodel->friends_records();
		$social = $this->socialmodel->social_records();
		
		$i = 0; $y = 0; $key = 0;
		$friendcard[$i][$y] = array('id'=>0,'name'=>'','profession'=>'','social'=>0,'note'=>'','image'=>'');
		for($fr = 0;$fr < count($friends);$fr++):
			$key++;				
			$friendcard[$i][$y]['id'] 			= $friends[$fr]['fr_id'];
			$friendcard[$i][$y]['name'] 		= $friends[$fr]['fr_name'];
			$friendcard[$i][$y]['profession'] 	= $friends[$fr]['fr_profession'];
			$friendcard[$i][$y]['social'] 		= $friends[$fr]['fr_social'];
			$friendcard[$i][$y]['note'] 		= $friends[$fr]['fr_note'];
			$friendcard[$i][$y]['image'] 		= $friends[$fr]['fr_image'];
			if($key % 3 == 0):
				$i++; $y = 0;
			else:
				$y++;	
			endif;
		endfor;
		
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
		
		$this->load->view('admin/admin-friends',array('pagevalue'=>$pagevalue,'friendcard'=>$friendcard,'social'=>$social,'key'=>$key,'msg'=>$msg));
	} 
		
	function friendnew(){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Создание карточки друга",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'backpath' 	=> $backpath,
					'baseurl' 	=> base_url()
				);
		
		$msg = $this->setmessage('','','',0);
		$this->session->unset_userdata('commentlist');
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('name','"Имя друга"','required');
			$this->form_validation->set_rules('profession','"Профессия"','required');
			$this->form_validation->set_rules('userfile','"Фото"','callback_userfile_check');
			$this->form_validation->set_rules('note','"Описание друга"','required');
			$this->form_validation->set_rules('social1','"Соц.сеть"','');
			$this->form_validation->set_rules('social2','"Соц.сеть"','');
			$this->form_validation->set_rules('hrefsocial1','"Ссылка"','prep_url');
			$this->form_validation->set_rules('hrefsocial2','"Ссылка"','prep_url');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->friendnew();
				return FALSE;
			else:
				$tmpfile = $_FILES['userfile']['tmp_name'];
				$this->resize_image($tmpfile,100,70,TRUE);
				$file = fopen($tmpfile,'rb');
				$_POST['image'] = fread($file,filesize($tmpfile));
				fclose($file);
				$_POST['social'] = 0;
				$social[0] = array('friend_id'=>0,'social'=>'','href'=>'','flag'=>0);
				$social[1] = array('friend_id'=>0,'social'=>'','href'=>'','flag'=>0);							
				if(!empty($_POST['social1']) and !empty($_POST['hrefsocial1'])):
					$_POST['social'] += 1;
					$socstatus = 1;
					$social[0]['friend_id'] = 0;
					$social[0]['social'] 	= $_POST['social1'];
					$social[0]['href'] 		= $_POST['hrefsocial1'];
					$social[0]['flag'] 		= TRUE;
				endif;
				if(!empty($_POST['social2']) and !empty($_POST['hrefsocial2'])):
					$_POST['social'] += 1;
					$socstatus = 2;
					$social[1]['friend_id'] = 0;
					$social[1]['social'] 	= $_POST['social2'];
					$social[1]['href'] 		= $_POST['hrefsocial2'];
					$social[1]['flag']		= TRUE;
				endif;
				$friend_id = $this->friendsmodel->insert_record($_POST);				
				if(isset($socstatus)):
					for ($i = 0; $i < $socstatus; $i++):
						if (!$social[$i]['flag']) continue;
						$social[$i]['friend_id'] = $friend_id;
						$this->socialmodel->insert_record($social[$i]);				
					endfor;
				endif;	
				$this->session->set_flashdata('operation_error',' ');
				$this->session->set_flashdata('operation_message','Имя друга - '.$_POST['name']);
				$this->session->set_flashdata('operation_saccessfull','Карточка создана успешно');
				redirect($backpath);
			endif;
		endif;
		$flasherr = $this->session->flashdata('operation_error');
		$flashmsg = $this->session->flashdata('operation_message');
		$flashsaf = $this->session->flashdata('operation_saccessfull');
		if($flasherr && $flashmsg && $flashsaf)
			$msg = $this->setmessage($flasherr,$flashsaf,$flashmsg,1);
				
		$this->load->view('admin/admin-friend-new',array('pagevalue'=>$pagevalue,'msg'=>$msg));
	}
		
	function friendedit($friend_id = 0,$error = FALSE){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Редактирование карточки друга",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'formuri' 	=> $this->uri->uri_string(),
					'backpath' 	=> $backpath,
					'valid'		=> $error,
					'baseurl' 	=> base_url()
				);
		if($friend_id == 0 or empty($friend_id))
			$friend_id = $this->uri->segment(3);
		$sociallist[0]  = array('id'=>'','social'=>'','href'=>'');
		$sociallist[1]  = array('id'=>'','social'=>'','href'=>'');
		$friend  		= array();
		$socials  		= array();
		$friend 		= $this->friendsmodel->friend_record($friend_id);
		$socials 		= $this->socialmodel->friend_social($friend_id);
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('name','"Имя друга"','required');
			$this->form_validation->set_rules('profession','"Профессия"','required');
			if($_FILES['userfile']['error'] != 4)
				$this->form_validation->set_rules('userfile','"Фото"','callback_userfile_check');
			$this->form_validation->set_rules('note','"Описание друга"','required');
			$this->form_validation->set_rules('social1','"Соц.сеть"','');
			$this->form_validation->set_rules('social2','"Соц.сеть"','');
			$this->form_validation->set_rules('hrefsocial1','"Ссылка"','prep_url');
			$this->form_validation->set_rules('hrefsocial2','"Ссылка"','prep_url');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			if (!$this->form_validation->run()):
				$_POST['btnsubmit'] = NULL;
				$this->friendedit($friend_id,TRUE);
				return FALSE;
			else:
				if($_FILES['userfile']['error'] != 4):
					$tmpfile = $_FILES['userfile']['tmp_name'];
					$this->resize_image($tmpfile,100,70,TRUE);
					$file = fopen($tmpfile,'rb');
					$_POST['image'] = fread($file,filesize($tmpfile));
					fclose($file);
				else:
					$_POST['image'] = $friend['fr_image'];
				endif;
				$_POST['social'] = 0;			
				$social[0] = array('id'=>0,'friend_id'=>0,'social'=>'','href'=>'','flag'=>0);
				$social[1] = array('id'=>0,'friend_id'=>0,'social'=>'','href'=>'','flag'=>0);
				if(!empty($_POST['social1']) and !empty($_POST['hrefsocial1'])):
					$_POST['social'] 		+= 1;
					$socstatus 				= 1;
					$social[0]['friend_id'] = $_POST['id'];
					$social[0]['social'] 	= $_POST['social1'];
					$social[0]['href'] 		= $_POST['hrefsocial1'];
					$social[0]['flag'] 		= 1;
				endif;
				if(!empty($_POST['social2']) and !empty($_POST['hrefsocial2'])):
					$_POST['social'] 		+= 1;
					$socstatus 				= 2;
					$social[1]['friend_id'] = $_POST['id'];
					$social[1]['social'] 	= $_POST['social2'];
					$social[1]['href'] 		= $_POST['hrefsocial2'];
					$social[1]['flag'] 		= 1;
				endif;
				$this->friendsmodel->reset_social($_POST['id']);
				$this->socialmodel->delete_records($_POST['id']);
				$this->friendsmodel->update_record($_POST);			
				if(isset($socstatus)):
					for ($i = 0; $i < $socstatus; $i++):
						if ($social[$i]['flag'] == 0)continue;
						$this->socialmodel->insert_record($social[$i]);				
					endfor;
				endif;
				
				$this->session->set_flashdata('operation_error',' ');
				$this->session->set_flashdata('operation_message','Имя друга - '.$_POST['name']);
				$this->session->set_flashdata('operation_saccessfull','Карточка изменена успешно');
				redirect($backpath);
			endif;
		endif;
		for($i = 0; $i < count($socials); $i++):
			$sociallist[$i]['id'] 		= $socials[$i]['soc_id'];
			$sociallist[$i]['social'] 	= $socials[$i]['soc_name'];
			$sociallist[$i]['href'] 	= $socials[$i]['soc_href'];
		endfor;
		$this->load->view('admin/admin-friend-edit',array('pagevalue'=>$pagevalue,'friend'=>$friend,'socials'=>$sociallist));
	}
		
	function frienddestroy(){
		
		$backpath = $this->session->userdata('backpage');
		$friend_id = $this->uri->segment(3);
		$friend = $this->friendsmodel->friend_record($friend_id);
		$this->friendsmodel->delete_record($friend_id);
		$this->socialmodel->delete_records($friend_id);
		$this->session->set_flashdata('operation_error',' ');
		$this->session->set_flashdata('operation_message','Удаленна карточка - '.$friend['fr_name']);
		$this->session->set_flashdata('operation_saccessfull','Карточка удалена успешно');
		redirect($backpath);
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
	
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Смена пароля администраторва",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'formuri' 	=> $this->uri->uri_string(),
					'backpath' 	=> $backpath,
					'baseurl' 	=> base_url()
				);
		$msg = $this->setmessage('','','',0);
		if($this->input->post('btnsubmit')):
			$this->form_validation->set_rules('oldpass','"Старый пароль"','required|callback_oldpass_check');
			$this->form_validation->set_rules('newpass','"Новый пароль"','required|min_length[6]|matches[confirmpass]');
			$this->form_validation->set_rules('confirmpass','"Подтверждение пароля"','required');
			$this->form_validation->set_error_delimiters('<div class="message">','</div>');
			$this->form_validation->set_message('min_length','Минимальная длина пароля — 6 символов.');
			$this->form_validation->set_message('matches','Поля "Новый пароль" и "Подтверждение пароля" должны совпадать');
			if ($this->form_validation->run() == FALSE):
				$msg = $this->setmessage('Не выполнены условия.','','Ошибка при изменении профиля.',1);
				$login = $this->session->userdata('login');
				$userinfo = $this->authentication->user_info($login);
        		$this->load->view('admin/profile',array('pagevalue'=>$pagevalue,'userinfo'=>$userinfo,'msg'=>$msg));
				return FALSE;
			else:
				$_POST['pass_crypt'] = $this->encrypt->encode($_POST['newpass']);
				$this->authentication->changepassword($_POST);
				$this->session->set_flashdata('operation_saccessfull','Пароль администратора изменен.');
				redirect('admin/profile');
				return TRUE;
			endif;
		endif;
		$login = $this->session->userdata('login');
		$userinfo = $this->authentication->user_info($login);
		$flashmsg = $this->session->flashdata('operation_saccessfull');
		if(isset($flashmsg) and !empty($flashmsg))
			$msg = $this->setmessage('','',$flashmsg,1);
        $this->load->view('admin/profile',array('pagevalue'=>$pagevalue,'userinfo'=>$userinfo,'msg'=>$msg));
	}				//функция производит смену пароля администратора;

	function oldpass_check($pass){
			
		$login = $this->session->userdata('login');
		$userinfo = $this->authentication->user_info($login);
			
		if(md5($pass) == $userinfo['usr_password']):
			return TRUE;
		else:
			$this->form_validation->set_message('oldpass_check','Введен не верный пароль!');
			return FALSE;
		endif;
	}	//функция проверяет старый пароль перед изменением;

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
	
	function resize_img($picture,$wgt,$hgt){
			
		$image	 	= $picture['userfile']['name'];
		$tmpName	= $picture['userfile']['tmp_name'];
		$fileSize	= $picture['userfile']['size'];
		
		chmod($tmpName, 0777);
		$img = getimagesize($tmpName);		
		$size_x = $img[0];
		$size_y = $img[1];
		
		$wight = $wgt;
		$height = $hgt; 
		
		if(($size_x < $wgt) or ($size_y < $hgt)):
			$this->resize_image($tmpName,$wgt,$hgt,FALSE);
			$file = fopen($tmpName,'rb');
			$image = fread($file,filesize($tmpName));
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
		$image = fread($file,filesize($tmpName));
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
	
	function show($album_id = 0,$error = FALSE){
		
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Администрирование | Галлерея",
					'desc' 		=> "\"\"",
					'keyword' 	=> "\"\"",
					'valid'		=> $error,
					'album'		=> $album_id,
					'baseurl' 	=> base_url(),
					'basepath' 	=> getcwd()
			);
		$this->load->view('view',array('pagevalue'=>$pagevalue));
	}
}
?>