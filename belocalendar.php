<?php

/*
Plugin Name: Belocalendar
Plugin URI: https://faltantornillos.net
Description: A simple events calendar
Version: 1.2
Author: faltantornillos
Author URI: https://faltantornillos.net
License: GPLv3 or later
Text Domain: belocalendar
*/

/*
 * Copyright (C) 2016 Daniel Monedero Tortola faltantornillos@gmail.com faltantornillos.net
 *
 * This file is part of Belocalendar.
 *
 * Belocalendar is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Belocalendar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Belocalendar. If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('WPINC'))
{
	die;
}

function activate_belocalendar()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-belocalendar-activator.php';

	Belocalendar_Activator::activate();
}

function deactivate_belocalendar()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-belocalendar-deactivator.php';

	Belocalendar_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_belocalendar');
register_deactivation_hook(__FILE__, 'deactivate_belocalendar');
require plugin_dir_path(__FILE__) . 'includes/class-belocalendar.php';

function run_belocalendar()
{
	$plugin = new Belocalendar();
	$plugin->run();
}

run_belocalendar();
