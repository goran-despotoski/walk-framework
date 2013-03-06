<?php
/**
 * 
 * Truncates the text with wanted size, break characted and adds pad, returns the same string when size is less than $limit
 * @param string $string String to be truncated
 * @param integer $limit Length of result string 
 * @param character $break What is the end delimiter to break at 
 * @param string $pad What should be shown on end of truncated string() 
 * 
 * @author Goran Despotoski
 */
function truncateText($string, $limit, $break=" ", $pad="...") 
{
	if(mb_strlen($string) <= $limit) return $string; // is $break present between $limit and the end of the string?
	if(false !== ($breakpoint = mb_strpos($string, $break, $limit)))
	{
		if($breakpoint < mb_strlen($string) - 1)
		$string = mb_substr($string, 0, $breakpoint) . $pad;
	}
	return $string;
}
?>