<?php
/**
 *  Habrarabr.ru Habrometr.
 *  Copyright (C) 2009 Leontyev Valera
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

set_include_path(dirname(__FILE__) . PATH_SEPARATOR . 
	realpath(dirname(__FILE__) . '/lib') . PATH_SEPARATOR . get_include_path());
require('Lpf/Loader.php');

Lpf_Loader::loadClass('Lpf_ErrorHandler');
set_exception_handler(array('Lpf_ErrorHandler', 'exceptionHandler'));
Lpf_Loader::registerAutoload();
set_time_limit(1800);

if (isset($_SERVER['REQUEST_METHOD']))
{
	header('Forbidden', true, 403);
	die('Forbidden');
}

$h = Habrometr_Model::getInstance();
$users = $h->getUserList();
$errorCounter = 0;
foreach ($users as $key => $user)
{
	try
	{
		$h->putValues($user['user_id']);
	}
	catch (Exception $e)
	{
		$errorCounter++;
		echo "Error updating user #$key";
		if ($errorCounter > 30)
			break;
	}
	echo ".";
}

echo "\r\nUsers updated: $key";

system('rm -f ' . dirname(__FILE__) . '/image_cache/*');