<?php
	class Blogmodel extends Model{
		
		var $blg_id = 0;
		var $blg_title = '';
		var $blg_text = '';
		var $blg_date = '';
		var $blg_cnt_cmnt = 0;
		
		function Blogmodel(){
			
			parent::Model();
		}
		
		function get_blog_all_records(){
		
			$this->db->order_by('blg_date desc, blg_id desc');
			$query = $this->db->get('blog');
			return $query->result();
		}
		function get_blog_limit_records($count,$from){
		
			$this->db->limit($count,$from);
			$this->db->order_by('blg_date desc, blg_id desc');
			$query = $this->db->get('blog');
			return $query->result();
		}
		function insert_record_to_blog($data){
		
			$this->blg_title = $data['title'];
			$this->blg_date = $data['date']; 
			$this->blg_text = $data['text'];
			$this->blg_cnt_cmnt = 0;

			$this->db->insert('blog', $this);
		}
		
		function delete_record_to_blog($id){
			
			$this->db->delete('blog', array('blg_id' => $id));
		}		
	
		function update_record_to_blog($data){
			
			$this->blg_id = $data['id'];
			$this->blg_title = $data['title'];
			$this->blg_text = $data['text'];
			$this->blg_cnt_cmnt = $data['cnt'];
			$this->blg_date = $data['date'];
			
			$this->db->where('blg_id', $this->blg_id);
			$this->db->update('blog', $this);
		}
		
		function get_blog_records_to_news(){
		
			$this->db->order_by('blg_date desc, blg_id desc');
			$query = $this->db->get('blog',3);
			return $query->result();
		}
		
		
		
		function get_blog_record($id){
			$this->db->where('blg_id',$id);
			$query = $this->db->get('blog',1);
			return $query->result();
		}
		
		function increment_cnt_comments_to_blog($id){
			$this->db->set('blg_cnt_cmnt', 'blg_cnt_cmnt+1', FALSE);
			$this->db->where('blg_id',$id);
			$this->db->update('blog');
		}
		
		function decrement_cnt_comments_to_blog($id){
			$this->db->set('blg_cnt_cmnt', 'blg_cnt_cmnt-1', FALSE);
			$this->db->where('blg_id',$id);
			$this->db->update('blog');
		}
		
		function count_records(){
		
			return $this->db->count_all('blog');
		}
		
	}

?>