<?php

/**
 * Fired during plugin activation
 *
 * @link       https://podi.dk
 * @since      1.0.0
 *
 * @package    bilinfo
 * @subpackage bilinfo/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    bilinfo
 * @subpackage bilinfo/includes
 * @author     Podi <info@podi.dk>
 */
class bilinfo_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		self::add_log_table();
		self::add_queue_table();
	}


	private static function add_log_table()
	{
		global $table_prefix, $wpdb;

		$tblname        = 'bilinfo_log';
		$wp_track_table = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		if ($wpdb->get_var("show tables like '$wp_track_table'") != $wp_track_table) {

			$sql = "CREATE TABLE " . $wp_track_table . " ( ";
			$sql .= "  id  int(11)   NOT NULL auto_increment, ";
			$sql .= "  type VARCHAR(55) NOT NULL, ";
			$sql .= "  input TEXT NULL, ";
			$sql .= "  response TEXT NULL, ";
			$sql .= "  time DATETIME NOT NULL, ";
			$sql .= "  PRIMARY KEY log_id (id) ";
			$sql .= ");";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	private static function add_queue_table()
	{
		global $table_prefix, $wpdb;

		$tblname        = 'bilinfo_media_queue';
		$wp_track_table = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		if ($wpdb->get_var("show tables like '$wp_track_table'") != $wp_track_table) {

			$sql = "CREATE TABLE " . $wp_track_table . " ( ";
			$sql .= "  id  int(11)   NOT NULL auto_increment, ";
			$sql .= "  type VARCHAR(55) NOT NULL, ";
			$sql .= "  url TEXT NOT NULL, ";
			$sql .= "  post_id int(11) NOT NULL, ";
			$sql .= "  time DATETIME NOT NULL, ";
			$sql .= "  priority int(3) NOT NULL DEFAULT 50, ";
			$sql .= "  PRIMARY KEY queue_id (id) ";
			$sql .= ");";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
}
