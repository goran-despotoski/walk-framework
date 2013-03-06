<?php
// use walkmvc\data\GlobalRegistry;
$global = GlobalRegistry::getInstance();/* @var $global GlobalRegistry */
$global->subFolder			=	$appSubFolder; /** @var $global->fileSystemPath string */

$global->siteUrl			=	"http://" . $_SERVER['HTTP_HOST'] . "/". $global->basePath . $global->subFolder; 

$global->RelativeSiteUrl	=	"/" . $global->subFolder;
$global->fileSystemPath		=	$appPath; /* @var $global->fileSystemPath GlobalRegistry */

$global->modelPath 			=	$global->fileSystemPath ."model/"; 
$global->controllerPath		=	$global->fileSystemPath ."controller/";
$global->viewPath			=	$global->fileSystemPath ."view/";

$global->defaultController	=	"main";
$global->defaultAction		=	"index";

$global->userFriendlyUrls	=	false;

$global->md5Salt 			=	'Lk63G';

//Database connection parameters

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