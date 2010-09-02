<?php
	class Socialmodel extends Model{
		
		var $soc_id = 0;
		var $soc_fr_id = 0;
		var $soc_name = '';
		var $soc_href = '';
		
		function Socialmodel(){
			
			parent::Model();
		}
		
		function get_friend_social_info_list(){
			
			$this->db->order_by('soc_id asc');
			$query = $this->db->get('social');
			return $query->result();
		}
		
		function get_friend_social_info($id){
			
			$this->db->where('soc_fr_id',$id);
			$query = $this->db->get('social',2);
			return $query->result();
		}
		
		function insert_record_to_social($data){
		
			$this->soc_fr_id = $data['friend_id'];
			$this->soc_name = $data['social'];
			$this->soc_href = $data['href'];
			
			$this->db->insert('social', $this);
		}
	
		function delete_record_to_social($id){
			
			$this->db->delete('social', array('soc_fr_id' => $id));
		}
		
		function delete_record_from_update($id){
			
			$this->db->delete('social', array('soc_id' => $id));
		}
		
		function update_record_to_social($data){
		
			$this->soc_id = $data['id'];
			$this->soc_fr_id = $data['friend_id'];
			$this->soc_name = $data['social'];
			$this->soc_href = $data['href'];
			
			$this->db->where('soc_id',$this->soc_id);
			$this->db->update('social', $this);
		}	
	}
?>