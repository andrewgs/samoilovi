<?php
	class Cmntmodel extends Model{
		
		var $cmnt_id = 0;
		var $cmnt_blog_id = 0;
		var $cmnt_usr_name = '';
		var $cmnt_usr_email = '';
		var $cmnt_web = '';
		var $cmnt_usr_date = '';
		var $cmnt_text = '';		
		
		function Cmntmodel(){
		
			parent::Model();
		}
		
		function get_comments_to_blog($blog_id){
			
			$this->db->where('cmnt_blog_id',$blog_id);
			$this->db->order_by('cmnt_usr_date desc, cmnt_id desc');
			$query = $this->db->get('comments');
			return $query->result();			
		}
		
		function get_comment_record($id){
			
			$this->db->where('cmnt_id',$id);
			$query = $this->db->get('comments',1);
			return $query->result();
		}
		
		function insert_record_to_comments($data){
			
			$this->cmnt_blog_id = $data['blog_id'];
			$this->cmnt_usr_name = $data['user_name'];
			$this->cmnt_usr_email = $data['user_email'];
			$this->cmnt_web = $data['homepage'];
			$this->cmnt_usr_date = date("Y-m-d"); 
			$this->cmnt_text = strip_tags($data['cmnt_text'],'<p> <br>');
			
			$this->db->insert('comments', $this);
		}
		
		function delete_records_to_comments($blog_id){
			
			$this->db->delete('comments', array('cmnt_blog_id' => $blog_id));
		}
		
		function delete_record_to_comments($comment_id){
			
			$this->db->delete('comments', array('cmnt_id' => $comment_id));
		}
		
		function update_record_to_comments($data){
		
			$this->cmnt_id = $data['id'];
			$this->cmnt_blog_id = $data['blog_id'];
			$this->cmnt_usr_name = $data['user_name'];
			$this->cmnt_usr_email = $data['user_email'];
			$this->cmnt_web = $data['homepage'];
			$this->cmnt_usr_date = $data['user_date'];
			$this->cmnt_text = $data['cmnt_text'];
			
			$this->db->where('cmnt_id', $this->cmnt_id);
			$this->db->update('comments', $this);
		}
	}
?>