<?php

class Home extends Controller{
	
	var $usrinfo = array(
					'firstname' 	=> '',
					'secondname' 	=> '',
					'email' 		=> '',
					'status' 		=> FALSE
					);
						
	var $months = array("01"=>"января", "02"=>"февраля",
						"03"=>"марта",	"04"=>"апреля",
						"05"=>"мая",	"06"=>"июня",
						"07"=>"июля",	"08"=>"августа",
						"09"=>"сентября", "10"=>"октября",
						"11"=>"ноября",	"12"=>"декабря"
					);
					
	function Home(){
	
		parent::Controller();
		
		$this->load->model('eventsmodel');
		$this->load->model('commentsmodel');
		$this->load->model('authentication');
		$this->load->model('albummodel');
		$this->load->model('friendsmodel');
		$this->load->model('socialmodel');
		$this->load->model('imagesmodel');
		if($this->session->userdata('logon') == '76f1847d0a99a57987156534634a1acf'):
			$userinfo = $this->authentication->user_info('admin');
			$this->usrinfo['firstname']		= $userinfo['usr_first_name'];
			$this->usrinfo['secondname'] 	= $userinfo['usr_second_name'];
			$this->usrinfo['email'] 		= $userinfo['usr_email'];
			$this->usrinfo['status'] 		= TRUE;
		else:
			$this->usrinfo['status'] = FALSE;
		endif;
	}
	
	function index(){
	
		$pagevalue = array(
						'title' 	=> "Samoilovi.ru | Интернет посольство семьи Самойловых",
						'desc' 		=> "\"веб-сайт семьи Самойловых\"",
						'keyword' 	=> "\"семейный сайт, любовь, семейные новости, отношения\"",
						'baseurl' 	=> base_url(),
						'admin' 	=> $this->usrinfo['status']
					);
		$this->session->set_userdata('backpage','');
		
		$events = $this->eventsmodel->new_events(3);
		for($i = 0;$i < count($events); $i++):
			$events[$i]['evnt_date'] = $this->operation_date($events[$i]['evnt_date']);				
			$events[$i]['evnt_text'] = strip_tags($events[$i]['evnt_text']);				
			if(mb_strlen($events[$i]['evnt_text'],'UTF-8') > 325):									
				$events[$i]['evnt_text'] = mb_substr($events[$i]['evnt_text'],0,325,'UTF-8');	
				$pos = mb_strrpos($events[$i]['evnt_text'],' ',0,'UTF-8');
				$events[$i]['evnt_text'] = mb_substr($events[$i]['evnt_text'],0,$pos,'UTF-8');
				$events[$i]['evnt_text'] .= ' ...';
			endif;
		endfor;
		
		$this->load->view('index',array('pagevalue'=>$pagevalue,'events'=>$events));
	}
	
	function albums(){
		$pagevalue = array(
					'title' => "Samoilovi.ru | Фоторепортажи",
					'desc' => "\"веб-сайт семьи Самойловых\"",
					'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
					'baseurl' => base_url(),
					'basepath' => getcwd(),
					'admin' => $this->usrinfo['status']
				);
		$this->session->set_userdata('backpage','photo-albums');
		$albums = array();
		$albums = $this->albummodel->albums_list();	 
		$this->load->view('albums',array('pagevalue'=>$pagevalue,'albums'=>$albums));
	}
			
	function events(){
	
		$pagevalue = array(
						'title' => "Samoilovi.ru | События в нашей жизни",
						'desc' => "\"веб-сайт семьи Самойловых\"",
						'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
						'baseurl' => base_url(),
						'admin' => $this->usrinfo['status']
					);
		$this->session->set_userdata('backpage','events');
		$events = array();
		
		$count = $this->eventsmodel->count_records();			
		$config['base_url'] 		= base_url().'/events';
        $config['total_rows'] 		= $count; 
        $config['per_page'] 		= 5;
        $config['num_links'] 		= 2;
        $config['uri_segment'] 		= 2;
		$config['first_link']		= 'В начало';
		$config['last_link'] 		= 'В конец';
		$config['next_link'] 		= 'Далее &raquo;';
		$config['prev_link'] 		= '&laquo; Назад';
		$config['cur_tag_open']		= '<b>';
		$config['cur_tag_close'] 	= '</b>';
					
		$from = intval($this->uri->segment(2));
		if(isset($from) and !empty($from))
			$this->session->set_userdata('backpage','events/'.$from);
			
		$events = $this->eventsmodel->events_limit(5,$from);
		
		for($i = 0;$i < count($events);$i++)
			$events[$i]['evnt_date'] = $this->operation_date($events[$i]['evnt_date']);

		$this->pagination->initialize($config);
		$pages = $this->pagination->create_links();
			
		$this->load->view('events',array('pagevalue'=>$pagevalue,'events'=>$events,'pages'=>$pages,'count'=>$count));
	}
	
	function event($event_id = 0){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Просмотр коментариев записи блога",
					'desc' 		=> "\"веб-сайт семьи Самойловых\"",
					'keyword' 	=> "\"семейный сайт, любовь, семейные новости, отношения\"",
					'baseurl' 	=> base_url(),
					'basepath' 	=> getcwd(),
					'backpath' 	=> $backpath,
					'admin' 	=> $this->usrinfo['status']
				);
		if($event_id == 0 or empty($event_id))
			$event_id = $this->uri->segment(2);
		$event = array();
		$comments = array();
		$event = $this->eventsmodel->event_record($event_id);
		if(isset($event) and !empty($event))
			$event['evnt_date'] = $this->operation_date($event['evnt_date']);
		
		$comments = $this->commentsmodel->comments_records($event_id);
		for($i = 0;$i < count($comments);$i++)
			$comments[$i]['cmnt_usr_date'] = $this->operation_date_slash($comments[$i]['cmnt_usr_date']);

		$this->load->view('event',array('pagevalue'=>$pagevalue,'event'=>$event,'comments'=>$comments,'user'=>$this->usrinfo));	
	}
			
	function friends(){
	
		$pagevalue = array(
						'title' => "Samoilovi.ru | Страница друзей",
						'desc' 		=> "\"веб-сайт семьи Самойловых\"",
						'keyword' 	=> "\"семейный сайт, любовь, семейные новости, отношения\"",
						'baseurl' 	=> base_url(),
						'basepath' 	=> getcwd(),
						'admin' 	=> $this->usrinfo['status']
					);
		$this->session->set_userdata('backpage','friends');
		$friends = array();
		$social = array();
		
		$friends = $this->friendsmodel->get_friends();
		$social = $this->socialmodel->get_social();
		
		$i = 0; $y = 0; $key = 0;
		$card[$i][$y] = array(	'id' => 0, 'name' => '',
						'profession' => '','social' => 0,
						  	'note' => '','image' => '');
							
		for($i = 0;$i < count($friends);$i++):			
			$key += 1;				
			$card[$i][$y]['id'] 		= $friends[$i]['fr_id'];
			$card[$i][$y]['name'] 		= $friends[$i]['fr_name'];
			$card[$i][$y]['profession'] = $friends[$i]['fr_profession'];
			$card[$i][$y]['social']	 	= $friends[$i]['fr_social'];
			$card[$i][$y]['note'] 		= $friends[$i]['fr_note'];
			$card[$i][$y]['image'] 		= $friends[$i]['fr_image'];			
			if ($key % 3 == 0):
				$i += 1; $y = 0;
			else:
				$y += 1;	
			endif;
		endfor;
		$this->load->view('friends',array('pagevalue'=>$pagevalue,'card'=>$card,'social'=>$social,'key'=>$key));
	}
			
	function about(){
	
		$pagevalue = array(
					'title' => "Samoilovi.ru | О нас",
					'desc' 		=> "\"веб-сайт семьи Самойловых\"",
					'keyword' 	=> "\"семейный сайт, любовь, семейные новости, отношения\"",
					'baseurl' 	=> base_url(),
					'basepath' 	=> getcwd(),
					'admin' 	=> $this->usrinfo['status']
				);
		$this->session->set_userdata('backpage','about');	
		$this->load->view('about',array('pagevalue'=>$pagevalue));
	}
		
	function comments_new(){
			
		$this->form_validation->set_rules('user_name','"Ваше имя"','required');
		$this->form_validation->set_rules('user_email','"E-Mail"','required|valid_email');
		$this->form_validation->set_rules('cmnt_text','"Комментарий"','required');
		$this->form_validation->set_rules('homepage','"Веб-сайт"','prep_url');
		
		$this->form_validation->set_error_delimiters('<div class="message">','</div>');
		if($this->form_validation->run() == FALSE):
			$this->event($_POST['evnt_id']);
			return FALSE;
		endif;
		$this->eventsmodel->insert_comments($_POST['evnt_id']);			
		$this->commentsmodel->insert_record($_POST);
		redirect('event/'.$_POST['evnt_id']);
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
	
	function photo(){
		
		$backpath = $this->session->userdata('backpage');
		$pagevalue = array(
					'title' 	=> "Samoilovi.ru | Фоторепортажи | Фотографии",
					'desc' 		=> "\"веб-сайт семьи Самойловых\"",
					'keyword' 	=> "\"семейный сайт, любовь, семейные новости, отношения\"",
					'baseurl' 	=> base_url(),
					'basepath' 	=> getcwd(),
					'backpath' 	=> $backpath,
					'admin' 	=> $this->usrinfo['status']
					);
		$album_id = $this->uri->segment(2);
		$this->session->set_userdata('backpage','photo-albums/gallery/'.$album_id);
		
		$images = array();
		$images = $this->imagesmodel->get_images($alb_id);
		
		$this->load->view('images',array('data1'=>$data1,'data2'=>$data2,'data3'=>$alb_id));
	}
	
	function page404(){
		
		$pagevalue = array(
					'title' => "Samoilovi.ru | Фоторепортажи",
					'desc' => "\"веб-сайт семьи Самойловых\"",
					'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
					'baseurl' => base_url(),
					'basepath' => getcwd(),
					'admin' => $this->usrinfo['status']
				);
		$this->load->view('page404',array('pagevalue'=>$pagevalue));
	}			//функция выводит 404-ю ошибку;
									 
	function viewimage(){
		
		$section = $this->uri->segment(1);
		$id = $this->uri->segment(3);
		
		switch ($section){
			
			case 'album' :	$image = $this->albummodel->get_image($id);
							break;
			case 'small' :	$image = $this->imagesmodel->small_image($id);
							break;
			case 'big'	 : 	$image = $this->imagesmodel->big_image($id);
							break;
			case 'friend': 	$image = $this->friendsmodel->get_image($id);
							break;
		}
		header('Content-type: image/gif');
		echo $image;
	}		//функция выводит рисунок на страницу;
		
}	
?>