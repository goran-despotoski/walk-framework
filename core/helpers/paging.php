<?php 
function ShowAdminPaging($prefix_array = array()) 
{
	$global = GlobalRegistry::getInstance();
	$parameters = array_merge($prefix_array, array($global->current_page-1,$global->col,$global->sort));
	if($global->current_page > 1)
		url($global->controller,$global->action,'Претходна',$parameters);
		
	if($global->pages > 0)
		echo " Страна ".$global->current_page . " од ". $global->pages ." ";
	$parameters = array_merge($prefix_array, array($global->current_page+1,$global->col,$global->sort));
	if($global->current_page < $global->pages)
		url($global->controller,$global->action,'Следна',$parameters);
}

function ShowPublicPaging($prefix_array = array()) 
{
	$global = GlobalRegistry::getInstance();
	
	$parameters = array_merge($prefix_array, array($global->current_page-1,$global->col,$global->sort));
	
	if($global->current_page > 1)
		url($global->controller,$global->action,'Претходна',$parameters);
		
	if($global->pages > 0)
		echo " Страна ".$global->current_page . " од ". $global->pages ." ";
	
	$parameters = array_merge($prefix_array, array($global->current_page+1,$global->col,$global->sort));
	
	if($global->current_page < $global->pages)
		url($global->controller,$global->action,'Следна',$parameters);
}
?>