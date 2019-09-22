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

class Belocalendar_Activator

{
	static function activate()
	{
		global $wpdb;
		global $jal_db_version;
		$jal_db_version = '1.0';
		$table_name = $wpdb->prefix . 'belocalendar';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = 'CREATE TABLE '.$table_name.' (
			id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
			event text NOT NULL,
			time datetime DEFAULT \'0000-00-00 00:00:00\' NOT NULL,
			place text NOT NULL,
			url text NOT NULL,
			UNIQUE KEY id (id)
		) '.$charset_collate.';';
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($sql);
		add_option('jal_db_version', $jal_db_version);
	}
}