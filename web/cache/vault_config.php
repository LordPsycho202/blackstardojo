<?php
/***************************************************************************
 *						vault_config.php
 *						----------------
 *	begin			: 15/02/2004
 *	copyright		: Ptirhiik
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}


$vault_config = array(
	'vault_loan_enable' => '1',
	'interests_rate' => '3',
	'interests_time' => '1209600',
	'loan_interests' => '3',
	'loan_interests_time' => '1209600',
	'loan_max_sum' => '10000',
	'loan_requirements' => '100',
	'stock_max_change' => '5',
	'stock_min_change' => '1',
	'base_amount' => '1500',
	'num_items' => '50',
);

?>