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

$plugin_public = new Belocalendar_Public($this->get_plugin_name() , $this->get_version());
$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
add_action('belocalendar_hook', 'belocalendar_hook_content');

function belocalendar_hook_content()
{
	function month_name_from_number($month_number)
	{
		switch ($month_number)
		{
		case 1:
			return __('Jan', 'belocalendar');
			break;

		case 2:
			return __('Feb', 'belocalendar');
			break;

		case 3:
			return __('Mar', 'belocalendar');
			break;

		case 4:
			return __('Apr', 'belocalendar');
			break;

		case 5:
			return __('May', 'belocalendar');
			break;

		case 6:
			return __('Jun', 'belocalendar');
			break;

		case 7:
			return __('Jul', 'belocalendar');
			break;

		case 8:
			return __('Aug', 'belocalendar');
			break;

		case 9:
			return __('Sep', 'belocalendar');
			break;

		case 10:
			return __('Oct', 'belocalendar');
			break;

		case 11:
			return __('Nov', 'belocalendar');
			break;

		case 12:
			return __('Dec', 'belocalendar');
			break;
		}
	}

	if (isset($_GET['belocalendar-month']) && isset($_GET['belocalendar-year']))
	{
		$getMonth = intval($_GET['belocalendar-month']);
		$getYear = intval($_GET['belocalendar-year']);
		$selectedDateTime = mktime(0, 0, 0, $getMonth, 1, $getYear);
		$selectedDay = - 1;
	}
	else
	{
		$selectedDateTime = current_time('timestamp');
		$selectedDay = date('d', $selectedDateTime);
	}

	$selectedMonthNumber = date('m', $selectedDateTime);
	$selectedYear = date('Y', $selectedDateTime);
	$previousMonth = ($selectedMonthNumber > 1 ? $selectedMonthNumber - 1 : 12);
	$previousYear = ($selectedMonthNumber > 1 ? $selectedYear : $selectedYear - 1);
	$nextMonth = ($selectedMonthNumber > 11 ? 1 : $selectedMonthNumber + 1);
	$nextYear = ($selectedMonthNumber > 11 ? $selectedYear + 1 : $selectedYear);
	$numberDaysInMonth = date('t', $selectedDateTime);

	// 0 = Sunday

	$firstDayInMonth = getdate(mktime(0, 0, 0, $selectedMonthNumber, 1, $selectedYear)) ['wday'];
	$firstDayInMonth--;
	if ($firstDayInMonth == - 1)
	{
		$firstDayInMonth = 6;
	}

?>
	<table id='belocalendar'>
		<tr id='belocalendar-header'>
			<th><a href='?belocalendar-month=<?php echo esc_attr($previousMonth) ?>&amp;belocalendar-year=<?php echo esc_attr($previousYear) ?>'>&lt;</a></th>
			<th colspan='5' class='belocalendar-month'><?php echo month_name_from_number(esc_attr($selectedMonthNumber)) ?> <?php echo esc_html($selectedYear) ?></th>
			<th><a href='?belocalendar-month=<?php echo esc_attr($nextMonth) ?>&amp;belocalendar-year=<?php echo esc_attr($nextYear) ?>'>&gt;</a></th>
		</tr>
		<tr id='belocalendar-days'>
			<td><?php echo __('M', 'belocalendar') ?></td>
			<td><?php echo __('T', 'belocalendar') ?></td>
			<td><?php echo __('W', 'belocalendar') ?></td>
			<td><?php echo __('Th', 'belocalendar') ?></td>
			<td><?php echo __('F', 'belocalendar') ?></td>
			<td><?php echo __('S', 'belocalendar') ?></td>
			<td><?php echo __('Su', 'belocalendar') ?></td>
		</tr><?php
	global $wpdb;
	$events = $wpdb->get_results($wpdb->prepare('SELECT id, DAY(time) day, TIME(time) time, event, place, url 
						FROM ' . $wpdb->prefix . 'belocalendar 
						WHERE time BETWEEN \'%d-%d-1 0:0:0\' AND \'%d-%d-%d 23:59:59\' ORDER BY time ASC', $selectedYear, $selectedMonthNumber, $selectedYear, $selectedMonthNumber, $numberDaysInMonth));
	$eventsMap = array();
	foreach((array)$events as $event)
	{
		$eventDay = $event->day;
		if (!array_key_exists($eventDay, $eventsMap))
		{
			$eventsMap[$eventDay] = array();
		}

		array_push($eventsMap[$eventDay], $event);
	}

	$currentDay = 0;
	$currentWeek = 0;
	while ($currentDay < $numberDaysInMonth)
	{
?>

		<tr><?php
		for ($i = 0; $i < 7; $i++)
		{
			if (($currentDay >= $numberDaysInMonth) || (($currentWeek == 0) && ($i < $firstDayInMonth)))
			{
?>

			<td></td><?php
			}
			else
			{
				$currentDay++;
				if (!array_key_exists($currentDay, $eventsMap))
				{
?>

			<td<?php echo $selectedDay == $currentDay ? ' id=\'belocalendar-current-day\'' : '' ?>><?php echo esc_html($currentDay) ?></td><?php
				}
				else
				{
?>

			<td class='belocalendar-event-day' <?php echo $selectedDay == $currentDay ? 'id=\'belocalendar-current-day\'' : '' ?>><a href='#' onclick='addBelocalendarActiveId("belocalendar-events-day-<?php echo esc_js($currentDay) ?>");' onmouseout='clearTimeout(belocalendarTimer);' onmouseover='belocalendarTimer=setTimeout(function(){addBelocalendarActiveId("belocalendar-events-day-<?php echo esc_js($currentDay) ?>");}, 500);'><?php echo esc_html($currentDay) ?></a><?php
					$eventsString = '<div class=\'belocalendar-events belocalendar-events-day-' . esc_attr($currentDay) . '\'><h3>' . esc_html($currentDay) . '/' . esc_html($selectedMonthNumber) . '/' . esc_html($selectedYear) . '<a href=\'#\' class=\'belocalendar-active-close\' onclick=\'removeBelocalendarActiveId();\' title=\'' . __('Close', 'belocalendar') . '\'>&times;</a></h3>';
					foreach($eventsMap[$currentDay] as $e)
					{
						if (current_user_can('administrator'))
						{
							$eventsString.= '<a href=\'/wp-admin/admin.php?page=belocalendar&amp;id=' . esc_attr($e->id) . '\' target=\'_blank\'>' . __('Edit', 'belocalendar') . '</a> ';
						}

						$eventsString.= esc_html(substr($e->time, 0, -3)) . ' <a href=\'' . esc_attr($e->url) . '\' target=\'_blank\'>' . esc_html($e->event) . ' ' . __('in', 'belocalendar') . ' ' . esc_html($e->place) . '</a><br />';
					}

					$eventsString.= '</div></td>';
					echo ($eventsString);
				}
			}
		}

		$currentWeek++;
		?>

		</tr><?php
	}

?>

	</table>
	<?php
}