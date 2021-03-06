<?php
/**
 *  Habrahabr.ru Habrometr.
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

require __DIR__ . '/bootstrap.php';
Log::debug(sprintf('index.php: started (%s)', $_SERVER['REQUEST_URI']));

session_start();

// Routing
$logController = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : '[undefined]';
$logAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : '[undefined]';
Log::debug(sprintf('index.php: routing started (controller = %s, action = %s)', $logController, $logAction));

if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	$actionProcessed = strtolower($action);
	while (false !== ($pos = strpos($actionProcessed, '_')) || false !== ($pos = strpos($actionProcessed, '-')))
	{
		$actionProcessed = substr($actionProcessed, 0, $pos)
			  . strtoupper(substr($actionProcessed, $pos + 1, 1)) . substr($actionProcessed, $pos + 2);
	}
}
else
{
	$action = null;
	$actionProcessed = 'default';
}
if (isset($_REQUEST['controller']))
{
	$controller = $_REQUEST['controller'];
	$controllerProcessed = strtolower($controller);
	while (false !== ($pos = strpos($actionProcessed, '_')) || false !== ($pos = strpos($controllerProcessed, '-')))
	{
		$actionProcessed = substr($actionProcessed, 0, $pos)
			  . strtoupper(substr($actionProcessed, $pos + 1, 1)) . substr($actionProcessed, $pos + 2);
	}
	$controllerProcessed = strtoupper(substr($controller, 0, 1)) . substr($controller, 1);
}
else
{
	$controller = null;
	$controllerProcessed = 'Index';
}
Log::debug(sprintf('index.php: routing finished (controller = %s, action = %s)', $logController, $logAction));

// Dispatch
Log::debug(sprintf('index.php: dispatcherization started (controller = %s, action = %s)',
	$controllerProcessed, $actionProcessed));
ob_start();
Lpf_Dispatcher::dispatch(null, $actionProcessed);
$cont = ob_get_flush();
Log::debug(sprintf('index.php: dispatcherization finished (controller = %s, action = %s)',
	$controllerProcessed, $actionProcessed));

// Cache
if ($cont && $action != 'register' && $action != 'all_users')
{
	$cacheTime = array('default' => 30 * 60, 'user_page' => 15 * 60, 'all_users' => 30 * 60, 'get' => 5 * 60);
	$cacheTimeSeconds = isset($cacheTime[$action]) ? $cacheTime[$action] : 10 * 60;
	$m = new Lpf_Memcache('habrometr');
	$m->set($_SERVER['REQUEST_URI'], $cont . "\r\n<!-- cached version " . date('r') . ' -->', 0, $cacheTimeSeconds);
	Log::debug(sprintf('index.php: cache saved for `%s` expire in %d seconds',
		$_SERVER['REQUEST_URI'], $cacheTimeSeconds));
}

Log::debug(sprintf('index.php: finished (%s)', $_SERVER['REQUEST_URI']));
