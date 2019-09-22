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

class Belocalendar

{
	protected $loader;
	protected $plugin_name;
	protected $version;
	public

	function __construct()
	{
		$this->plugin_name = 'belocalendar';
		$this->version = '1.2.0';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	function run()
	{
		$this->loader->run();
	}

	function get_plugin_name()
	{
		return $this->plugin_name;
	}

	function get_loader()
	{
		return $this->loader;
	}

	function get_version()
	{
		return $this->version;
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-belocalendar-loader.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-belocalendar-i18n.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-belocalendar-admin.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-belocalendar-public.php';

		$this->loader = new Belocalendar_Loader();
	}

	private function set_locale()
	{
		$plugin_i18n = new Belocalendar_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_admin_hooks()
	{
		require_once plugin_dir_path(__FILE__) . '../admin/partials/belocalendar-admin-display.php';

	}

	private function define_public_hooks()
	{
		require_once plugin_dir_path(__FILE__) . '../public/partials/belocalendar-public-display.php';

	}
}
