<?php
	class Unionmodel extends Model{
		
		function Unionmodel(){
		
			parent::Model();
		}
		
		function select_cmnt_blog_from_list($cntday,$count,$from){
			
			$datesub = 'DATE_SUB(CURDATE(),INTERVAL '.$cntday.' DAY)';
			
			$this->db->select('comments.*,blog.*',FALSE);
			$this->db->from('comments');
			$this->db->join('blog', 'blog.blg_id = comments.cmnt_blog_id','inner');
			$this->db->where('cmnt_usr_date >=',$datesub,FALSE);
			$this->db->where('cmnt_usr_date <=',"CURDATE()",FALSE);
			$this->db->limit($count,$from);
			$this->db->order_by('cmnt_usr_date desc, cmnt_id desc');
			$query = $this->db->get();
			return $query->result();
		}
		
		function get_count_cmnt_blog_record($cntday){
			
			$datesub = 'DATE_SUB(CURDATE(),INTERVAL '.$cntday.' DAY)';
			
			$this->db->select('comments.*,blog.*',FALSE);
			$this->db->from('comments');
			$this->db->join('blog', 'blog.blg_id = comments.cmnt_blog_id','inner');
			$this->db->where('cmnt_usr_date >=',$datesub,FALSE);
			$this->db->where('cmnt_usr_date <=',"CURDATE()",FALSE);
			return $this->db->count_all_results();
		}
	}
?>