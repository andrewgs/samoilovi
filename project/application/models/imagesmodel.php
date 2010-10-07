<?php

	class Imagesmodel extends Model{
	
		var $img_id = 0;
		var $img_title = '';
		var $img_album = '';
		var $img_src = '';
		
		function Imagesmodel(){			
			
			parent::Model();
		}
		
		function get_data($type,$object){
		
			$this->db->where('img_type',$type);
			$this->db->where('img_object',$object);
			
			$query = $this->db->get('images');
			return $query->result_array();
		}
		
		function get_type_data($type){
		
			$this->db->where('img_type',$type);
			
			$query = $this->db->get('images');
			return $query->result_array();
		}
		
		function insert_record($data){
			
			$this->img_title 	= $data['imagetitle'];
			$this->img_album 	= $data['album'];
			$this->img_src 		= $data['images'];
			
			$this->db->insert('images', $this);
		}
		
		function update_record($data){
			
			$this->img_id 		= $data['id'];
			$this->img_title 	= $data['imagetitle'];
			$this->img_album 	= $data['album'];
			$this->img_src 		= $data['images'];
			
			$this->db->where('img_id',$this->img_id);
			$this->db->update('images', $this);
		}
		
		
		function get_images_without($type,$object,$without){
		
			$this->db->order_by('img_type asc');
			
			$where = array('img_object' => $object,'img_type' => $type,'img_id !=' => $without);
			$this->db->where($where);
			$query = $this->db->get('images');
			return $query->result_array();
		}
			
		function get_type_ones_image($type,$object){
		
			$this->db->where('img_type',$type);
			$this->db->where('img_object',$object);
			
			$query = $this->db->get('images',1);
			$data = $query->result_array();
			if(isset($data[0])) return $data[0];
			return NULL;
		}
		
		function get_title_image($type,$object){
		
			$this->db->where('img_type',$type);
			$this->db->where('img_object',$object);
			
			$query = $this->db->get('images',1);
			$data = $query->result_array();
			if(isset($data[0])) return $data[0]['img_title'];
			return NULL;
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
			$data = $query->result_array();
			if(isset($data[0])) return $data[0];
			return NULL;
		}
		
		function get_only_image($id){
			
			$this->db->where('img_id',$id);
			$this->db->select('img_image');
			$query = $this->db->get('images');
			$data = $query->result_array();
			return $data[0]['img_image'];
		}
		
		function get_only_big_image($id){
			
			$this->db->where('img_id',$id);
			$this->db->select('img_big_image');
			$query = $this->db->get('images');
			$data = $query->result_array();
			return $data[0]['img_big_image'];
		}
		
		function image_delete($id){
			
			$this->db->delete('images',array('img_id'=>$id));
		}
		
		function images_delete($album_id){
			
			$this->db->delete('images',array('img_album'=>$album_id));
		}
	}
?>