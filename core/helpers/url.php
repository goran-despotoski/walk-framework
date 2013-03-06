<?php

/**
 * Creates a HTML url from given parameters(userfriendly if GlobalRegistry::userFriendlyUrls is true, or nonuserfriendly urls
 * if it is false)
 * @param string $controller
 * @param string $action
 * @param array $arguments
 * @param array $plus
 * @param string $class The css class
 * @param string $styles Styles
 */
function url($controller='', $action = '',$title = '' ,$arguments = array(), $get_variables = array(),$class="", $styles="")
{
	echo linkReturn($controller, $action,$title ,$arguments, $get_variables, $class, $styles);
}

/**
 * 
 * Builds a link with given
 * @param string $controller
 * @param string $action
 * @param string $title
 * @param array $arguments
 * @param string $plus
 * @param string $class
 * @param string $styles
 * @author Goran Despotoski
 */
function linkReturn($controller='', $action = '',$title = '' ,$arguments = array(), $get_variables = array(), $class="", $styles="")
{
	$globals = GlobalRegistry::getInstance();
	$args = '';
	$styles = 'style="' . $styles .'"';
	$class= 'class="'.$class.'"';

	return '<a href="'.urlBuild($controller, $action, $arguments, $get_variables) . '" '.$styles.' '.$class.'>'.$title.'</a>';
}

/**
 * 
 * Builds and returns the url for the given controller, action and arguments(array) 
 * @param string $controller
 * @param string $action
 * @param array $arguments
 * @author Goran Despotoski
 */
function urlBuild($controller='', $action = '',$arguments = array(), $get_variables = array())
{
	$globals = GlobalRegistry::getInstance();
	$args = '';
	foreach($arguments as $k=>$v) $args .= '/'.$v;
	if(count($get_variables) > 0 )$args .= "?";
		foreach($get_variables as $k=>$v) $args .= '&' . $k . '='.$v;
	
	return $globals->siteUrl.''.$controller.'/' . $action . $args;
}

/**
 * 
 * Short for die(header()) syntax
 * @param string $where Url to redirect to
 * @author Goran Despotoski
 */
function redirect($where)
{
	$globals = GlobalRegistry::getInstance();
	die(header("Location: ".$where));
}

?>