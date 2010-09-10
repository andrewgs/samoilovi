<?php

	class Home extends Controller{
	
		var $usrinfo = array(
						'firstname' => '',
						'secondname' => '',
						'email' => '',
						'adminlogin' => 0
						);
						
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
						
		function Home(){
			parent::Controller();
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->model('blogmodel');
			$this->load->model('cmntmodel');
			$this->load->model('authentication');
			$this->load->model('albummodel');
			$this->load->model('friendsmodel');
			$this->load->model('socialmodel');
			$this->load->model('imagesmodel');
			$this->load->library('session');
			$this->load->library('pagination');
			if ($this->session->userdata('logon') == '76f1847d0a99a57987156534634a1acf'){
				$this->usrinfo['adminlogin'] = 1;
				
				$userinfo = $this->authentication->get_users_info('admin');
				foreach($userinfo as $value){
					$this->usrinfo['firstname'] = $value->usr_first_name;
					$this->usrinfo['secondname'] = $value->usr_second_name;
					$this->usrinfo['email'] = $value->usr_email;
				}
			}else{
				$this->usrinfo['adminlogin'] = 0;
			}
				
				
		}
		
		function index(){
			$data1 = array(
							'title' => "Samoilovi.ru | Интернет посольство семьи Самойловых",
							'desc' => "\"веб-сайт семьи Самойловых\"",
							'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
							'baseurl' => base_url(),
							'islogin' => $this->usrinfo['adminlogin']							
						);
			$data2 = $this->blogmodel->get_blog_records_to_news();
			foreach ($data2 as $blog){
				
				$blog->blg_date = $this->operation_date($blog->blg_date);
				
				$blog->blg_text = strip_tags($blog->blg_text);
				if (strlen($blog->blg_text) > 450){									
					$blog->blg_text = substr($blog->blg_text,0,450);				
					$pos = strrpos($blog->blg_text,' ');
					$blog->blg_text = substr($blog->blg_text,0,$pos);
				}
			}
			$this->load->view('index',array('data1' => $data1,'data2'=>$data2));
			$this->load->view('footer');
		}
		
		function photos(){
			$data1 = array(
							'title' => "Samoilovi.ru | Фоторепортажи",
							'desc' => "\"веб-сайт семьи Самойловых\"",
							'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
							'baseurl' => base_url(),
							'basepath' => getcwd(),
							'islogin' => $this->usrinfo['adminlogin']
						);
			$data2 = $this->albummodel->get_albums_info_list();	 
			$this->load->view('photos',array('data1' => $data1,'data2' => $data2));
			$this->load->view('footer');
		}
		
		function blog(){
			$data1 = array(
							'title' => "Samoilovi.ru | События в нашей жизни",
							'desc' => "\"веб-сайт семьи Самойловых\"",
							'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
							'baseurl' => base_url(),
							'islogin' => $this->usrinfo['adminlogin']
						);
						
			$data3 = $this->blogmodel->count_records();			
						
			$config['base_url'] = base_url().'/blog';	 				// путь к страницам в пейджере
        	$config['total_rows'] = $data3; 							// всего записей
        	$config['per_page'] =  5;   								// количество записей на странице
        	$config['num_links'] = 2;   	 							// количество ссылок в пейджере
        	$config['uri_segment'] = 2;									// указываем где в URL номер страницы
			$config['first_link'] = 'В начало';
			$config['last_link'] = 'В конец';
			$config['next_link'] = 'Далее &raquo;';
			$config['prev_link'] = '&laquo; Назад';
			$config['cur_tag_open'] = '<b>';
			$config['cur_tag_close'] = '</b>';
						
			$from = intval($this->uri->segment(2));			
			$data2['query'] = $this->blogmodel->get_blog_limit_records(5,$from);
			
			foreach ($data2['query'] as $data){
				
				$data->blg_date = $this->operation_date($data->blg_date);
			}			
			$this->pagination->initialize($config);
			$data2['pager'] = $this->pagination->create_links();
				
			$this->load->view('blog',array('data1' => $data1, 'data2' => $data2,'data3' => $data3));
			$this->load->view('footer');
		}
		
		function commentslist(){
			$data1 = array(
							'title' => "Samoilovi.ru | Просмотр коментариев блога",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'islogin' => $this->usrinfo['adminlogin']
						);
			$id = $this->uri->segment(2);
			$data2 = $this->blogmodel->get_blog_record($id);			
			foreach ($data2 as $data){
				
				$data->blg_date = $this->operation_date($data->blg_date);
			}
			
			$data3 = $this->cmntmodel->get_comments_to_blog($id);
			foreach ($data3 as $data){
				
				$data->cmnt_usr_date = $this->operation_date_slash($data->cmnt_usr_date);
			}
			if ($data1['islogin'] == 1){
				$this->load->view('comments',array('data1'=>$data1,'data2'=>$data2,'data3'=>$data3,'data4'=>$this->usrinfo));	
			}else{
				$this->load->view('comments',array('data1'=>$data1,'data2'=>$data2,'data3'=>$data3));
			}
			
			$this->load->view('footer');
		}
		
		function friends(){
			$data1 = array(
							'title' => "Samoilovi.ru | Страница друзей",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'basepath' => getcwd(),
							'islogin' => $this->usrinfo['adminlogin']
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
			$this->load->view('friends',array(
												'data1' => $data1,
												'data2' => $data4,
												'data3' => $data3,
												'data4' => $key
												));
			$this->load->view('footer');
		}
		
		function about(){
			$data = array(
							'title' => "Samoilovi.ru | О нас",
							'desc' => "\"веб-сайт семьи Самойловых\"",
							'keyword' => "\"семейный сайт, любовь, семейные новости, отношения\"",
							'baseurl' => base_url(),
							'islogin' => $this->usrinfo['adminlogin']
						);	
			$this->load->view('about',array('data' => $data));
			$this->load->view('footer');
		}
		
		function cmntnew(){
			
			if(empty($_POST['user_name']) or (empty($_POST['user_email'])) or (empty($_POST['cmnt_text']))){
				
				if (!empty($_SERVER['HTTP_REFERER'])) 
    				header('Location: '.$_SERVER['HTTP_REFERER']);
					return FALSE;
			}
				
		/*	if($_POST['user_name']=='') $_POST['user_name'] = 'Аноним';
			if($_POST['user_email']=='') $_POST['user_email'] = 'E-mail не указан';
			if($_POST['cmnt_text']!=''){
				$_POST['cmnt_text'] = nl2br($_POST['cmnt_text']);
				$_POST['cmnt_text']='<p>'.$_POST['cmnt_text'].'</p>';
			}else{
				$_POST['cmnt_text']='<p>Содержимое коментария не задано</p>';
			}
		*/
			$this->blogmodel->increment_cnt_comments_to_blog($_POST['blog_id']);			
			$this->cmntmodel->insert_record_to_comments($_POST);
			redirect('commentslist/'.$_POST['blog_id']);
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

		function imageslist(){
			
			$data1 = array(
							'title' => "Samoilovi.ru | Фоторепортажи | Фотографии",
							'desc' => "\"\"",
							'keyword' => "\"\"",
							'baseurl' => base_url(),
							'basepath' => getcwd(),
							'islogin' => $this->usrinfo['adminlogin']
						);
			$alb_id = $this->uri->segment(2);
			
			$data2 = array();
			$data2 = $this->imagesmodel->get_data($alb_id);
			
			$this->load->view('images',array('data1'=>$data1,'data2'=>$data2,'data3'=>$alb_id));
			$this->load->view('footer');	
		}
	}	
?>