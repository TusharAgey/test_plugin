<?php
/*
Plugin Name: test_plug
Plugin URI: 
Description: Best New Plugin
Version: 1.1
Author: Tushar Agey
Author URI: https://www.linkedin.com/pub/antonis-roussos/47/25b/9a5
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
  //  wp_enqueue_style( 'utt_style',  plugins_url('style.css', __FILE__) );
    //add main page of plugin
    add_menu_page('tp_data','tp_data','manage_options',__FILE__,'utt_tp_data_page' );
    
    //add submenu pages to tp_data menu
  //  $teachersPage = add_submenu_page( __FILE__, __("Insert Teacher","tp_data"), __("Teachers","tp_data"), 'manage_options',__FILE__.'_teachers', 'utt_create_teachers_page' );
   // add_action('load-'.$teachersPage, 'utt_teacher_scripts');
   }

//load main utt page
function utt_tp_data_page(){
    global $wpdb;
    //set table names
   // $periodsTable=$wpdb->prefix."tp_periods";
    ?>
    <div class="wrap">
        <h2><?php _e("Main Page of tp_data Plugin","tp_data"); ?></h2>
        <h3><?php _e("About","tp_data"); ?></h3>
        <p>
            <?php _e("<strong>tp_data</strong> is a WordPress plugin for presenting timetables of an educational institute. It includes teachers, classrooms, subjects (modules) and student groups, which are all combined to define lectures. The lectures can be scheduled at some time point during a semester. Out of schedule events and holidays are also supported. After providing the plugin with data, shortcodes provided (see below) generate beautiful calendars with all or selected part of the entered data. <strong>tp_data</strong> was designed by <a href='https://www.researchgate.net/profile/Fotis_Kokkoras'>Fotis Kokkoras</a> and <a href='https://www.linkedin.com/pub/antonis-roussos/47/25b/9a5'>Antonis Roussos</a> and implemented by <a href='https://www.linkedin.com/pub/antonis-roussos/47/25b/9a5'>Antonis Roussos</a> for the fulfillment of his BSc Thesis in the <a href'http://www.cs.teilar.gr/CS/Home.jsp'>Department of Computer Science and Engineering (TEI of Thessaly, Greece)</a>.","tp_data"); ?>
        </p>
        <h3><?php _e("How to use the Shortcodes","tp_data"); ?></h3>
            <?php _e("<p>The general purpose shortcode is <strong>[utt_calendar]</strong> and should be better placed in a page (or post) with substantial width. The resulting calendar includes two filter combo-boxes for selecting individual calendars for any of the semester, classroom, and teacher.</p><p>In case that a fixed calendar is required, parameters can be added to precisely define the content to be displayed. More specifically:</p><ul class='bullets'><li>[utt_calendar classroom = &lt;comma separated classroomID list&gt;]   (examples: [utt_calendar classroom=1], [utt_calendar classroom=1,2,3])</li><li>[utt_calendar teacher = &lt;comma separated teacherID list&gt;]</li><li>[utt_calendar semester = &lt;comma separated semester list&gt;]</li></ul><p>Note that the filtered shortcodes generate a calendar without the usual filter combo-boxes.</p>","tp_data"); ?>
        <h3><?php
        //show database records
        _e("Database Records","tp_data"); ?></h3>
        <?php //$teachers = $wpdb->get_row("SELECT count(*) as counter FROM $teachersTable;") ?>
    </div>
    <?php
}
?>