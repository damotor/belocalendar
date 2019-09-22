<?php

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

class Belocalendar_i18n

{
	function load_plugin_textdomain()
	{
		load_plugin_textdomain('belocalendar', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
	}
}