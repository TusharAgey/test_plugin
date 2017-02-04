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
  	$loginPage = add_submenu_page( __FILE__, __("Login","tp_data"), __("Login","tp_data"), 'manage_options',__FILE__.'_login', 'tp_data_login' );
   	add_action('load-'.$loginPage, 'tp_data_login');
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


function tp_data_login(){
    //teachers form
?>
<div class="wrap" >
    <h2 id="dataTitle"><?php _e('Insert Data','tp_data'); ?></h2>
    <form action="" name="dataForm" method="post">
        <input type="hidden" name="dataid" id="dataid" value=0 />
        <?php _e("Name:","tp_data"); ?><br/>
        <input type="text" name="userName" id="userName" class="dirty" required placeholder="<?php _e("Required","tp_data"); ?>"/>
        <br/>
        <?php _e("Semester:","tp_data"); ?><br/>
        <input type="text" name="semester" id="semester" class="dirty"/>
        <br/>
        <?php _e("email:","tp_data"); ?><br/>
        <input type="text" name="emailId" id="emailId" class="dirty"/>
        <br/>
        <div id="secondaryButtonContainer">
        <input type="submit" value="<?php _e("Submit","tp_data"); ?>" id="insert-updateData" class="button-primary"/>
        <a href='#' class='button-secondary' id="clearDataForm"><?php _e("Reset","tp_data"); ?></a>
        </div>
    </form>
    <!-- place to view messages -->
    <div id="messages"></div>
    <!-- place to view table with inserted data -->
    <div id="dataResults">
        <?php tp_view_data(); ?>
    </div>
</div>

<?php
}

add_action('wp_ajax_tp_view_data', 'tp_view_data');
function tp_view_data(){
    global $wpdb;
    $tpTable=$wpdb->prefix."tp_data";
        
    //show inserted data
    $tp = $wpdb->get_results("SELECT * FROM $tpTable");
    ?>
        <!-- table with inserted data -->
        <table class="widefat bold-th">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php _e("Name","UniTimetable"); ?></th>
                    <th><?php _e("Semester","UniTimetable"); ?></th>
                    <th><?php _e("Email Id","UniTimetable"); ?></th>
                </tr>
            </thead>
            <tbody>
        <?php
        //show grey and white records in order to be more recognizable
        $bgcolor = 1;
        foreach($tp as $tps){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            //a record
            echo "<tr id='$tps->dataID' $addClass><td>$tps->dataID</td><td>$tps->userName</td><td>$tps->userSemester</td><td><a href='#' onclick='deleteTeacher($teacher->dataID);' class='deleteTeacher'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Delete","UniTimetable")."</a>&nbsp; <a href='#' onclick=\"editTeacher($tps->dataID, '$tps->userName', '$tps->userSemester');\" class='editTeacher'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Edit","UniTimetable")."</a></td></tr>";
        }
        
        ?>
            </tbody>
        </table>
        <?php
        die();
}
