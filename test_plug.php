<?php
/*
Plugin Name: test_plug
Plugin URI: 
Description: Best New Plugin
Version: 1.1
Author: Tushar Agey
License: GPLv3
*/
//register utt_activate function to run when user activates the plugin
register_activation_hook( __FILE__, 'tp_activate' );
//create tables and view for tp_data plugin to the Wordpress Database
function tp_activate(){
    //require upgrade.php so that we can use dbDelta function
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    //set table names
    $periodsTable=$wpdb->prefix."tp_data";
    $charset_collate = $wpdb->get_charset_collate();
    
    //create utt tables
    $sql = "CREATE TABLE IF NOT EXISTS `$periodsTable`(
    		dataID INT NOT NULL AUTO_INCREMENT,
    		userName VARCHAR(200) NOT NULL,
    		userSemester VARCHAR(20) NOT NULL,
    		userEmail VARCHAR(200) NOT NULL,
    		PRIMARY KEY (`dataID`))
    		ENGINE = InnoDB
            $charset_collate;";
    dbDelta($sql);
}

//register utt_tp_dataMenu_create
add_action('admin_menu','utt_tp_dataMenu_create');
//Create Menu-Submenus
function utt_tp_dataMenu_create(){
	//load utt_style.css on every plugin page
    wp_enqueue_style( 'utt_style',  plugins_url('style.css', __FILE__) );
 
	//add main page of plugin
    add_menu_page('tp_data','tp_data','manage_options',__FILE__,'tp_data_page' );
    
    //add submenu pages to tp_data menu
  	$loginPage = add_submenu_page( __FILE__, __("Login","tp_data"), __("Login","tp_data"), 'manage_options',__FILE__.'_login', 'tp_create_data_page' );
   	add_action('load-'.$loginPage, 'tp_data_scripts');
   }

//load main utt page
function tp_data_page(){
    ?>
    <div class="wrap">
        <h2><?php _e("Main Page of tp_data Plugin","tp_data"); ?></h2>
        <h3><?php _e("About","tp_data"); ?></h3>
        <p>
            <?php _e("<strong>tp_data</strong> is a Wordpress plugin for testing purpose. It simply creates a table when activated and then provides a login screen. If credentials are correct, user gets to add data into the table and see the data."); ?>
        </p>
    </div>
    <?php
}
require('teachersFunctions.php');
?>
