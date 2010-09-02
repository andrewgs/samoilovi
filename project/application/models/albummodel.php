<?php
	class Albummodel extends Model{
		
		var $alb_id = 0;
		var $alb_name = '';
		var $alb_title = '';
		var $alb_amt = '';
		var $alb_annotation = '';
		var $alb_photo = '';
		var $alb_photo_title = '';
		var $alb_date = '';
		
		function Albummodel(){
			
			parent::Model();
		}
		
		function get_albums_info_list(){
			
			$this->db->order_by('alb_id desc');
			$query = $this->db->get('album');
			return $query->result();
		}
		
		function get_album_info($id){
		
			$this->db->where('alb_id',$id);
			$query = $this->db->get('album', 1);
			return $query->result();
		}
		
		function increment_amt_to_album($id){
			$this->db->set('alb_amt', 'alb_amt+1', FALSE);
			$this->db->where('alb_id',$id);
			$this->db->update('album');
		}
		
		function decrement_amt_to_album($id){
			$this->db->set('alb_amt', 'alb_amt-1', FALSE);
			$this->db->where('alb_id',$id);
			$this->db->update('album');
		}
		
		function insert_record_to_album($data){
			
			$this->alb_name = $data['name'];
			$this->alb_title = $data['title'];
			$this->alb_amt = 0;
			$this->alb_annotation = $data['annotation'];
			$this->alb_photo = $data['userfile'];
			$this->alb_photo_title = $data['photo_title']; 
			$this->alb_date = date("Y-m-d");

			$this->db->insert('album', $this);
		}
		
		function delete_record_to_album($id){
			
			$this->db->delete('album', array('alb_id' => $id));
		}
		
		function update_record_to_album($data){
		
			$this->alb_id = $data['id'];
			$this->alb_name = $data['name'];
			$this->alb_title = $data['title'];
			$this->alb_amt = $data['amt'];
			$this->alb_annotation = $data['annotation'];
			$this->alb_photo = $data['userfile'];
			$this->alb_photo_title = $data['photo_title']; 
			$this->alb_date = date("Y-m-d");
			
			$this->db->where('alb_id', $this->alb_id);
			$this->db->update('album', $this);	
		}
}