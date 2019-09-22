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

$plugin_admin = new Belocalendar_Admin($this->get_plugin_name() , $this->get_version());
$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
add_action('admin_menu', 'test_plugin_setup_menu');

function test_plugin_setup_menu()
{
	add_menu_page('Belocalendar', 'Belocalendar', 'manage_options', 'belocalendar', 'belocalendar_admin_page');
}

function belocalendar_admin_page()
{
	if (current_user_can('administrator'))
	{
		$id = 0;
		$event = '';
		$time = '';
		$place = '';
		$url = '';
		global $wpdb;
		if (isset($_GET['id']))
		{

			// display existing

			$id = intval(stripslashes_deep($_GET['id']));
			$eventRow = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'belocalendar WHERE id = %d', $id));
			if ($eventRow != null)
			{
				$event = $eventRow->event;
				$time = $eventRow->time;
				$place = $eventRow->place;
				$url = $eventRow->url;
			}
			else
			{
				$id = 0;
			}
		}
		else
		{
			if (isset($_POST['id']) && (intval($_POST['id'] > 0))) {
				if (isset($_POST['action']) && ($_POST['action'] == 'delete')) {

					// delete

					$id = intval(stripslashes_deep($_POST['id']));
					$wpdb->delete($wpdb->prefix . 'belocalendar', array(
						'id' => $id
					), array(
						'%d'
					));
				} else {

					// update

					$id = intval(stripslashes_deep($_POST['id']));
					$event = stripslashes_deep($_POST['event']);
					$time = stripslashes_deep($_POST['time']);
					$place = stripslashes_deep($_POST['place']);
					$url = stripslashes_deep($_POST['url']);
					$wpdb->update($wpdb->prefix . 'belocalendar', array(
						'event' => $event,
						'time' => $time,
						'place' => $place,
						'url' => $url
					), array(
						'id' => $id
					), array(
						'%s',
						'%s',
						'%s',
						'%s'
					), array(
						'%d'
					));
				}
			} else {
				if (isset($_POST['event'])) {

					// create

					$event = stripslashes_deep($_POST['event']);
					$time = stripslashes_deep($_POST['time']);
					$place = stripslashes_deep($_POST['place']);
					$url = stripslashes_deep($_POST['url']);
					$wpdb->insert($wpdb->prefix . 'belocalendar', array(
						'event' => $event,
						'time' => $time,
						'place' => $place,
						'url' => $url
					), array(
						'%s',
						'%s',
						'%s',
						'%s'
					));
					$id = $wpdb->insert_id;
				}
			}
		}

		?>
		<h1>Belocalendar</h1>
		<form method="post" action="/wp-admin/admin.php?page=belocalendar">
			<input type="hidden" name="id" value="<?php echo esc_attr($id) ?>">
			<label for="time" class="belocalendar-label"><?php echo __('Time', 'belocalendar') ?></label>
			<input type="text" size="60" name="time" id="time" value="<?php echo esc_attr($time) ?>"
				   placeholder="2016-12-31 23:59:59"><br />
			<label for="event" class="belocalendar-label"><?php echo __('Event', 'belocalendar') ?></label>
			<input type="text" size="60" name="event" id="event" value="<?php echo esc_attr($event) ?>"><br />
			<label for="place" class="belocalendar-label"><?php echo __('Place', 'belocalendar') ?></label>
			<input type="text" size="60" name="place" id="place" value="<?php echo esc_attr($place) ?>"><br />
			<label for="url" class="belocalendar-label"><?php echo __('Url', 'belocalendar') ?></label>
			<input type="text" size="60" name="url" id="url" value="<?php echo esc_attr($url) ?>" placeholder="http://..."><br />
			<button name="action" value="save" type="submit"><?php echo __('Save', 'belocalendar') ?></button>
			<?php if ($id > 0) { ?>
			<button name="action" value="delete" type="submit" onclick="return confirm('<?php echo __('Are you sure you want to delete this event?', 'belocalendar') ?>')"><?php echo __('Delete', 'belocalendar') ?></button>
			<?php } ?>
		</form>
		<?php
	}
}