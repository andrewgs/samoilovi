<?php
	class Friendsmodel extends Model{
		
		var $fr_id = 0;
		var $fr_name = '';
		var $fr_profession = '';
		var $fr_social = '';
		var $fr_note = '';
		var $fr_image = '';
		
		function Friendsmodel(){
			
			parent::Model();
		}
		
		function friends_records(){
			
			$this->db->order_by('fr_id asc');
			$query = $this->db->get('friends');
			return $query->result_array();
		}
		
		function get_friend_info($id){
		
			$this->db->where('fr_id',$id);
			$query = $this->db->get('friends', 1);
			return $query->result();
		}
		
		function get_image($friend_id){
		
			$this->db->where('fr_id',$friend_id);
			$this->db->select('fr_image');
			$query = $this->db->get('friends');
			$data = $query->result_array();
			return $data[0]['fr_image'];
		}
		
		function insert_record_to_friends($data,$social){
			
			$this->fr_name = $data['name'];
			$this->fr_profession = $data['profession'];
			$this->fr_social = $social;
			$this->fr_image = $data['userfile'];
			$this->fr_note = $data['note'];
			
			if (strlen($this->fr_note) > 450)
				$this->fr_note = substr($this->fr_note,0,450);				
			
			$this->db->insert('friends', $this);
			$id = $this->db->insert_id();
			
			return $id;
		}
		
		function delete_record_to_friend($id){
			
			$this->db->delete('friends', array('fr_id' => $id));
		}
		
		function update_record_to_friends($data,$social){
			
			$this->fr_id = $data['fr_id'];
			$this->fr_name = $data['name'];
			$this->fr_profession = $data['profession'];
			$this->fr_social = $social;
			$this->fr_note = $data['note'];
			$this->fr_image = $data['userfile'];
			
			if (strlen($this->fr_note) > 450)
				$this->fr_note = substr($this->fr_note,0,450);
			
			$this->db->where('fr_id',$this->fr_id);
			$this->db->update('friends', $this);
		}

		function reset_social_count($id){
			$this->db->set('fr_social', 0, FALSE);
			$this->db->where('fr_id',$id);
			$this->db->update('friends');
		}
	}
?>