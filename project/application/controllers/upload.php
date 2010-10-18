<?php
class Upload extends Controller
{
	function Upload()	{
		parent::Controller();
		$this->load->helper('form');
		$this->load->helper('url');
	}
	function index(){
		
		$this->load->view('view');
	}
	function uploadify(){
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetPath = $_SERVER['DOCUMENT_ROOT'].$_REQUEST['folder'] . '/';
		$targetFile = str_replace('//','/',$targetPath).$_FILES['Filedata']['name'];
		copy($tempFile,$targetFile);
		if(file_exists($_FILES['Filedata']['tmp_name']) and is_uploaded_file($_FILES['Filedata']['tmp_name'])):
			unlink(file_exists($_FILES['Filedata']['tmp_name']));
		endif;
		return TRUE;
	}
	function up_test(){
		$_REQUEST['folder'] = 'uploads';
		$_FILES['Filedata']['name'] = 'вася.txt';
		$targetPath = $_SERVER['DOCUMENT_ROOT'].$_REQUEST['folder'].'/';
		$targetFile = str_replace('//','/',$targetPath).$_FILES['Filedata']['name'];
//		$targetFile = $targetPath.$_FILES['Filedata']['name'];
		print_r($_REQUEST);
		echo '<br/>';
		print_r($_FILES);
		echo '<br/>';
		echo $targetPath.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$targetFile;
		echo '<br/>';
	}
}
?>
