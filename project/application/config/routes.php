<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";

// routes - Home;
$route['index']							= "home/index";
$route['']								= "home/index";
$route['photo-albums'] 					= "home/albums";
$route['events'] 						= "home/events";
$route['events/:num'] 					= "home/events";
$route['event/:num'] 					= "home/event";
$route['photo-albums/gallery/:num'] 	= "home/photo";
$route['about'] 						= "home/about";
$route['friends'] 						= "home/friends";




$route['admin'] = "/admin/index/";
$route['blog/:num'] = "/home/blog/";
$route['blog-event/:num'] = "/home/commentslist/";
$route['cmntnew/:num'] = "/home/cmntnew/";
$route['comment'] = "/home/comments_new/";
$route['photo-albums/photo-gallery/:num'] = "/home/cmntnew/";

$route['admin/album/:num/addphoto'] = "/admin/addphoto/";
$route['admin/album/:num/deletephoto/:num'] = "/admin/deletephoto/";
$route['admin/album/:num/images'] = "/admin/imageslist/";

$route['album/:num/images'] = "/home/imageslist/";
$route['admin/uploadify'] = "admin/uploadify/";

//other
$route[':any']			 = "home/page404";