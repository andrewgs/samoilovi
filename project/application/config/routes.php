<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";

$route['about'] = "/home/about/";
$route['friends'] = "/home/friends/";
$route['blog'] = "/home/blog/";
$route['album'] = "/home/photos/";
$route['admin'] = "/admin/index/";
$route['blog/:num'] = "/home/blog/";
$route['commentslist/:num'] = "/home/commentslist/";
$route['cmntnew/:num'] = "/home/cmntnew/";
$route['cmntnew'] = "/home/cmntnew/";

$route['admin/album/:num/addphoto'] = "/admin/addphoto/";
$route['admin/album/:num/deletephoto/:num'] = "/admin/deletephoto/";
$route['admin/album/:num/images'] = "/admin/imageslist/";

$route['album/:num/images'] = "/home/imageslist/";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */