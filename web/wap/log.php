<?php
function mo_log($msg, $dir = '')
{
	global $mo_log_dir;
	
	if ( !$dir )
	{
		$dir = $mo_log_dir;
	}
	
	if ( !is_dir($dir) )
	{
		if ( !mkdir($dir, 0755) )
		{
			return;
		}
	}
	
	$time = gmdate("Y-m-d H:i:s");
	$msg = "$time\t$msg\n";
	
	$date = gmdate("Ym");
	$file = "$dir/$date.log";
	
	if ( !( $fp = fopen($file, 'a') ) )
	{
		return;
	}
	fputs($fp, $msg);
	fclose($fp);
}

function mo_echo_env()
{
	global $userdata, $board_config;
	
	$log = "\nuserdata:\n";
	foreach ( $userdata as $key => $value )
	{
		$log .= "$key\t->\t$value\n";
	}
	
	$log .= "board_config:\n";
	foreach ( $board_config as $key => $value )
	{
		$log .= "$key\t->\t$value\n";
	}
	
	mo_log($log);
}

function mo_echo_wurfl()
{
	global $mo_enable_wurfl;
	
	if ( !$mo_enable_wurfl )
	{
		return;
	}
	
	global $wurfl;
	
	$log = "\nwurfl:\n";
	foreach ( $wurfl as $key => $value )
	{
		if ( $key != '_wurfl' && $key != '_wurfl_agents' && 
		    $key != 'capabilities' )
		{
			$log .= "$key\t->\t$value\n";
		}
	}
	
	while ( $capability = key($wurfl->capabilities) )
	{
		if ( $capability != 'user_agent' && $capability != 'fall_back' && 
		    $capability != 'id' && $capability != 'actual_device_root' )
		{
			$log .= "$capability:\n";
			foreach ( $wurfl->capabilities[$capability] as $key => $value )
			{
				$log .= "$key\t->\t$value\n";
			}
		}
		next($wurfl->capabilities);
	}
	
	mo_log($log);
}
?>
