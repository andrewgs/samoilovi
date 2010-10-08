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
$route['album/viewimage/:num']				= "home/viewimage";
$route['small/viewimage/:num']				= "home/viewimage";
$route['big/viewimage/:num']				= "home/viewimage";

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
$route['admin/album-gallary']				= "admin/albums";
$route['admin/album-new']					= "admin/albumnew";
$route['admin/album-edit/:num'] 			= "admin/albumedit";
$route['admin/album-destroy/:num'] 			= "admin/albumdestroy";

$route['admin/photo-gallary/:num']			= "admin/photos";
$route['admin/photo-destory/:num']			= "admin/photodestroy";
$route['admin/photo-multiupload']			= "admin/multiupload";

$route['admin/friends']						= "admin/friends";
$route['admin/friend-new']					= "admin/friendnew";

//other
$route[':any']			 = "home/page404";