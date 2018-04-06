<?php
function mo_get_get_opt($id, $opt = 0)
{
	global $HTTP_GET_VARS;
	
	if ( isset($HTTP_GET_VARS[$id]) )
	{
		$opt = $HTTP_GET_VARS[$id];
	}
	
	return $opt;
}

function mo_get_post_opt($id, $opt = '')
{
	global $HTTP_POST_VARS;
	
	if ( isset($HTTP_POST_VARS[$id]) )
	{
		$opt = $HTTP_POST_VARS[$id];
	}
	
	return $opt;
}
?>
