<?php

/**
 * Fired during plugin activation
 *
 * @link       https://devcrazygit.github.io/
 * @since      1.0.0
 *
 * @package    Cfwjm
 * @subpackage Cfwjm/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cfwjm
 * @subpackage Cfwjm/includes
 * @author     Devcrazy <devcrazy@hotmail.com>
 */
class Cfwjm_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		include_once 'class-cfwjm-db.php';	
		Cfwjm_Db::install();			
	}

	public static function update(){
		include_once 'class-cfwjm-db.php';	
		Cfwjm_Db::plugin_update_db_check();
	}

}
