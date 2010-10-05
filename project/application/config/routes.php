<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";

// routes - Home;
$route['index']								= "home/index";
$route['']									= "home/index";
$route['photo-albums'] 						= "home/albums";
$route['events'] 							= "home/events";
$route['events/:num'] 						= "home/events";
$route['event/:num'] 						= "home/event";
$route['photo-albums/gallery/:num'] 		= "home/photo";
$route['about'] 							= "home/about";
$route['friends'] 							= "home/friends";

// routes - admin;
$route['admin/login'] 						= "admin/login";
$route['login'] 							= "admin/login";
$route[':any/login']						= "admin/login";
$route[':any/admin']						= "admin/login";
$route['admin'] 							= "admin/index";
$route['logoff'] 							= "admin/logoff";
$route['admin/events'] 						= "admin/events";
$route['admin/events/:num']					= "admin/events";
$route['admin/event/:num'] 					= "admin/event";
$route['admin/event-new'] 					= "admin/eventnew";
$route['admin/event-edit/:num'] 			= "admin/eventedit";
$route['admin/event-destroy/:num'] 			= "admin/eventdestroy";
$route['admin/comment-insert'] 				= "admin/event";
$route['admin/comment-edit/:num/:num']		= "admin/commentedit";
$route['admin/comment-destroy/:num/:num'] 	= "admin/commentdestroy";

$route['admin/comments']					= "admin/comments";
$route['admin/comments/:num']				= "admin/comments";



$route['admin/album/:num/addphoto'] = "/admin/addphoto/";
$route['admin/album/:num/deletephoto/:num'] = "/admin/deletephoto/";
$route['admin/album/:num/images'] = "/admin/imageslist/";

$route['album/:num/images'] = "/home/imageslist/";
$route['admin/uploadify'] = "admin/uploadify/";

//other
$route[':any']			 = "home/page404";