<?php
//include js
function tp_data_scripts(){
    //include teacher scripts
    wp_enqueue_script( 'teacherScripts',  plugins_url('js/teacherScripts.js', __FILE__) );
    //localize teacher scripts
    wp_localize_script( 'teacherScripts', 'teacherStrings', array(
        'deleteForbidden' => __( 'Delete is forbidden while completing the form!', 'UniTimetable' ),
        'deleteRecord' => __( 'Are you sure that you want to delete this record?', 'UniTimetable' ),
        'teacherDeleted' => __( 'Teacher deleted successfully!', 'UniTimetable' ),
        'teacherNotDeleted' => __( 'Failed to delete Teacher. Check if Teacher is connected with a Lecture.', 'UniTimetable' ),
        'editForbidden' => __( 'Edit is forbidden while completing the form!', 'UniTimetable' ),
        'editTeacher' => __( 'Edit Teacher', 'UniTimetable' ),
        'cancel' => __( 'Cancel', 'UniTimetable' ),
        'surnameVal' => __( 'Surname field is required. Please avoid using special characters.', 'UniTimetable' ),
        'nameVal' => __( 'Please avoid using special characters at Name field.', 'UniTimetable' ),
        'insertTeacher' => __( 'Insert Teacher', 'UniTimetable' ),
        'reset' => __( 'Reset', 'UniTimetable' ),
        'failAdd' => __( 'Failed to add Teacher. Check if the Teacher already exists.', 'UniTimetable' ),
        'successAdd' => __( 'Teacher successfully added!', 'UniTimetable' ),
        'failEdit' => __( 'Failed to edit Teacher. Check if the Teacher already exists.', 'UniTimetable' ),
        'successEdit' => __( 'Teacher successfully edited!', 'UniTimetable' ),
    ));
}

//teachers page
function tp_create_data_page(){
    //teachers form
?>
<div class="wrap" >
    <h2 id="dataTitle"><?php _e('Insert Data','tp_data'); ?></h2>
    <form action="#" name="dataForm" method="post">
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
        <input type="submit" value="<?php _e("Submit","tp_data"); ?>" id="insert-updateTeacher" class="button-primary"/>
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
    $teachersTable=$wpdb->prefix."tp_data";

    //show registered teachers
    $teachers = $wpdb->get_results("SELECT * FROM $teachersTable");
    ?>
        <!-- table with registered teachers -->
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
        foreach($teachers as $teacher){
            if($bgcolor == 1){
                $addClass = "class='grey'";
                $bgcolor = 2;
            }else{
                $addClass = "class='white'";
                $bgcolor = 1;
            }
            //a record
            echo "<tr id='$teacher->dataID' $addClass><td>$teacher->dataID</td><td>$teacher->userName</td><td>$teacher->userSemester</td><td><a href='#' onclick='deleteData($teacher->dataID);' class='deleteData'><img id='edit-delete-icon' src='".plugins_url('icons/delete_icon.png', __FILE__)."'/> ".__("Delete","UniTimetable")."</a>&nbsp; <a href='#' onclick=\"editData($teacher->dataID, '$teacher->userName', '$teacher->Semester');\" class='editData'><img id='edit-delete-icon' src='".plugins_url('icons/edit_icon.png', __FILE__)."'/> ".__("Edit","UniTimetable")."</a></td></tr>";
        }
        
        ?>
            </tbody>
        </table>
        <?php
        if($_POST["userName"])
            tp_insert_update_data();
        die();
}

//ajax response delete teacher
add_action('wp_ajax_tp_delete_data', 'tp_delete_data');
function tp_delete_data(){
    global $wpdb;
    $teachersTable=$wpdb->prefix."tp_data";
    $safeSql = $wpdb->prepare("DELETE FROM $teachersTable WHERE dataID= %d ", $_GET['data_id']);
    $success = $wpdb->query($safeSql);
    //if success is 1, delete succeeded
    echo $success;
    die();
}

//ajax response insert-update teacher
add_action('wp_ajax_tp_insert_update_data','tp_insert_update_data');
function tp_insert_update_data(){
    global $wpdb;
    //data
    $firstname=$_POST['userName'];
    $lastname=$_POST['semester'];
    $teacherid=$_POST['emailId'];
    $teachersTable=$wpdb->prefix."tp_data";
    $_POST["userName"] = "";
    $x = 0;
    //insert
    if($x==0){
        $safeSql = $wpdb->prepare("INSERT INTO $teachersTable (userName, userSemester, userEmail) VALUES (%s,%s, %s)",$firstname,$lastname,$teacherid);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
            tp_view_data();
            die();
        }else{
            //fail
            echo 0; 
        }
    //edit
    }else{
        $safeSql = $wpdb->prepare("UPDATE $teachersTable SET name=%s, surname=%s WHERE teacherID=%d; ",$firstname,$lastname,$teacherid);
        $success = $wpdb->query($safeSql);
        if($success == 1){
            //success
            echo 1;
        }else{
            //fail
            echo 0;
        }
    }
die();
}

?>