<?php
// use walkmvc\data\GlobalRegistry;
$global = GlobalRegistry::getInstance();/* @var $global GlobalRegistry */

$environment = (isset($_SERVER["APPLICATION_ENV"]))?$_SERVER["APPLICATION_ENV"]:"live";

$global->subFolder			= "";
if($environment == "development")
	$global->subFolder			=	"phpwalk/"; /** @var $global->fileSystemPath string */

$global->siteUrl			=	"http://" . $_SERVER['HTTP_HOST'] . "/" . $global->subFolder; 
$global->RelativeSiteUrl	=	"/" . $global->subFolder;
$global->fileSystemPath		=	$_SERVER['DOCUMENT_ROOT']."/" . $global->subFolder; /* @var $global->fileSystemPath GlobalRegistry */

$global->modelPath 			=	$global->fileSystemPath ."application/model/"; 
$global->controllerPath		=	$global->fileSystemPath ."application/controller/";
$global->viewPath			=	$global->fileSystemPath ."application/view/";

$global->defaultController	=	"main";
$global->defaultAction		=	"index";

$global->userFriendlyUrls	=	false;

$global->md5Salt 			=	'Lk63G';

//Database connection parameters

$db=array();


$db_dev=array();
$db_dev['type']="mysql";
$db_dev['host']="localhost";
$db_dev['database']="manage";
$db_dev['user']="root";
$db_dev['password']="root";

if($environment != "development")
{
	$global->db=array(
			'type'=>"mysql",
			'host'=>"localhost",
			'database'=>"test_blog",
			'user'=>"root",
			'password'=>"root"
			);
}else
{
	$global->db=array(
			'type'=>"mysql",
			'host'=>"localhost",
			'database'=>"test_blog",
			'user'=>"root",
			'password'=>"root"
		);
}

$global->viewData=array();
$global->viewData['site_title']='GTO Studio Project Management Environment';
$global->viewData['meta_description']='GTO Studio Project Management Environment Meta Description';


$global->libraries = array();
$global->helpers = array("input","url", "time", "output");

$global->timeFormat = "G:i d.m.Y";

$global->page_size = 2;
$global->last_n = 2;

$global->under_construction = false;
?>