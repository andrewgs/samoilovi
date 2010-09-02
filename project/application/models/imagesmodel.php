<?php

	class Imagesmodel extends Model{
	
		var $img_id = 0;
		var $img_src = '';
		var $img_album = '';
		var $img_title = '';
	
		function Imagesmodel(){			
			
			parent::Model();
		}
		
		function insert_record($data){
			
			$this->img_src = $data['file'];
			$this->img_album = $data['alb_id'];
			$this->img_title = $data['imagetitle'];
			
			$this->db->insert('images', $this);
		}
		
		function get_data($album){
			$this->db->where('img_album',$album);
			
			$query = $this->db->get('images');
			return $query->result();
		}
		
		function get_image_all_data($type,$object,$without){
		
			$this->db->order_by('img_type asc');
			
			$where = array('img_object' => $object,'img_type' => $type,'img_id !=' => $without);
			$this->db->where($where);
			$query = $this->db->get('images');
			return $query->result();
		}
			
		function get_type_ones_image($type,$object){
		
			$this->db->where('img_type',$type);
			$this->db->where('img_object',$object);
			
			$query = $this->db->get('images',1);
			return $query->result();
		}
		
		function get_ones_image($type,$object){
		
			$this->db->order_by('img_id asc');
			$this->db->where('img_type',$type);
			$this->db->where('img_object',$object);
			
			$query = $this->db->get('images',1);
			return $query->result();
		}
		
		function get_image($id){
			
			$this->db->where('img_id',$id);
			$query = $this->db->get('images');
			return $query->result();
		}
		
		function image_delete($id){
			
			$this->db->delete('images', array('img_id' => $id));
		}
		
		function image_album_delete($id){
			
			$this->db->delete('images', array('img_album' => $id));
		}
	}
?>