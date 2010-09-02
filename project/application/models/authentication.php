<?php

	class Authentication extends Model{
	
		var $usr_id = 0;
		var $usr_login = '';
		var $usr_first_name = '';
		var $usr_second_name = '';
		var $usr_email = '';
		var $usr_password = '';
		
		function Authentication(){			
			
			parent::Model();
		}
		
		function get_users_info($login){
			$this->db->where('usr_login',$login);
			$query = $this->db->get('users');
			return $query->result();
		}
		
		function changepassword($data){
			
			$pass = md5($data['newpass']);
			$pass_crypt = $data['pass_crypt'];
			$this->db->set('usr_password',$pass);
			$this->db->set('usr_pass_crypt',$pass_crypt);
			$this->db->where('usr_id',$data['id']);
			$this->db->update('users'); 
		}
	}
?>