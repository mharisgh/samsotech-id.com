<?php
//
//exit();
include './connections.php';
include './encryption_dynamicyards_unknowncode.php';
session_start();

###### Form Submit vlue assign to index#######
if ($_POST['INDEX'] != '') {
    $index = $_POST['INDEX'];
}
else if ($_GET['G_IND'] != '') {
    $index = $_GET['G_IND'];
}
//echo $index;
//exit();
###### Form Submit vlue assign to index#######


if ($index == 'login') {
    $uname = addslashes($_POST['uname']);
    $pswd = addslashes($_POST['pswd']);
    $pass = Encrypt_dynamicyards($pswd);
    $result = login("tbl_users", "Username", $uname, "Password", $pass, "Status", 1);

    if ($result->num_rows > 0) {
        $_SESSION["uname"] = $uname;
        while ($row = $result->fetch_assoc()) {
            $_SESSION["utype"] = $row['Usertype'];
            $_SESSION["UID"] = $row['user_id'];
        }
        header("Location:../dashboard.php");
    }
    else {
        $_SESSION["err"] = "Username or password incorrect ";
        header("Location:../index.php");
    }
}
if ($index == 'change_password') {
    $newpswd = addslashes($_POST['newpswd']);
    $curpswd = addslashes($_POST['curpswd']);
    $curold = addslashes($_POST['oldpswd']);
    $cpswd = addslashes($_POST['cpswd']);
    if (($newpswd == $cpswd) && ($curpswd == $curold)) {
        $query = "UPDATE `tbl_users` SET `Password` = '" . Encrypt_dynamicyards($newpswd) . "'where `user_id` = '1' ";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "Password Updated Successfully";
            header("Location:../change_password.php");
        }
        else {
            $_SESSION["err"] = "Error updating password";
            header("Location:../change_password.php");
        }
    }
    else {
        $_SESSION["err"] = "Password mismatch";
        header("Location:../change_password.php");
    }
}

if ($index == 'add-usertype') {

    $usertype = $_POST['usertype'];
    $cms = $ads = $cate_tags = $business = $settings = 'NO';
    foreach ($_POST['priv'] as $priv) {
        switch ($priv) {
            case 'cms':$cms = 'YES';
                break;

            case 'finance':
                $finance = 'YES';
                break;
            case 'settings':
                $settings = 'YES';
                break;
            default:
                break;
        }
    }
    $UEX = get_where_cond("tbl_usertype", "usertype='" . $usertype . "'");
    if ($UEX->num_rows > 0) {
        $_SESSION["err"] = 'User type Already exists';

        header("Location:../view_usertype.php");
    }
    else {
        $query = "INSERT INTO `tbl_usertype`(`usertype`, `cms`, `finance`, `settings`) VALUES ('" . $usertype . "','" . $cms . "','" . $finance . "','" . $settings . "')";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "User Type Added Successfully ";
            $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../view_usertype.php");
        }
        else {
            $_SESSION["err"] = 'Error adding usertype';
            header("Location:../view_usertype.php");
        }
    }
}

if ($index == 'edit-usertype') {
    $id = $_POST['utid'];
    $cms = $ads = $cate_tags = $business = $settings = 'NO';
    foreach ($_POST['priv'] as $priv) {
        switch ($priv) {
            case 'cms':$cms = 'YES';
                break;
            case 'finance':
                $finance = 'YES';
                break;
            case 'settings':
                $settings = 'YES';
                break;
            default:
                break;
        }
    }
    $query = "Update`tbl_usertype` set  `cms`='" . $cms . "', `finance`='" . $finance . "', `settings`='" . $settings . "' where `id`=" . $id;
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Privileges Updated Successfully ";
        $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../user-role.php");
    }
    else {
        $_SESSION["err"] = 'Error updating Privileges';
        header("Location:../user-role.php");
    }
}

if ($index == 'add_user') {
    $name = addslashes($_POST['name']);
    $email = addslashes($_POST['email']);
    $phno = addslashes($_POST['mob']);
    $type = addslashes($_POST['usertype']);
    $pswdo = addslashes($_POST['pswd']);
    $cpswd = addslashes($_POST['cpswd']);
    if ($pswdo == $cpswd) {
        $pswd = Encrypt_dynamicyards($pswdo);
        $result = get_where_cond("tbl_users", "Username='" . $email . "'");
        if ($result->num_rows > 0) {
            $_SESSION["err"] = "User Already Exist,Please try login";
            echo $_SESSION["err"];
            header("Location:../add_user.php");
        }
        else {
            echo $query = "INSERT INTO `tbl_users`(`Username`, `Password`, `Usertype`, `Status`, `Name`, `Phone`, `email`) VALUES( '" . $email . "','" . $pswd . "','" . $type . "',0,'" . $name . "','" . $phno . "','" . $email . "');";
            $result = execute($query);

            if ($result == 'Query Executed Successfully') {
                $_SESSION["err"] = "User added successfully ";
                $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
                $res_preview = execute($preview);
                header("Location:../users.php");
            }
        }
    }
    else {
        $_SESSION["err"] = "Password does not match";
        echo $_SESSION["err"];
        header("Location:../users.php");
    }
}

if ($index == 'delete_user') {
    $user_id = addslashes($_POST['user_id']);
    $query = "Delete from `tbl_users` where user_id=" . $user_id;
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "User Deleted Successfully";
        $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../users.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting ";
        header("Location:../users.php");
    }
}
if ($index == 'approve_user') {
    $user_id = addslashes($_POST['user_id']);
    $query = "UPDATE `tbl_users` SET `Status`=1 where user_id=" . $user_id;
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "User Approved Successfully";
        $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../users.php");
    }
    else {
        $_SESSION["err"] = "Error Approving ";
        header("Location:../users.php");
    }
}
if ($index == 'disapprove_user') {
    $user_id = addslashes($_POST['user_id']);
    $query = "UPDATE `tbl_users` SET `Status`=0 where user_id=" . $user_id;
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "User Disapproved Successfully";
        $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../users.php");
    }
    else {
        $_SESSION["err"] = "Error Disapproving ";
        header("Location:../users.php");
    }
}

if ($index == 'Website Title') {
    if (trim($_POST['description']) == '') {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../appearance.php");
    }
    else {

        $title = $_POST['description'];
        $query = "Update tbl_appearance set description='" . $title . "' where title='" . $index . "'";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../appearance.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../appearance.php");
        }
    }
}

if ($index == 'Logo') {
    $target_dir = "../uploads/";
    $filnm = rand(1, 100000);
    $file_name = basename($_FILES["file"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $target_file12 = $target_dir . basename($_FILES["file"]["name"]);
    $ext1 = pathinfo($target_file12);
    $ext = $ext1['extension'];
    if ($file_name != null) {
        $target_file = $target_dir . $filnm . $file_name;
    }
    else {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
    }
    $target_file = $target_dir . $filnm . basename($_FILES["file"]["name"]);

    if (($ext == 'jpeg') || ($ext == 'png') || ($ext == 'gif') || ($ext == 'bmp') || ($ext == 'jpg') || ($ext == 'tiff') || ($ext == '')) {
        move_uploaded_file($_FILES['file']["tmp_name"], $target_file);
        if ($file_name == '') {
            $query = "";
        }
        else {
            $target_file = substr($target_file, 3);
            $query = "Update tbl_appearance set description='" . $target_file . "' where title='" . $index . "'";
        }
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../appearance.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../appearance.php");
        }
    }
    else {
        $_SESSION["err"] = "Only image files are accepted";
        header("Location:../appearance.php");
    }
}

if ($index == 'Favicon') {

    $target_dir = "../../";
//$filnm = rand(1, 100000);
    $file_name = basename($_FILES["file"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $target_file12 = $target_dir . basename($_FILES["file"]["name"]);
    $ext1 = pathinfo($target_file12);
    $ext = $ext1['extension'];
    if ($file_name != null) {
        $target_file = $target_dir . $file_name;
    }
    else {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
    }
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (($ext == 'jpeg') || ($ext == 'png') || ($ext == 'gif') || ($ext == 'bmp') || ($ext == 'jpg') || ($ext == 'ico') || ($ext == '')) {
        move_uploaded_file($_FILES['file']["tmp_name"], $target_file);
        if ($file_name == '') {
            $query = "";
        }
        else {
            $target_file = substr($target_file, 3);
            $query = "Update tbl_appearance set description='" . $target_file . "' where title='" . $index . "'";
        }

        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../appearance.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../appearance.php");
        }
    }
    else {
        $_SESSION["err"] = "Only image files are accepted";
        header("Location:../appearance.php");
    }
}

if ($index == 'Footer Logo') {
    $target_dir = "../uploads/";
    $filnm = rand(1, 100000);
    $file_name = basename($_FILES["file"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $target_file12 = $target_dir . basename($_FILES["file"]["name"]);
    $ext1 = pathinfo($target_file12);
    $ext = $ext1['extension'];
    if ($file_name != null) {
        $target_file = $target_dir . $file_name;
    }
    else {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
    }
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (($ext == 'jpeg') || ($ext == 'png') || ($ext == 'gif') || ($ext == 'bmp') || ($ext == 'jpg') || ($ext == 'tiff') || ($ext == 'ico') || ($ext == '')) {
        move_uploaded_file($_FILES['file']["tmp_name"], $target_file);
        if ($file_name == '') {
            $query = "";
        }
        else {
            $target_file = substr($target_file, 3);
            $query = "Update tbl_appearance set description='" . $target_file . "' where title='" . $index . "'";
        }
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set  `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../appearance.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../appearance.php");
        }
    }
    else {
        $_SESSION["err"] = "Only image files are accepted";
        header("Location:../appearance.php");
    }
}

if ($index == 'Copyright') {
    if (trim($_POST['description']) == '') {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../appearance.php");
    }
    else {
        $title = $_POST['description'];
        $query = "Update tbl_appearance set description='" . $title . "' where title='" . $index . "'";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../appearance.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../appearance.php");
        }//
    }
}

if ($index == 'social_icon_update') {

    //exit();
    $soc_id = addslashes($_POST['soc_id']);
    $soc_link = addslashes($_POST['link']);
    if (trim($_POST['link']) == '') {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../appearance.php");
    }
    else {
        $query = "UPDATE `tbl_social` set soc_link = '" . $soc_link . "' where `soc_id` = '" . $soc_id . "'";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "Social Link Updated Successfully";
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../appearance.php");
        }
        else {
            $_SESSION["err"] = "Error Updating Social Link";
            header("Location:../appearance.php");
        }
    }
}



##################################################################### SLIDER ################################################
############################## ADD SLIDER #############################
if ($index == 'add_slider') {

//    exit();

    $main_title = $_POST['main_title'];
    $sub_title = $_POST['sub_title'];
    $main_title_ar = $_POST['main_title_ar'];
    $sub_title_ar = $_POST['sub_title_ar'];
    $slider_link = $_POST['slider_link'];
    $slider_link_ar = $_POST['slider_link_ar'];
    $position = $_POST['position'];

    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['file']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $query = "INSERT INTO `tbl_slider`(`main_title`, `main_title_ar`, `sub_title`, `sub_title_ar`,`slider_link`,`slider_link_ar`,`position`, `image`) VALUES ('" . $main_title . "', '" . $main_title_ar . "', '" . $sub_title . "', '" . $sub_title_ar . "', '" . $slider_link . "', '" . $slider_link_ar . "','" . $position . "', '" . $subfile . "')";
    echo $query;

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Slider Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../slider.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Slider";
        header("Location:../slider.php");
    }
}
if ($index == 'add_product_slider') {

    $product_id = $_POST['product_id'];
    $main_title = $_POST['main_title'];
    $sub_title = $_POST['sub_title'];
    $main_title_ar = $_POST['main_title_ar'];
    $sub_title_ar = $_POST['sub_title_ar'];
    $position = $_POST['position'];

    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['file']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $query = "INSERT INTO `tbl_product_slider`(`product_id`,`main_title`, `main_title_ar`, `sub_title`, `sub_title_ar`,`position`, `image`) VALUES ('" . $product_id . "','" . $main_title . "', '" . $main_title_ar . "', '" . $sub_title . "', '" . $sub_title_ar . "','" . $position . "', '" . $subfile . "')";
    echo $query;

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Slider Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Slider";
        header("Location:../products.php");
    }
}
############################## ADD SLIDER END #############################
############################## EDIT SLIDER #############################
if ($index == 'edit_slider') {
//

    $main_title = $_POST['main_title'];
    $sub_title = $_POST['sub_title'];
    $id = $_POST['slider_id'];
    $main_title_ar = $_POST['main_title_ar'];
    $sub_title_ar = $_POST['sub_title_ar'];
    $slider_link = $_POST['slider_link'];
    $slider_link_ar = $_POST['slider_link_ar'];
    $position = $_POST['position'];
    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $file_name;
    if ($file_name != '') {
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);
        $query = "UPDATE `tbl_slider` set main_title = '" . $main_title . "',`main_title_ar`='" . $main_title_ar . "', sub_title = '" . $sub_title . "',`sub_title_ar`='" . $sub_title_ar . "',`slider_link`='" . $slider_link . "',`slider_link_ar`='" . $slider_link_ar . "', image = '" . $subfile . "',`position`='" . $position . "' where `id` = '" . $id . "'";
    }
    else {
        $query = "UPDATE `tbl_slider` set main_title = '" . $main_title . "',`main_title_ar`='" . $main_title_ar . "', sub_title = '" . $sub_title . "',`sub_title_ar`='" . $sub_title_ar . "',`slider_link`='" . $slider_link . "',`slider_link_ar`='" . $slider_link_ar . "',`position`='" . $position . "' where `id` = '" . $id . "'";
    }

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Slider Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../slider.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../slider.php");
    }
}
if ($index == 'edit_product_slider') {
//

    $main_title = $_POST['main_title'];
    $sub_title = $_POST['sub_title'];
    $id = $_POST['slider_id'];
    $product_id = $_POST['product_id'];
    $main_title_ar = $_POST['main_title_ar'];
    $sub_title_ar = $_POST['sub_title_ar'];
    $position = $_POST['position'];


    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["file"]["name"]);

    if ($file_name != '') {
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);
        $query = "UPDATE `tbl_product_slider` set main_title = '" . $main_title . "',`main_title_ar`='" . $main_title_ar . "', sub_title = '" . $sub_title . "',`sub_title_ar`='" . $sub_title_ar . "', image = '" . $subfile . "',`position`='" . $position . "' where `id` = '" . $id . "' and product_id='" . $product_id . "'";
    }
    else {
        $query = "UPDATE `tbl_product_slider` set main_title = '" . $main_title . "',`main_title_ar`='" . $main_title_ar . "', sub_title = '" . $sub_title . "',`sub_title_ar`='" . $sub_title_ar . "',`position`='" . $position . "' where `id` = '" . $id . "'and product_id='" . $product_id . "'";
    }

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Slider Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../products.php");
    }
}
############################## EDIT SLIDER END #############################
############################### DELETE SLIDER #############################
if ($index == 'delete_slider') {

//exit();

    $id = $_POST['slider_id'];

    $query = "DELETE from `tbl_slider`  where `id` = '" . $id . "'";


    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Slider Deleted Successfully";
        header("Location:../slider.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Slider";
        header("Location:../slider.php");
    }
}
if ($index == 'delete_product_slider') {
//
    $id = $_POST['slider_id'];
    $product_id = $_POST['product_id'];
    $query = "DELETE from `tbl_product_slider`  where `id` = '" . $id . "' and product_id='" . $product_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Slider Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../products.php");
    }
}
############################## DELETE SLIDER END #############################
##################################################################### SLODER END################################################
#
###################################################################### ABOUT US ################################################
############################## ABOUT PAGES #############################
if ($index == 'visionmission_image') {
    $page_id = $_POST['page_id'];
    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["bg_image"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["bg_image"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['bg_image']["tmp_name"], $file);
    $subfile_content = substr($file, 3);
    if ($file_name != '') {
        $query = "Update tbl_cms set  about_description='" . $subfile_content . "' where id='" . $page_id . "'";
    }
    else {
        $query = "";
    }
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../about-us.php?abid=1");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../about-us.php?abid=1");
    }
}
if ($index == 'about_update') {

//
    $lang = $_POST['lang'];
    $page_id = $_POST['page_id'];
    if (($page_id == 2) || ($page_id == 3) || ($page_id == 4)) {

        if ($lang == 'en') {


//            exit();
            if ((trim($_POST['about_title']) == '') || (trim($_POST['about_description']) == '')) {
                echo 'hi';
//                exit();
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {

                $page_title = addslashes($_POST['about_title']);
                $page_content = addslashes($_POST['about_description']);

                $query = "Update tbl_cms set about_title='" . $page_title . "', about_description='" . $page_content . "' where id='" . $page_id . "'";
            }
        }
        else if ($lang == 'ar') {
            if ((trim($_POST['about_title_ar']) == '') || (trim($_POST['about_description_ar']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);
                $page_content = addslashes($_POST['about_description_ar']);

                $query = "Update tbl_cms set about_title_ar='" . $page_title . "', about_description_ar='" . $page_content . "' where id='" . $page_id . "'";
            }
        }
    }
    else if (($page_id == 1) || ($page_id == 11) || ($page_id == 14) || ($page_id == 15)) {
        $filnm = rand(4500, 8000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["content_image"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $image = $target_dir . basename($_FILES["content_image"]["name"]);
        $ext1 = pathinfo($image);
        $ext = $ext1['extension'];
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['content_image']["tmp_name"], $file);
        $subfile_content = substr($file, 3);


        $target_dir = "../uploads/";
        $file_name_ar = basename($_FILES["content_image_ar"]["name"]);
        $file_name_ar = str_replace(" ", "-", $file_name_ar);
        $image_ar = $target_dir . basename($_FILES["content_image_ar"]["name"]);
        $ext1 = pathinfo($image_ar);
        $ext = $ext1['extension'];
        $file_ar = $target_dir . $filnm . $file_name_ar;
        move_uploaded_file($_FILES['content_image_ar']["tmp_name"], $file_ar);
        $subfile_content_ar = substr($file_ar, 3);

        if ($lang == 'en') {

            if ((trim($_POST['about_title']) == '') || (trim($_POST['about_description']) == '')) {

                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {

                $page_title = addslashes($_POST['about_title']);
                $page_content = addslashes($_POST['about_description']);
                $excerpt = addslashes($_POST['about_excerpt']);
                if ($file_name == '') {
                    $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $page_content . "', about_excerpt='" . $excerpt . "' where about_id='" . $page_id . "'";
                }
                else {

                    $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $page_content . "', about_excerpt='" . $excerpt . "',`content_image`='" . $subfile_content . "' where about_id='" . $page_id . "'";
                }
            }
        }
        else if ($lang == 'ar') {
            if ((trim($_POST['about_title_ar']) == '') || (trim($_POST['about_description_ar']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);
                $page_content = addslashes($_POST['about_description_ar']);
                $excerpt = addslashes($_POST['about_excerpt_ar']);
                if ($file_name_ar == '') {
                    $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $page_content . "', about_excerpt_ar='" . $excerpt . "' where about_id='" . $page_id . "'";
                }
                else {
                    $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $page_content . "', about_excerpt_ar='" . $excerpt . "',`content_image_ar`='" . $subfile_content_ar . "' where about_id='" . $page_id . "'";
                }
            }
        }
    }
    else if ($page_id == 12) {
        $filnm = rand(4500, 8000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["bg_image"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $image = $target_dir . basename($_FILES["bg_image"]["name"]);
        $ext1 = pathinfo($image);
        $ext = $ext1['extension'];
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['bg_image']["tmp_name"], $file);
        $subfile_bg = substr($file, 3);


        $target_dir = "../uploads/";
        $file_name_ar = basename($_FILES["bg_image_ar"]["name"]);
        $file_name_ar = str_replace(" ", "-", $file_name_ar);
        $image_ar = $target_dir . basename($_FILES["bg_image_ar"]["name"]);
        $ext1 = pathinfo($image_ar);
        $ext = $ext1['extension'];
        $file_ar = $target_dir . $filnm . $file_name_ar;
        move_uploaded_file($_FILES['bg_image_ar']["tmp_name"], $file_ar);
        $subfile_bg_ar = substr($file_ar, 3);

        if ($lang == 'en') {
            if (trim($_POST['about_title']) == '') {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title']);

                if ($file_name == '') {
                    $query = "Update tbl_about set about_title='" . $page_title . "' where about_id='" . $page_id . "'";
                }
                else {

                    $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $subfile_bg . "' where about_id='" . $page_id . "'";
                }
            }
        }
        else if ($lang == 'ar') {
            if (trim($_POST['about_title_ar']) == '') {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);

                if ($file_name_ar == '') {
                    $query = "Update tbl_about set about_title_ar='" . $page_title . "' where about_id='" . $page_id . "'";
                }
                else {
                    $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $subfile_bg_ar . "' where about_id='" . $page_id . "'";
                }
            }
        }
    }
    else if ($page_id == 5) {

        $filnm = rand(4500, 8000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["content_image"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $image = $target_dir . basename($_FILES["content_image"]["name"]);
        $ext1 = pathinfo($image);
        $ext = $ext1['extension'];
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['content_image']["tmp_name"], $file);
        $subfile_bg = substr($file, 3);


        $target_dir = "../uploads/";
        $file_name_ar = basename($_FILES["content_image_ar"]["name"]);
        $file_name_ar = str_replace(" ", "-", $file_name_ar);
        $image_ar = $target_dir . basename($_FILES["content_image_ar"]["name"]);
        $ext1 = pathinfo($image_ar);
        $ext = $ext1['extension'];
        $file_ar = $target_dir . $filnm . $file_name_ar;
        move_uploaded_file($_FILES['content_image_ar']["tmp_name"], $file_ar);
        $subfile_bg_ar = substr($file_ar, 3);

        if ($lang == 'en') {
            if ((trim($_POST['about_title']) == '') || (trim($_POST['about_description']) == '') || (trim($_POST['about_excerpt']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
//                echo 'hi';
//              exit();
                $page_title = addslashes($_POST['about_title']);
                $page_content = addslashes($_POST['about_description']);
                parse_str(parse_url($page_content, PHP_URL_QUERY), $vid);
                echo $url = $vid['v'];
//                exit();
                $excerpt = addslashes($_POST['about_excerpt']);
                if ($file_name == '') {
                    $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $url . "', about_excerpt='" . $excerpt . "' where about_id='" . $page_id . "'";
                }
                else {
                    $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $url . "', about_excerpt='" . $excerpt . "',content_image='" . $subfile_bg . "' where about_id='" . $page_id . "'";
                }
            }
//                echo $query;
//
//                 exit();
        }
        else if ($lang == 'ar') {
            if ((trim($_POST['about_title_ar']) == '') || (trim($_POST['about_description_ar']) == '') || (trim($_POST['about_excerpt_ar']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);
                $page_content = addslashes($_POST['about_description_ar']);
                parse_str(parse_url($page_content, PHP_URL_QUERY), $vid_ar);
                $url_ar = $vid_ar['v'];
                $excerpt = addslashes($_POST['about_excerpt_ar']);
                if ($file_name_ar == '') {
                    $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $url_ar . "', about_excerpt_ar='" . $excerpt . "' where about_id='" . $page_id . "'";
                }
                else {
                    $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $url_ar . "', about_excerpt_ar='" . $excerpt . "',content_image_ar='" . $subfile_bg_ar . "' where about_id='" . $page_id . "'";
                }
            }
        }
    }
    else if (($page_id == 6) || ($page_id == 8) || ($page_id == 8) || ($page_id == 7) || ($page_id == 19) || ($page_id == 21)) {
        if ($lang == 'en') {
            if ((trim($_POST['about_title']) == '') || (trim($_POST['about_description']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {

                $page_title = addslashes($_POST['about_title']);
                $page_content = addslashes($_POST['about_description']);

                $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $page_content . "' where about_id='" . $page_id . "'";
            }
        }
        else if ($lang == 'ar') {
            if ((trim($_POST['about_title_ar']) == '') || (trim($_POST['about_description_ar']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);
                $page_content = addslashes($_POST['about_description_ar']);

                $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $page_content . "' where about_id='" . $page_id . "'";
            }
        }
        $query;
//        }
    }
    else if ($page_id == 22) {
        if ($lang == 'en') {
            if ((trim($_POST['about_title']) == '') || (trim($_POST['about_description']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                //header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title']);
                $page_content = addslashes($_POST['about_description']);
                echo $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $page_content . "' where about_id='" . $page_id . "'";
            }
        }
        else if ($lang == 'ar') {
            if ((trim($_POST['about_title_ar']) == '') || (trim($_POST['about_description_ar']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                //header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);
                $page_content = addslashes($_POST['about_description_ar']);
                $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $page_content . "' where about_id='" . $page_id . "'";
            }
        }
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../about-us.php?abid=" . $page_id . "");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../about-us.php?abid=" . $page_id . "");
        }
    }
    else {
        if ($lang == 'en') {
            if ((trim($_POST['about_title']) == '') || (trim($_POST['about_description']) == '') || (trim($_POST['about_excerpt']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                //header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title']);
                $page_content = addslashes($_POST['about_description']);
                $excerpt = addslashes($_POST['about_excerpt']);
                $query = "Update tbl_about set about_title='" . $page_title . "', about_description='" . $page_content . "', about_excerpt='" . $excerpt . "' where about_id='" . $page_id . "'";
            }
        }
        else if ($lang == 'ar') {
            if ((trim($_POST['about_title_ar']) == '') || (trim($_POST['about_description_ar']) == '') || (trim($_POST['about_excerpt_ar']) == '')) {
                $_SESSION["err"] = "Enter Valid Details";
                //header("Location:../about-us.php?abid=" . $page_id . "");
            }
            else {
                $page_title = addslashes($_POST['about_title_ar']);
                $page_content = addslashes($_POST['about_description_ar']);
                $excerpt = addslashes($_POST['about_excerpt_ar']);
                $query = "Update tbl_about set about_title_ar='" . $page_title . "', about_description_ar='" . $page_content . "', about_excerpt_ar='" . $excerpt . "' where about_id='" . $page_id . "'";
            }
        }
        //echo $query;
//        }
    }
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        header("Location:../about-us.php?abid=" . $page_id . "");
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../about-us.php?abid=" . $page_id . "");
    }
}
if ($index == 'about_update_header') {
    $page_id = $_POST['page_id'];
    $filnm = rand(8500, 12000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["header_image"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["header_image"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['header_image']["tmp_name"], $file);
    $subfile_header = substr($file, 3);

//            $page_title = addslashes($_POST['about_title']);
//            $page_content = addslashes($_POST['about_description']);
//            $excerpt = addslashes($_POST['about_excerpt']);
    if ($file_name != '') {
        $query = "Update tbl_about set `header_image`='" . $subfile_header . "' where about_id='" . $page_id . "'";
    }
    else {
        $query = "";
    }
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../about-us.php?abid=" . $page_id . "");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../about-us.php?abid=" . $page_id . "");
    }
}
############################## ABOUT PAGES END#############################
############################## ADD AWARDS #############################
if ($index == 'add_awards') {
//
    if ((trim($_POST['award_title']) == '') || (trim($_POST['award_title_ar']) == '')) {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../awards.php");
    }
    else {
        $award_title = addslashes($_POST['award_title']);
        $award_title_ar = addslashes($_POST['award_title_ar']);
        $filnm = rand(250000, 500000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);
        $query = "INSERT INTO `tbl_awards`(`aw_image`, `aw_description`, `aw_description_ar`) VALUES ('" . $subfile . "', '" . $award_title . "', '" . $award_title_ar . "')";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../awards.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../awards.php");
        }
    }
}
############################## ADD AWARDS END #############################
#
############################### EDIT AWARDS #############################
if ($index == 'edit_awards') {
//
    $aw_id = $_POST['award_id'];
    if ((trim($_POST['award_title']) == '') || (trim($_POST['award_title_ar']) == '')) {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../awards.php");
    }
    else {
        $award_title = addslashes($_POST['award_title']);
        $award_title_ar = addslashes($_POST['award_title_ar']);

        $filnm = rand(250000, 500000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);
        if ($file_name != '') {
            $query = "Update tbl_awards set aw_image='" . $subfile . "', aw_description='" . $award_title . "', aw_description_ar='" . $award_title_ar . "' where aw_id='" . $aw_id . "'";
        }
        else {
            $query = "Update tbl_awards set aw_description='" . $award_title . "', aw_description_ar='" . $award_title_ar . "' where aw_id='" . $aw_id . "'";
        }
        echo $query;
//exit();
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../awards.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../awards.php");
        }
    }
}
################################ EDIT AWARDS END #############################
#
################################# WHY CHOOSE US ADD ############################
if ($index == 'add_wcs_points') {

    if ((trim($_POST['wcs_title']) == '') || (trim($_POST['wcs_title_ar']) == '') || (trim($_POST['wcs_description']) == '') || (trim($_POST['wcs_description_ar']) == '')) {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../wcs-points.php");
    }
    else {
        $wcs_title = addslashes($_POST['wcs_title']);
        $wcs_title_ar = addslashes($_POST['wcs_title_ar']);
        $wcs_description = addslashes($_POST['wcs_description']);
        $wcs_description_ar = addslashes($_POST['wcs_description_ar']);

        $filnm = rand(250000, 500000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);

        $query = "INSERT INTO `tbl_wcs_points`( `wcs_title`, `wcs_title_ar`, `wcs_description`, `wcs_description_ar`,`image`) VALUES ('" . $wcs_title . "', '" . $wcs_title_ar . "', '" . $wcs_description . "','" . $wcs_description_ar . "','" . $subfile . "')";
//exit();
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../wcs-points.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../wcs-points.php");
        }
    }
}

################################## WHY CHOOSE US Add END ############################
#
################################## WHY CHOOSE US EDIT ############################
if ($index == 'edit_wcs_points') {

    $wcs_id = $_POST['wcs_points_id'];
    if ((trim($_POST['wcs_title']) == '') || (trim($_POST['wcs_title_ar']) == '') || (trim($_POST['wcs_description']) == '') || (trim($_POST['wcs_description_ar']) == '')) {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../wcs-points.php");
    }
    else {
        $wcs_title = addslashes($_POST['wcs_title']);
        $wcs_title_ar = addslashes($_POST['wcs_title_ar']);
        $wcs_description = addslashes($_POST['wcs_description']);
        $wcs_description_ar = addslashes($_POST['wcs_description_ar']);

        $filnm = rand(250000, 500000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);
        if ($file_name != '') {
            $query = "UPDATE `tbl_wcs_points` SET `wcs_title`='" . $wcs_title . "',`wcs_title_ar`='" . $wcs_title_ar . "',`wcs_description`='" . $wcs_description . "',`wcs_description_ar`='" . $wcs_description_ar . "',`image`='" . $subfile . "' WHERE wcs_id='" . $wcs_id . "'";
        }
        else {
            $query = "UPDATE `tbl_wcs_points` SET `wcs_title`='" . $wcs_title . "',`wcs_title_ar`='" . $wcs_title_ar . "',`wcs_description`='" . $wcs_description . "',`wcs_description_ar`='" . $wcs_description_ar . "' WHERE wcs_id='" . $wcs_id . "'";
        }
//exit();
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../wcs-points.php");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../wcs-points.php");
        }
    }
}

################################## WHY CHOOSE US EDIT END ############################
#
#
##################################################################### ABOUT US END################################################
#
#
#
##################################################################### SOLUTIONS ################################################
############################ ADD SOLUTION #########################################
if ($index == 'add_solution') {
//
    $lang = $_POST['lang'];

    $solu_main_title = addslashes($_POST['solu_main_title']);
    $solu_main_description = addslashes($_POST['solu_main_description']);
    $solu_excerpt = addslashes($_POST['solu_excerpt']);
    $solu_sub_title = addslashes($_POST['solu_sub_title']);
    $solu_sub_description = addslashes($_POST['solu_sub_description']);

    $solu_main_title_ar = addslashes($_POST['solu_main_title_ar']);
    $solu_main_description_ar = addslashes($_POST['solu_main_description_ar']);
    $solu_excerpt_ar = addslashes($_POST['solu_excerpt_ar']);
    $solu_sub_title_ar = addslashes($_POST['solu_sub_title_ar']);
    $solu_sub_description_ar = addslashes($_POST['solu_sub_description_ar']);

    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["icon"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["icon"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['icon']["tmp_name"], $file);
    $subfile = substr($file, 3);



    $query = "INSERT INTO `tbl_solutions`(`main_title`, `main_title_ar`, `main_description`, `main_description_ar`, `solu_excerpt`, `solu_excerpt_ar`, `sub_title`, `sub_title_ar`, `sub_description`, `sub_description_ar`,`icon_image`) VALUES ('" . $solu_main_title . "','" . $solu_main_title_ar . "','" . $solu_main_description . "','" . $solu_main_description_ar . "','" . $solu_excerpt . "','" . $solu_excerpt_ar . "','" . $solu_sub_title . "','" . $solu_sub_title_ar . "','" . $solu_sub_description . "','" . $solu_sub_description_ar . "','" . $subfile . "')";


    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../solutions.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../solutions.php");
    }
}
############################ ADD SOLUTION END#########################################
############################ EDIT SOLUTION #########################################
if ($index == 'edit_solution') {
//
    $lang = $_POST['lang'];
    $solution_id = $_POST['solution_id'];
    $solu_main_title = addslashes($_POST['solu_main_title']);
    $solu_main_description = addslashes($_POST['solu_main_description']);
    $solu_excerpt = addslashes($_POST['solu_excerpt']);
    $solu_sub_title = addslashes($_POST['solu_sub_title']);
    $solu_sub_description = addslashes($_POST['solu_sub_description']);
    $order = $_POST['sol_order'];
    $solu_main_title_ar = addslashes($_POST['solu_main_title_ar']);
    $solu_main_description_ar = addslashes($_POST['solu_main_description_ar']);
    $solu_excerpt_ar = addslashes($_POST['solu_excerpt_ar']);
    $solu_sub_title_ar = addslashes($_POST['solu_sub_title_ar']);
    $solu_sub_description_ar = addslashes($_POST['solu_sub_description_ar']);

    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["icon"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["icon"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['icon']["tmp_name"], $file);
    $subfile = substr($file, 3);
    if ($file_name != '') {
        $query = "UPDATE `tbl_solutions` SET `main_title`='" . $solu_main_title . "',`main_title_ar`='" . $solu_main_title_ar . "',`main_description`='" . $solu_main_description . "',`main_description_ar`='" . $solu_main_description_ar . "',`solu_excerpt`='" . $solu_excerpt . "',`solu_excerpt_ar`='" . $solu_excerpt_ar . "',`sub_title`='" . $solu_sub_title . "',`sub_title_ar`='" . $solu_sub_title_ar . "',`sub_description`='" . $solu_sub_description . "',`sub_description_ar`='" . $solu_sub_description_ar . "',`icon_image`='" . $subfile . "',`sol_order`='" . $order . "' WHERE solution_id='" . $solution_id . "'";
    }
    else if ($file_name == '') {
        $query = "UPDATE `tbl_solutions` SET `main_title`='" . $solu_main_title . "',`main_title_ar`='" . $solu_main_title_ar . "',`main_description`='" . $solu_main_description . "',`main_description_ar`='" . $solu_main_description_ar . "',`solu_excerpt`='" . $solu_excerpt . "',`solu_excerpt_ar`='" . $solu_excerpt_ar . "',`sub_title`='" . $solu_sub_title . "',`sub_title_ar`='" . $solu_sub_title_ar . "',`sub_description`='" . $solu_sub_description . "',`sub_description_ar`='" . $solu_sub_description_ar . "',`sol_order`='" . $order . "' WHERE solution_id='" . $solution_id . "'";
    }




    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../solutions.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../solutions.php");
    }
}
############################ EDIT SOLUTION #########################################
#
############################# ADD SOLUTION IMAGE #########################################
if ($index == 'add_solution_images') {
//
    $solution_id = $_POST['solution_id'];
    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["banner"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $sliderimg = $target_dir . basename($_FILES["banner"]["name"]);
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['banner']["tmp_name"], $file);
    echo $subfile = substr($file, 3);

    $filnm_s = rand(250000, 500000);
    $file_name_s = basename($_FILES["small"]["name"]);
    $file_name_s = str_replace(" ", "-", $file_name_s);
    $sliderimg_s = $target_dir . basename($_FILES["small"]["name"]);
    $file_s = $target_dir . $filnm_s . $file_name_s;
    move_uploaded_file($_FILES['small']["tmp_name"], $file_s);
    echo $subfile_s = substr($file_s, 3);

    if (($file_name != '') && ($file_name_s != '')) {
        $query = "UPDATE `tbl_solutions` SET `banner_image`='" . $subfile . "',`small_image`='" . $subfile_s . "' WHERE solution_id='" . $solution_id . "'";
    }
    else if ($file_name != '') {
        $query = "UPDATE `tbl_solutions` SET `banner_image`='" . $subfile . "' WHERE solution_id='" . $solution_id . "'";
    }
    else if ($file_name_s != '') {
        $query = "UPDATE `tbl_solutions` SET `small_image`='" . $subfile_s . "' WHERE solution_id='" . $solution_id . "'";
    }
    else if (($file_name == '') && ($file_name_s == '')) {
        $query = "";
    }

//exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../solutions.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../solutions.php");
    }
}

############################## ADD SOLUTION IMAGE END#########################################
##################################################################### SOLUTIONS END################################################
#
#
##################################################################### PRODUCTS ################################################
############################ ADD PRODUCTS #########################################
if ($index == 'add_products') {
//
    $lang = $_POST['lang'];
    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["prod_image"]["name"]);
    if ($file_name != '') {
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["prod_image"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['prod_image']["tmp_name"], $file);
        echo $subfile = substr($file, 3);
    }
    else {
        $subfile = '';
    }



    $file_name_s = basename($_FILES["prod_image_ar"]["name"]);
    if ($file_name_s != '') {
        $file_name_s = str_replace(" ", "-", $file_name_s);
        $sliderimg_s = $target_dir . basename($_FILES["prod_image_ar"]["name"]);
        $file_s = $target_dir . $filnm . $file_name_s;
        move_uploaded_file($_FILES['prod_image_ar']["tmp_name"], $file_s);
        echo $subfile_s = substr($file_s, 3);
    }
    else {
        $subfile_s = '';
    }


    $prod_main_title = addslashes($_POST['prod_main_title']);
    $prod_main_description = addslashes($_POST['prod_main_description']);
    $prod_excerpt = addslashes($_POST['prod_excerpt']);
    $prod_sub_title = addslashes($_POST['prod_sub_title']);
    $prod_sub_description = addslashes($_POST['prod_sub_description']);
    $prod_video_link_url = addslashes($_POST['prod_video_link']);
    parse_str(parse_url($prod_video_link_url, PHP_URL_QUERY), $vid);
    echo $prod_video_link = $vid['v'];
    $prod_video_text = addslashes($_POST['prod_video_text']);
    $prod_image_title = addslashes($_POST['prod_image_title']);

    $prod_main_title_ar = addslashes($_POST['prod_main_title_ar']);
    $prod_main_description_ar = addslashes($_POST['prod_main_description_ar']);
    $prod_excerpt_ar = addslashes($_POST['prod_excerpt_ar']);
    $prod_sub_title_ar = addslashes($_POST['prod_sub_title_ar']);
    $prod_sub_description_ar = addslashes($_POST['prod_sub_description_ar']);
    $prod_video_link_ar_url = addslashes($_POST['prod_video_link_ar']);
    parse_str(parse_url($prod_video_link_ar_url, PHP_URL_QUERY), $vid);
    echo $prod_video_link_ar = $vid['v'];
    $prod_video_text_ar = addslashes($_POST['prod_video_text_ar']);
    $prod_image_title_ar = addslashes($_POST['prod_image_title_ar']);

    echo $query = "INSERT INTO `tbl_products`(`main_title`, `main_title_ar`, `main_description`, `main_description_ar`, `prod_excerpt`, `prod_excerpt_ar`, `sub_title`, `sub_title_ar`, `sub_description`, `sub_description_ar`, `video_link`, `video_link_ar`,`video_text`, `video_text_ar`, `image_title`, `image_title_ar`, `image`, `image_ar`) VALUES ('" . $prod_main_title . "','" . $prod_main_title_ar . "','" . $prod_main_description . "','" . $prod_main_description_ar . "','" . $prod_excerpt . "','" . $prod_excerpt_ar . "','" . $prod_sub_title . "','" . $prod_sub_title_ar . "','" . $prod_sub_description . "','" . $prod_sub_description_ar . "','" . $prod_video_link . "','" . $prod_video_link_ar . "','" . $prod_video_text . "','" . $prod_video_text_ar . "','" . $prod_image_title . "','" . $prod_image_title_ar . "','" . $subfile . "','" . $subfile_s . "')";
//exit();

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../products.php");
    }
}
############################ ADD PRODUCTS END #########################################
############################ EDIT PRODUCTS #########################################
if ($index == 'edit_products') {
//
//$lang = $_POST['lang'];

    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["prod_image"]["name"]);
    if ($file_name != '') {
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["prod_image"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['prod_image']["tmp_name"], $file);
        echo $subfile = substr($file, 3);
    }
    else {
        $subfile = '';
    }


    $file_name_s = basename($_FILES["prod_image_ar"]["name"]);
    if ($file_name_s != '') {
        $file_name_s = str_replace(" ", "-", $file_name_s);
        $sliderimg_s = $target_dir . basename($_FILES["prod_image_ar"]["name"]);
        $file_s = $target_dir . $filnm . $file_name_s;
        move_uploaded_file($_FILES['prod_image_ar']["tmp_name"], $file_s);
        echo $subfile_s = substr($file_s, 3);
    }
    else {
        $subfile_s = '';
    }

    $products_id = $_POST['products_id'];
    $prod_main_title = addslashes($_POST['prod_main_title']);
    $prod_main_description = addslashes($_POST['prod_main_description']);
    $prod_excerpt = addslashes($_POST['prod_excerpt']);
    $prod_sub_title = addslashes($_POST['prod_sub_title']);
    $prod_sub_description = addslashes($_POST['prod_sub_description']);
    $prod_video_link_url = addslashes($_POST['prod_video_link']);
    parse_str(parse_url($prod_video_link_url, PHP_URL_QUERY), $vid);
    echo $prod_video_link = $vid['v'];
    $prod_video_text = addslashes($_POST['prod_video_text']);
    $prod_image_title = addslashes($_POST['prod_image_title']);
    $order = $_POST['pro_order'];
    $prod_main_title_ar = addslashes($_POST['prod_main_title_ar']);
    $prod_main_description_ar = addslashes($_POST['prod_main_description_ar']);
    $prod_excerpt_ar = addslashes($_POST['prod_excerpt_ar']);
    $prod_sub_title_ar = addslashes($_POST['prod_sub_title_ar']);
    $prod_sub_description_ar = addslashes($_POST['prod_sub_description_ar']);
    $prod_video_link_ar_url = addslashes($_POST['prod_video_link_ar']);
    parse_str(parse_url($prod_video_link_ar_url, PHP_URL_QUERY), $vid);
    echo $prod_video_link_ar = $vid['v'];
    $prod_video_text_ar = addslashes($_POST['prod_video_text_ar']);
    $prod_image_title_ar = addslashes($_POST['prod_image_title_ar']);


    if (($file_name != '') && ($file_name_s != '')) {
        $query = "UPDATE `tbl_products` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`prod_excerpt`='" . $prod_excerpt . "',`prod_excerpt_ar`='" . $prod_excerpt_ar . "',`sub_title`='" . $prod_sub_title . "',`sub_title_ar`='" . $prod_sub_title_ar . "',`sub_description`='" . $prod_sub_description . "',`sub_description_ar`='" . $prod_sub_description_ar . "',`video_link`='" . $prod_video_link . "',`video_link_ar`='" . $prod_video_link_ar . "',`video_text`='" . $prod_video_text . "',`video_text_ar`='" . $prod_video_text_ar . "',`image_title`='" . $prod_image_title . "',`image_title_ar`='" . $prod_image_title_ar . "',`image`='" . $subfile . "',`image_ar`='" . $subfile_s . "',`pro_order`='" . $order . "' WHERE products_id='" . $products_id . "'";
    }
    else if ($file_name != '') {
        $query = "UPDATE `tbl_products` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`prod_excerpt`='" . $prod_excerpt . "',`prod_excerpt_ar`='" . $prod_excerpt_ar . "',`sub_title`='" . $prod_sub_title . "',`sub_title_ar`='" . $prod_sub_title_ar . "',`sub_description`='" . $prod_sub_description . "',`sub_description_ar`='" . $prod_sub_description_ar . "',`video_link`='" . $prod_video_link . "',`video_link_ar`='" . $prod_video_link_ar . "',`video_text`='" . $prod_video_text . "',`video_text_ar`='" . $prod_video_text_ar . "',`image_title`='" . $prod_image_title . "',`image_title_ar`='" . $prod_image_title_ar . "',`image`='" . $subfile . "',`pro_order`='" . $order . "' WHERE products_id='" . $products_id . "'";
    }
    else if ($file_name_s != '') {
        $query = "UPDATE `tbl_products` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`prod_excerpt`='" . $prod_excerpt . "',`prod_excerpt_ar`='" . $prod_excerpt_ar . "',`sub_title`='" . $prod_sub_title . "',`sub_title_ar`='" . $prod_sub_title_ar . "',`sub_description`='" . $prod_sub_description . "',`sub_description_ar`='" . $prod_sub_description_ar . "',`video_link`='" . $prod_video_link . "',`video_link_ar`='" . $prod_video_link_ar . "',`video_text`='" . $prod_video_text . "',`video_text_ar`='" . $prod_video_text_ar . "',`image_title`='" . $prod_image_title . "',`image_title_ar`='" . $prod_image_title_ar . "',`image_ar`='" . $subfile_s . "',`pro_order`='" . $order . "' WHERE products_id='" . $products_id . "'";
    }
    else if (($file_name == '') && ($file_name_s == '')) {
        $query = "UPDATE `tbl_products` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`prod_excerpt`='" . $prod_excerpt . "',`prod_excerpt_ar`='" . $prod_excerpt_ar . "',`sub_title`='" . $prod_sub_title . "',`sub_title_ar`='" . $prod_sub_title_ar . "',`sub_description`='" . $prod_sub_description . "',`sub_description_ar`='" . $prod_sub_description_ar . "',`video_link`='" . $prod_video_link . "',`video_link_ar`='" . $prod_video_link_ar . "',`video_text`='" . $prod_video_text . "',`video_text_ar`='" . $prod_video_text_ar . "',`image_title`='" . $prod_image_title . "',`image_title_ar`='" . $prod_image_title_ar . "',`pro_order`='" . $order . "' WHERE products_id='" . $products_id . "'";
    }

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../products.php");
    }
}
############################ EDIT PRODUCTS #########################################
#
############################# ADD PRODUCTS IMAGE #########################################
if ($index == 'add_products_images') {

    $products_id = $_POST['products_id'];
    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["banner"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $sliderimg = $target_dir . basename($_FILES["banner"]["name"]);
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['banner']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $filnm_s = rand(250000, 500000);
    $file_name_s = basename($_FILES["small"]["name"]);
    $file_name_s = str_replace(" ", "-", $file_name_s);
    $sliderimg_s = $target_dir . basename($_FILES["small"]["name"]);
    $file_s = $target_dir . $filnm_s . $file_name_s;
    move_uploaded_file($_FILES['small']["tmp_name"], $file_s);
    $subfile_s = substr($file_s, 3);

    $filnm_sub = rand(250000, 500000);
    $file_name_sub = basename($_FILES["subimg"]["name"]);
    $file_name_sub = str_replace(" ", "-", $file_name_sub);
    $sliderimg_sub = $target_dir . basename($_FILES["subimg"]["name"]);
    $file_sub = $target_dir . $filnm_sub . $file_name_sub;
    move_uploaded_file($_FILES['subimg']["tmp_name"], $file_sub);
    $subfile_sub = substr($file_sub, 3);

    if (($file_name != '') && ($file_name_s != '')) {
        $query = "UPDATE `tbl_products` SET `banner_image`='" . $subfile . "',`small_image`='" . $subfile_s . "' WHERE products_id='" . $products_id . "'";
    }
    else if ($file_name != '') {
        $query = "UPDATE `tbl_products` SET `banner_image`='" . $subfile . "' WHERE products_id='" . $products_id . "'";
    }
    else if ($file_name_s != '') {
        $query = "UPDATE `tbl_products` SET `small_image`='" . $subfile_s . "' WHERE products_id='" . $products_id . "'";
    }
    else if (($file_name == '') && ($file_name_s == '')) {
        $query = "";
    }
    if ($file_name_sub != '') {
        $query1 = "UPDATE `tbl_products` SET `sub_image`='" . $subfile_sub . "' WHERE products_id='" . $products_id . "'";
    }
    else if ($file_name_sub == '') {
        $query1 = "";
    }
    echo $query;
    echo $query1;
//exit();
    $result1 = execute($query1);
//exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../products.php");
    }
}

############################## ADD PRODUCTS IMAGE END#########################################
#
############################### ADD TAGS ##########################################
if ($index == 'add_tags') {

    $products_id = $_POST['products_id'];
    $tags = $_POST['tags'];

    $tag_one = implode(",", $tags);
    $query = "UPDATE `tbl_products` SET `tags`='" . $tag_one . "' WHERE products_id='" . $products_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../products.php");
    }
}

################################ ADD TAGS ##########################################
##################################################################### PRODUCTS END################################################
if ($index == 'cms_update') {
    if ((trim($_POST['page_title']) == '') || (trim($_POST['page_content']) == '') || (trim($_POST['page_excerpt']) == '')) {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../cms-page.php?id=" . $page_id . "");
    }
    else {
        $page_title = $_POST['page_title'];
        $page_content = $_POST['page_content'];
        $excerpt = $_POST['page_excerpt'];
        $page_id = $_POST['page_id'];
        $query = "Update tbl_cms set page_title = '" . $page_title . "', page_content = '" . $page_content . "', excerpt = '" . $excerpt . "' where id = '" . $page_id . "'";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../cms-page.php?id=" . $page_id . "");
        }
        else {
            $_SESSION["err"] = "Error updating ";
            header("Location:../cms-page.php?id=" . $page_id . "");
        }
    }
}


if ($index == 'activate_user') {
    $id = $_POST['id'];
    $res = get_where_cond("tbl_memcard", "mem_u_id = '" . $id . "'");
    if ($res->num_rows > 0) {

        $row = $res->fetch_assoc();
        $startdate = date("Y/m/d");

        $months = $row['mem_months'];
        $court = $row['court_id'];
        $slot = $row['time_slot'];
        $amid = $row['am_id'];
        $date = date_create(date("Y/m/d"));
//        echo $date;
        date_add($date, date_interval_create_from_date_string("'.$months.'"));
        $expdate = date_format($date, "Y/m/d");
    }
//              echo $startdate;
//              echo $expdate;

    $query1 = "UPDATE `tbl_memcard` SET `mem_date` = '" . $startdate . "', `exp_date` = '" . $expdate . "' where `mem_u_id` = " . $id;
    $result1 = execute($query1);

    $query2 = "INSERT INTO `tbl_bookings`(`user_id`, `court_id`, `slot`, `start_date`, `end_date`, `am_id`) VALUES ('" . $id . "', '" . $court . "', '" . $slot . "', '" . $startdate . "', '" . $expdate . "', '" . $amid . "')";
    $result2 = execute($query2);
    $query = "UPDATE `tbl_members` SET `user_status` = 1 where `user_id` = " . $id;

    $result = execute($query);
//      echo $query1;
//       echo $query;
//        echo $query2;
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        $_SESSION["err"] = "User Activated Successfully";

        header("Location:../members.php");
    }
    else {
        $_SESSION["err"] = "Error Activating user";

        header("Location:../members.php");
    }
}




if ($index == 'add_blog') {

    $language = $_POST['lang'];
    $blog_date = date('Y-m-d');
    $sess_user = $_SESSION['uname'];
    $get_author = get_where_cond("tbl_users", "Username='" . $sess_user . "'");
    $res_author = $get_author->fetch_assoc();
    $blog_author = $res_author['Name'];
    $blog_title = addslashes($_POST['blog_title']);
    $blog_desc = addslashes($_POST['blog_description']);
    $blog_excerpt = addslashes($_POST['blog_excerpt']);
    $blog_title_ar = addslashes($_POST['blog_title_ar']);
    $blog_desc_ar = addslashes($_POST['blog_description_ar']);
    $blog_excerpt_ar = addslashes($_POST['blog_excerpt_ar']);

    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["blog"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["blog"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['blog']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_namea = basename($_FILES["blog_ar"]["name"]);
    $file_namea = str_replace(" ", "-", $file_namea);
    $imagea = $target_dir . basename($_FILES["blog_ar"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1['extension'];
    $filea = $target_dir . $filnma . $file_namea;
    move_uploaded_file($_FILES['blog_ar']["tmp_name"], $filea);
    $subfilea = substr($filea, 3);

    $query = "INSERT INTO `tbl_blog`( `title`,`title_ar`, `author`, `content`,`content_ar`, `image`,`image_ar`,`blog_excerpt`,`blog_excerpt_ar`,`date`) VALUES ('" . $blog_title . "','" . $blog_title_ar . "','" . $blog_author . "','" . $blog_desc . "','" . $blog_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $blog_excerpt . "','" . $blog_excerpt_ar . "','" . $blog_date . "')";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Blog Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../blog.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Blog";
        header("Location:../blog.php");
    }
}
if ($index == 'update_blog') {

//
    $blog_id = $_POST['blog_id'];
    $blog_date = date('Y-m-d');
    $sess_user = $_SESSION['uname'];
    $get_author = get_where_cond("tbl_users", "Username='" . $sess_user . "'");
    $res_author = $get_author->fetch_assoc();
    $blog_author = $res_author['Name'];
    $blog_title = addslashes($_POST['blog_title']);
    $blog_desc = addslashes($_POST['blog_description']);
    $blog_excerpt = addslashes($_POST['blog_excerpt']);
    $blog_title_ar = addslashes($_POST['blog_title_ar']);
    $blog_desc_ar = addslashes($_POST['blog_description_ar']);
    $blog_excerpt_ar = addslashes($_POST['blog_excerpt_ar']);

    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["blog"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["blog"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['blog']["tmp_name"], $file);
    $subfile = substr($file, 3);
    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_namea = basename($_FILES["blog_ar"]["name"]);
    $file_namea = str_replace(" ", "-", $file_namea);
    $imagea = $target_dir . basename($_FILES["blog_ar"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1a['extension'];
    $filea = $target_dir . $filnma . $file_namea;
    move_uploaded_file($_FILES['blog_ar']["tmp_name"], $filea);
    $subfilea = substr($filea, 3);

    if (($file_name != '') && ($file_namea != '')) {
        $query = "UPDATE `tbl_blog` SET `title` = '" . $blog_title . "', title_ar='" . $blog_title_ar . "',author='" . $blog_author . "',content='" . $blog_desc . "',content_ar='" . $blog_desc_ar . "',image='" . $subfile . "',image_ar='" . $subfilea . "',blog_excerpt='" . $blog_excerpt . "',blog_excerpt_ar='" . $blog_excerpt_ar . "' where `id` ='" . $blog_id . "'";
    }
    else if (($file_name == '') && ($file_namea != '')) {
        $query = "UPDATE `tbl_blog` SET `title` = '" . $blog_title . "', title_ar='" . $blog_title_ar . "',author='" . $blog_author . "',content='" . $blog_desc . "',content_ar='" . $blog_desc_ar . ",image_ar='" . $subfilea . "',blog_excerpt='" . $blog_excerpt . "',blog_excerpt_ar='" . $blog_excerpt_ar . "' where `id` ='" . $blog_id . "'";
    }
    else if (($file_name != '') && ($file_namea == '')) {
        $query = "UPDATE `tbl_blog` SET `title` = '" . $blog_title . "', title_ar='" . $blog_title_ar . "',author='" . $blog_author . "',content='" . $blog_desc . "',content_ar='" . $blog_desc_ar . "',image='" . $subfile . "',blog_excerpt='" . $blog_excerpt . "',blog_excerpt_ar='" . $blog_excerpt_ar . "' where `id` ='" . $blog_id . "'";
    }
    else {
        $query = "UPDATE `tbl_blog` SET `title` = '" . $blog_title . "', title_ar='" . $blog_title_ar . "',author='" . $blog_author . "',content='" . $blog_desc . "',content_ar='" . $blog_desc_ar . "',blog_excerpt='" . $blog_excerpt . "',blog_excerpt_ar='" . $blog_excerpt_ar . "' where `id` ='" . $blog_id . "'";
    }
//    exit();

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        $_SESSION["err"] = "Blog Added Successfully";
        header("Location:../blog.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Blog";
        header("Location:../blog.php");
    }

    exit();


    $lang = $_POST['lang'];

    $blogid = $_POST['blogid'];
    $blog_date = date('Y-m-d');
    $sess_user = $_SESSION['uname'];
    $get_author = get_where_cond("tbl_users", "Username='" . $sess_user . "'");
    $res_author = $get_author->fetch_assoc();
    $blog_author = $res_author['Name'];

    if (trim($lang) == 'en') {

        $title = addslashes($_POST['blog_title']);
        $blog_description = addslashes($_POST['blog_description']);
        $blog_excerpt = addslashes($_POST['blog_excerpt']);


        $filnm = rand(4500, 8000);
        $target_dir = "../uploads/";
        $file_name = basename($_FILES["blog"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $image = $target_dir . basename($_FILES["blog"]["name"]);
        $ext1 = pathinfo($image);
        $ext = $ext1['extension'];
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['blog']["tmp_name"], $file);
        $subfile = substr($file, 3);

        if ($file_name != '') {

            $query = "UPDATE `tbl_blog` SET `title`='" . $title . "',`author`='" . $blog_author . "',`content`='" . $blog_description . "',`image`='" . $subfile . "',`blog_excerpt`='" . $blog_excerpt . "',`date`='" . $blog_date . "' WHERE `id`='" . $blogid . "'";
            echo $query;
            exit();
            $result = execute($query);
            if ($result == 'Query Executed Successfully') {
                $_SESSION["err"] = "Blog Updated Successfully";
                header("Location:../blog.php");
            }
            else {
                $_SESSION["err"] = "Error Updating Blog";
                header("Location:../blog.php");
            }
        }
        else {

            $query = "UPDATE `tbl_blog` SET `title`='" . $title . "',`author`='" . $blog_author . "',`content`='" . $blog_description . "'blog_excerpt`='" . $blog_excerpt . "',`date`='" . $blog_date . "' WHERE `id`='" . $blogid . "'";
            echo $query;
            exit();
            $result = execute($query);
            if ($result == 'Query Executed Successfully') {
                $_SESSION["err"] = "Blog Updated Successfully";
                $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
                $res_preview = execute($preview);
                header("Location:../blog.php");
            }
            else {
                $_SESSION["err"] = "Error Updating Blog";
                header("Location:../blog.php");
            }
        }
    }
    elseif ($lang == 'ar') {
        $title_ar = addslashes($_POST['blog_title_ar']);
        $content_ar = addslashes($_POST['blog_description_ar']);
        $blog_excerpt_ar = addslashes($_POST['blog_excerpt_ar']);

        $filnma = rand(4500, 8000);
        $target_dir = "../uploads/";
        $file_namea = basename($_FILES["blog_ar"]["name"]);
        $file_namea = str_replace(" ", "-", $file_namea);
        $imagea = $target_dir . basename($_FILES["blog_ar"]["name"]);
        $ext1a = pathinfo($image);
        $exta = $ext1['extension'];
        $filea = $target_dir . $filnma . $file_namea;
        move_uploaded_file($_FILES['blog_ar']["tmp_name"], $filea);
        $subfilea = substr($filea, 3);

        if ($file_namea != '') {
            $query = "UPDATE `tbl_blog` SET `title_ar`='" . $title_ar . "',`author_ar`='" . $blog_author . "',`content_ar`='" . $content_ar . "',`image_ar`='" . $subfilea . "',`blog_excerpt_ar`='" . $blog_excerpt_ar . "',`date`='" . $blog_date . "' WHERE `id`='" . $blogid . "'";
            echo $query;
            exit();
            $result = execute($query);
            if ($result == 'Query Executed Successfully') {
                $_SESSION["err"] = "Blog Updated Successfully";
                $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
                $res_preview = execute($preview);
                header("Location:../blog.php");
            }
            else {
                $_SESSION["err"] = "Error Updating Blog";
                header("Location:../blog.php");
            }
        }
        else {
            $query = "UPDATE `tbl_blog` SET `title_ar`='" . $title_ar . "',`author_ar`='" . $blog_author . "',`content_ar`='" . $content_ar . "',`blog_excerpt_ar`='" . $blog_excerpt_ar . "',`date`='" . $blog_date . "' WHERE `id`='" . $blogid . "'";
            echo $query;
            exit();
            $result = execute($query);
            if ($result == 'Query Executed Successfully') {
                $_SESSION["err"] = "Blog Updated Successfully";
                $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
                $res_preview = execute($preview);
                header("Location:../blog.php");
            }
            else {
                $_SESSION["err"] = "Error Updating Blog";
                header("Location:../blog.php");
            }
        }
    }
//
}
if ($index == 'add_news') {
    $tags = $_POST['tags'];
    $taggs = implode(",", $tags);
    echo $taggs;


    $language = $_POST['lang'];
    $news_date = $_POST['date'];

    $news_title = addslashes($_POST['news_title']);
    $news_desc = addslashes($_POST['news_description']);
    $news_excerpt = addslashes($_POST['news_excerpt']);
    $news_title_ar = addslashes($_POST['news_title_ar']);
    $news_desc_ar = addslashes($_POST['news_description_ar']);
    $news_excerpt_ar = addslashes($_POST['news_excerpt_ar']);
    //$news_or_event = $_POST['newsorvent'];



    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["news"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["news"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['news']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_namea = basename($_FILES["news_ar"]["name"]);
    $file_namea = str_replace(" ", "-", $file_namea);
    $imagea = $target_dir . basename($_FILES["news_ar"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1['extension'];
    $filea = $target_dir . $filnma . $file_namea;
    move_uploaded_file($_FILES['news_ar']["tmp_name"], $filea);
    $subfilea = substr($filea, 3);

    echo $query = "INSERT INTO `tbl_posts`(`tags`,`title`, `title_ar`,`blog_excerpt`,`blog_excerpt_ar`, `content`, `content_ar`, `image`, `image_ar`, `date`) VALUES ('" . $taggs . "','" . $news_title . "','" . $news_title_ar . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "News Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../news.php");
    }
    else {
        $_SESSION["err"] = "Error Adding News";
        header("Location:../news.php");
    }
}
if ($index == 'update_news') {

    $tags = $_POST['tags'];
    $taggs = implode(",", $tags);

    $news_date = $_POST['date'];
    $news_id = $_POST['news_id'];
    $news_title = addslashes($_POST['news_title']);
    $news_desc = addslashes($_POST['news_description']);
    $news_excerpt = addslashes($_POST['news_excerpt']);

//    $news_or_event = $_POST['newsorvent'];

    $filnm = rand(4500, 8000);
    $target_dir = "../../assets/img/blog/";
    $file_name = basename($_FILES["news"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);

    if ($file_name != '') {
        $image = $target_dir . basename($_FILES["news"]["name"]);
        $ext1 = pathinfo($image);
        $ext = $ext1['extension'];
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['news']["tmp_name"], $file);
        $subfile = substr($file, 3);
        $query = "UPDATE `tbl_posts` SET `tags`='" . $taggs . "', `title`='" . $news_title . "',`blog_excerpt`='" . $news_excerpt . "',`content`='" . $news_desc . "',`image`='" . $subfile . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }
    else {

        $image = $target_dir . basename($_FILES["news"]["name"]);
        $ext1 = pathinfo($image);
        $ext = $ext1['extension'];
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['news']["tmp_name"], $file);
        $subfile = substr($file, 3);
        $query = "UPDATE `tbl_posts` SET `tags`='" . $taggs . "', `title`='" . $news_title . "',`blog_excerpt`='" . $news_excerpt . "',`content`='" . $news_desc . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "News Updated Successfully";
        // $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        //$res_preview = execute($preview);
        header("Location:../news.php");
    }
    else {
        $_SESSION["err"] = "Error Updating News";
        header("Location:../news.php");
    }
}

if ($index == 'delete_news') {
    $news_id = $_POST['id'];
    $query = "DELETE FROM `tbl_posts` WHERE `id`='" . $news_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "News Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../news.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting News";
        header("Location:../news.php");
    }
}
if ($index == 'delete_testi') {


    $testi_id = $_POST['id'];


    $query = "DELETE FROM `tbl_testimonial` WHERE `id`='" . $testi_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Testimonial Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../testimonials.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting";
        header("Location:../testimonials.php");
    }
}
if ($index == 'delete_blog') {


    $blog_id = $_POST['id'];


    $query = "DELETE FROM `tbl_blog` WHERE `id`='" . $blog_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Blog Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../blog.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Blog";
        header("Location:../blog.php");
    }
}
if ($index == 'add_testimonial') {

    $language = $_POST['lang'];
    $testi_date = date('Y-m-d');

    $testi_author = addslashes($_POST['testi_author']);
    $testi_desc = addslashes($_POST['testi_description']);
    $testi_designation = addslashes($_POST['testi_designation']);
    $testi_designation_ar = addslashes($_POST['testi_designation_ar']);
    $testi_author_ar = addslashes($_POST['testi_author_ar']);
    $testi_desc_ar = addslashes($_POST['testi_description_ar']);


    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["testi"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["testi"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['testi']["tmp_name"], $file);
    $subfile = substr($file, 3);



    $query = "INSERT INTO `tbl_testimonial`(`author`, `author_ar`, `content`, `content_ar`, `image`,`designation`, `designation_ar`, `date`) VALUES ('" . $testi_author . "','" . $testi_author_ar . "','" . $testi_desc . "','" . $testi_desc_ar . "','" . $subfile . "','" . $testi_designation . "','" . $testi_designation_ar . "','" . $testi_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Testimonial Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../testimonials.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Testimonial";
        header("Location:../testimonials.php");
    }
}
if ($index == 'update_testimonial') {



    $testi_date = date('Y-m-d');
    $testi_id = $_POST['testi_id'];
    $testi_author = addslashes($_POST['testi_author']);
    $testi_desc = addslashes($_POST['testi_description']);
    $testi_designation = addslashes($_POST['testi_designation']);
    $testi_designation_ar = addslashes($_POST['testi_designation_ar']);
    $testi_author_ar = addslashes($_POST['testi_author_ar']);
    $testi_desc_ar = addslashes($_POST['testi_description_ar']);


    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["testi"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["testi"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['testi']["tmp_name"], $file);
    $subfile = substr($file, 3);

    if ($file_name != '') {
        $query = "UPDATE `tbl_testimonial` SET `author`='" . $testi_author . "',`author_ar`='" . $testi_author_ar . "',`content`='" . $testi_desc . "',`content_ar`='" . $testi_desc_ar . "',`image`='" . $subfile . "',`designation`='" . $testi_designation . "',`designation_ar`='" . $testi_designation_ar . "',`date`='" . $testi_date . "' WHERE `id`='" . $testi_id . "'";
//        $query = "UPDATE `tbl_news` SET `title`='" . $news_title . "',`title_ar`='" . $news_title_ar . "',`content`='" . $news_desc . "',`content_ar`='" . $news_desc_ar . "',`image`='" . $subfile . "',`news_excerpt`='" . $news_excerpt . "',`news_excerpt_ar`='" . $news_excerpt . "',`type`='" . $news_or_event . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }
    else if ($file_name == '') {
        $query = "UPDATE `tbl_testimonial` SET `author`='" . $testi_author . "',`author_ar`='" . $testi_author_ar . "',`content`='" . $testi_desc . "',`content_ar`='" . $testi_desc_ar . "',`designation`='" . $testi_designation . "',`designation_ar`='" . $testi_designation_ar . "',`date`='" . $testi_date . "' WHERE `id`='" . $testi_id . "'";
    }
////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Testimonial Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../testimonials.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Testimonial";
        header("Location:../testimonials.php");
    }
}
if ($index == 'update_footer_contact') {
//
    $footer_contact_id = $_POST['footer_contact_id'];
    $place = trim($_POST['place']);
    $place_ar = trim($_POST['place_ar']);
    $phone = trim($_POST['phone']);


//    $map = trim(addslashes($_POST['map']));
//$query="INSERT INTO `tbl_contact`(`email`, `phone`, `address`, `address_ar`, `meta_id`) VALUES ('".$email."','".$phno."','".$address."','".$address_ar."')";


    $query = "UPDATE `tbl_footer_contact` SET `place`='" . $place . "',`place_ar`='" . $place_ar . "',`phone`='" . $phone . "' WHERE `id`='" . $footer_contact_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Footer Contact Details Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../footer_contact.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Details";
        header("Location:../footer_contact.php");
    }
}
if ($index == 'delete_footer_contact') {


    $footer_contact_id = $_POST['footer_contact_id'];


    $query = "DELETE FROM `tbl_footer_contact` WHERE `id`='" . $footer_contact_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Contact Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../footer_contact.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Contact";
        header("Location:../footer_contact.php");
    }
}
if ($index == 'footer_contact') {
//
    $place = trim($_POST['place']);
    $place_ar = trim($_POST['place_ar']);
    $phone = trim($_POST['phone']);

//    $map = trim(addslashes($_POST['map']));
    $query = "INSERT INTO `tbl_footer_contact`(`place`, `place_ar`, `phone`) VALUES ('" . $place . "','" . $place_ar . "','" . $phone . "')";


//    $query = "UPDATE `tbl_contact` set email = '" . $email . "', phone = '" . $phone . "', address = '" . $address . "' where `id` = 1";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Footer Contact Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../footer_contact.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Footer Contact";
        header("Location:../footer_contact.php");
    }
}
if ($index == 'contact_details') {
//
    $place = trim($_POST['place']);
    $place_ar = trim($_POST['place_ar']);
    $email = trim($_POST['email']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);
    $phone3 = trim($_POST['phone3']);
    $phno = $phone1 . ',' . $phone2 . ',' . $phone3;
    $address = addslashes($_POST['address']);
    $address_ar = addslashes($_POST['address_ar']);
    if (($place == '') && ($place_ar == '') && ($email == '') && ($phone1 == '') && ($phone2 == '') && ($phone3 == '') && ($address == '') && ($address_ar == '')) {
        $_SESSION["err"] = "Please Enter  Valid Details";
        header("Location:../contact.php");
    }
    else {
//    $map = trim(addslashes($_POST['map']));
        $query = "INSERT INTO `tbl_contact`(`email`, `phone`, `place`, `place_ar`, `address`, `address_ar`) VALUES ('" . $email . "','" . $phno . "','" . $place . "','" . $place_ar . "','" . $address . "','" . $address_ar . "')";


//    $query = "UPDATE `tbl_contact` set email = '" . $email . "', phone = '" . $phone . "', address = '" . $address . "' where `id` = 1";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "Contact Details Updated Successfully";
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../contact.php");
        }
        else {
            $_SESSION["err"] = "Error Updating Details";
            header("Location:../contact.php");
        }
    }
}
if ($index == 'update_contact_details') {
//

    $contact_id = $_POST['contact_id'];
    $place = trim($_POST['place']);
    $place_ar = trim($_POST['place_ar']);
    $email = trim($_POST['email']);
    $phone1 = trim($_POST['phone1']);
    $phone2 = trim($_POST['phone2']);
    $phone3 = trim($_POST['phone3']);
    $phno = $phone1 . ',' . $phone2 . ',' . $phone3;
    $address = addslashes($_POST['address']);
    $address_ar = addslashes($_POST['address_ar']);
    if (($place == '') && ($place_ar == '') && ($email == '') && ($phone1 == '') && ($phone2 == '') && ($phone3 == '') && ($address == '') && ($address_ar == '')) {
        $_SESSION["err"] = "Please Enter  Valid Details";
        header("Location:../contact.php");
    }
    else {
//    $map = trim(addslashes($_POST['map']));
//$query="INSERT INTO `tbl_contact`(`email`, `phone`, `address`, `address_ar`, `meta_id`) VALUES ('".$email."','".$phno."','".$address."','".$address_ar."')";


        $query = "UPDATE `tbl_contact` set email = '" . $email . "', phone = '" . $phno . "',`place`='" . $place . "',`place_ar`='" . $place_ar . "', address = '" . $address . "', `address_ar`='" . $address_ar . "' where `id` = '" . $contact_id . "'";
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "Contact Details Updated Successfully";
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../contact.php");
        }
        else {
            $_SESSION["err"] = "Error Updating Details";
            header("Location:../contact.php");
        }
    }
}
if ($index == 'delete_contact') {


    $contact_id = $_POST['contact_id'];


    $query = "DELETE FROM `tbl_contact` WHERE `id`='" . $contact_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Contact Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../contact.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Contact";
        header("Location:../contact.php");
    }
}
if ($index == 'update_contact_header') {
    $phone = addslashes($_POST['phone']);
    $phone2 = addslashes($_POST['phone2']);
    $email = addslashes($_POST['email']);
    $semail = addslashes($_POST['semail']);
    $query = "UPDATE `tbl_contact_main` SET `email`='" . $email . "',`semail`='" . $semail . "',`phno`='" . $phone . "',`phno2`='" . $phone2 . "' WHERE `id`='1'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Contact Main Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../contact_main.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Details";
        header("Location:../contact_main.php");
    }
}


if ($index == 'remove_pdt_image') {


    $products_id = $_POST['products_id'];


    $query = "Update `tbl_products` set image='' WHERE `products_id`='" . $products_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Image Deleted Successfully";
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Product";
        header("Location:../products.php");
    }
}
if ($index == 'remove_pdt_image_ar') {


    $products_id = $_POST['products_id'];


    $query = "Update `tbl_products` set image_ar='' WHERE `products_id`='" . $products_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Image Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Product";
        header("Location:../products.php");
    }
}
if ($index == 'delete_products') {


    $products_id = $_POST['products_id'];


    $query = "DELETE FROM `tbl_products` WHERE `products_id`='" . $products_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../products.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Product";
        header("Location:../products.php");
    }
}



##################################################################### SEO ################################################
############################## UPDATE META TAGS #############################
if ($index == 'update_meta') {

    $page_title = trim($_POST['page_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $page_id = trim($_POST['page_id']);
    $page_name = $_POST['page_name'];
    $meta_id = trim($_POST['meta_id']);
    $get_meta_details = get_where_cond("tbl_meta", "meta_id = " . $meta_id);
    if ($get_meta_details->num_rows == 1) {
        echo "Yes";
        $query = "UPDATE `tbl_meta` set meta_page_title = '" . $page_title . "', meta_keywords = '" . $meta_keywords . "', meta_description = '" . $meta_description . "' where `meta_id` = '" . $meta_id . "'";
        $result = execute($query);
    }
    else {
        echo "No";
        $query = " INSERT INTO `tbl_meta`(`page_id`, `meta_page_title`, `meta_keywords`, `meta_description`) VALUES('" . $page_id . "', '" . $page_title . "', '" . $meta_keywords . "', '" . $meta_description . "')";
        $id = get_insertid($query);
        if ($page_name == "main") {

        }
        else if ($page_name == "contact") {
            $query1 = "UPDATE `tbl_contact` set meta_id = '" . $id . "' where `id` = '" . $page_id . "'";
        }
        else if ($page_name == "news") {
            $query1 = "UPDATE `tbl_news` set meta_id = '" . $id . "' where `id` = '" . $page_id . "'";
        }
        else if ($page_name == "testimonials") {
            $query1 = "UPDATE `tbl_testimonials` set meta_id = '" . $id . "' where `id` = '" . $page_id . "'";
        }
        $result = execute($query1);
    }

    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Contact Details Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        if ($page_name == "contact") {
            header("Location:../contact.php");
        }
        else if ($page_name == "news") {
            header("Location:../news.php");
        }
        else if ($page_name == "testimonials") {
            header("Location:../testimonials.php");
        }
        else if ($page_name == "main") {
            header("Location:../main_meta.php");
        }
    }
    else {
        $_SESSION["err"] = "Error Updating Details";
        if ($page_name == "contact") {
            header("Location:../contact.php");
        }
        else if ($page_name == "news") {
            header("Location:../news.php");
        }
        else if ($page_name == "testimonials") {
            header("Location:../testimonials.php");
        }
        else if ($page_name == "main") {
            header("Location:../main_meta.php");
        }
    }
}
############################## UPDATE META TAGS #############################



if ($index == 'update_meta_cms') {
//
    $page_title = trim($_POST['page_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $page_id = trim($_POST['page_id']);
    $meta_id = trim($_POST['meta_id']);
    $get_meta_details = get_where_cond("tbl_meta", "page_id = " . $page_id);
    if ($get_meta_details->num_rows == 1) {
        echo "Yes";
        echo $query = "UPDATE `tbl_meta` set meta_page_title = '" . $page_title . "', meta_keywords = '" . $meta_keywords . "', meta_description = '" . $meta_description . "' where `meta_id` = '" . $meta_id . "'";
        $result = execute($query);
    }
    else {
        echo "No";
        $query = " INSERT INTO `tbl_meta`(`page_id`, `meta_page_title`, `meta_keywords`, `meta_description`) VALUES('" . $page_id . "', '" . $page_title . "', '" . $meta_keywords . "', '" . $meta_description . "')";
        $id = get_insertid($query);
        $query1 = "UPDATE `tbl_cms` set meta_id = '" . $id . "' where `id` = '" . $page_id . "'";
        $result = execute($query1);
    }
    if ($page_id == 1) {
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "SEO Updated Successfully";
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../seo.php?meta_id = " . $meta_id . "");
        }
        else {
            $_SESSION["err"] = "Error Updating SEO";
            header("Location:../seo.php?meta_id = " . $meta_id . "");
        }
    }
    else {
        if ($result == 'Query Executed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            $_SESSION["err"] = "Contact Details Updated Successfully";
            header("Location:../cms-page.php?id = " . $page_id . "");
        }
        else {
            $_SESSION["err"] = "Error Updating Details";
            header("Location:../cms-page.php?id = " . $page_id . "");
        }
    }
}
if ($index == 'add_client') {

    $client_name = $_POST['client_name'];
    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["client_icon"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["client_icon"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['client_icon']["tmp_name"], $file);
    $subfile = substr($file, 3);

    if (trim($_POST['client_name']) == '') {
        $_SESSION["err"] = "Enter Valid Details";
        header("Location:../clients.php");
    }
    else {

        $query = "INSERT INTO `tbl_clients`( `client_name`, `icon`) VALUES ('" . $client_name . "','" . $subfile . "')";
//    echo $query;
//    exit();
        $result = execute($query);
        if ($result == 'Query Executed Successfully') {
            $_SESSION["err"] = "Client Added Successfully";
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../clients.php");
        }
        else {
            $_SESSION["err"] = "Error Adding Clients";
            header("Location:../clients.php");
        }
    }
}


if ($index == 'delete_client') {


    $client_id = $_POST['client_id'];


    $query = "DELETE FROM `tbl_clients` WHERE `id`='" . $client_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Clients Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../clients.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting ";
        header("Location:../clients.php");
    }
}
if ($index == 'update_fun_facts') {



    $fun_id = $_POST['fun_id'];
    $fun_number = addslashes($_POST['fun_number']);
    $funtext_level_1 = addslashes($_POST['funtext_level_1']);
    $funtext_level_2 = addslashes($_POST['funtext_level_2']);
    $fun_number_ar = addslashes($_POST['fun_number_ar']);
    $funtext_level_1_ar = addslashes($_POST['funtext_level_1_ar']);
    $funtext_level_2_ar = addslashes($_POST['funtext_level_1_ar']);


    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["fun"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["fun"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['fun']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_namea = basename($_FILES["fun_ar"]["name"]);
    $file_namea = str_replace(" ", "-", $file_namea);
    $imagea = $target_dir . basename($_FILES["fun_ar"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1['extension'];
    $filea = $target_dir . $filnma . $file_namea;
    move_uploaded_file($_FILES['fun_ar']["tmp_name"], $filea);
    $subfilea = substr($filea, 3);
    if (($file_name != '') && ($file_namea != '')) {
        $query = "UPDATE `tbl_fun_facts` SET `image`='" . $subfile . "',`image_ar`='" . $subfilea . "',`number`='" . $fun_number . "',`number_ar`='" . $fun_number_ar . "',`text_level_1`='" . $funtext_level_1 . "',`text_level_1_ar`='" . $funtext_level_1_ar . "',`text_level_2`='" . $funtext_level_2 . "',`text_level_2_ar`='" . $funtext_level_2_ar . "' WHERE `id`='" . $fun_id . "'";
//        $query = "UPDATE `tbl_news` SET `title`='" . $news_title . "',`title_ar`='" . $news_title_ar . "',`content`='" . $news_desc . "',`content_ar`='" . $news_desc_ar . "',`image`='" . $subfile . "',`image_ar`='" . $subfilea . "',`news_excerpt`='" . $news_excerpt . "',`news_excerpt_ar`='" . $news_excerpt . "',`type`='" . $news_or_event . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }
    else if ($file_name != '') {
        $query = "UPDATE `tbl_fun_facts` SET `image`='" . $subfile . "',`number`='" . $fun_number . "',`number_ar`='" . $fun_number_ar . "',`text_level_1`='" . $funtext_level_1 . "',`text_level_1_ar`='" . $funtext_level_1_ar . "',`text_level_2`='" . $funtext_level_2 . "',`text_level_2_ar`='" . $funtext_level_2_ar . "' WHERE `id`='" . $fun_id . "'";
//        $query = "UPDATE `tbl_news` SET `title`='" . $news_title . "',`title_ar`='" . $news_title_ar . "',`content`='" . $news_desc . "',`content_ar`='" . $news_desc_ar . "',`image`='" . $subfile . "',`news_excerpt`='" . $news_excerpt . "',`news_excerpt_ar`='" . $news_excerpt . "',`type`='" . $news_or_event . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }
    else if ($file_namea != '') {
        $query = "UPDATE `tbl_fun_facts` SET `image_ar`='" . $subfilea . "',`number`='" . $fun_number . "',`number_ar`='" . $fun_number_ar . "',`text_level_1`='" . $funtext_level_1 . "',`text_level_1_ar`='" . $funtext_level_1_ar . "',`text_level_2`='" . $funtext_level_2 . "',`text_level_2_ar`='" . $funtext_level_2_ar . "' WHERE `id`='" . $fun_id . "'";
//        $query = "UPDATE `tbl_news` SET `title`='" . $news_title . "',`title_ar`='" . $news_title_ar . "',`content`='" . $news_desc . "',`content_ar`='" . $news_desc_ar . "',`image_ar`='" . $subfilea . "',`news_excerpt`='" . $news_excerpt . "',`news_excerpt_ar`='" . $news_excerpt . "',`type`='" . $news_or_event . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }
    else if (($file_name == '') && ($file_namea == '')) {
        $query = "UPDATE `tbl_fun_facts` SET `number`='" . $fun_number . "',`number_ar`='" . $fun_number_ar . "',`text_level_1`='" . $funtext_level_1 . "',`text_level_1_ar`='" . $funtext_level_1_ar . "',`text_level_2`='" . $funtext_level_2 . "',`text_level_2_ar`='" . $funtext_level_2_ar . "' WHERE `id`='" . $fun_id . "'";
//        $query = "UPDATE `tbl_news` SET `title`='" . $news_title . "',`title_ar`='" . $news_title_ar . "',`content`='" . $news_desc . "',`content_ar`='" . $news_desc_ar . "',`news_excerpt`='" . $news_excerpt . "',`news_excerpt_ar`='" . $news_excerpt . "',`type`='" . $news_or_event . "',`date`='" . $news_date . "' WHERE `id`='" . $news_id . "'";
    }

////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Fun Facts Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../fun_facts.php");
    }
    else {
        $_SESSION["err"] = "Error Updating News";
        header("Location:../fun_facts.php");
    }
}

if ($index == 'update_middle_banner') {




    $left_text_1 = addslashes($_POST['left_text_1']);
    $left_text_2 = addslashes($_POST['left_text_2']);
    $left_link = addslashes($_POST['left_link']);
    $right_text_1 = addslashes($_POST['right_text_1']);
    $right_text_2 = addslashes($_POST['right_text_2']);
    $left_text_1_ar = addslashes($_POST['left_text_1_ar']);
    $left_text_2_ar = addslashes($_POST['left_text_2_ar']);
    $left_link_ar = addslashes($_POST['left_link_ar']);
    $right_text_1_ar = addslashes($_POST['right_text_1_ar']);
    $right_text_2_ar = addslashes($_POST['right_text_2_ar']);



    $filnm = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["banner"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["banner"]["name"]);
    $ext1 = pathinfo($image);
    $ext = $ext1['extension'];
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['banner']["tmp_name"], $file);
    $subfile = substr($file, 3);

    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_namea = basename($_FILES["banner_ar"]["name"]);
    $file_namea = str_replace(" ", "-", $file_namea);
    $imagea = $target_dir . basename($_FILES["banner_ar"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1['extension'];
    $filea = $target_dir . $filnma . $file_namea;
    move_uploaded_file($_FILES['banner_ar']["tmp_name"], $filea);
    $subfilea = substr($filea, 3);

    if (($file_name != '') && ($file_namea != '')) {
        $query = "UPDATE `tbl_middle_banner` SET `left_text_1`='" . $left_text_1 . "',`left_text_1_ar`='" . $left_text_1_ar . "',`left_text_2`='" . $left_text_2 . "',`left_text_2_ar`='" . $left_text_2_ar . "',`left_link`='" . $left_link . "',`left_link_ar`='" . $left_link_ar . "',`image`='" . $subfile . "',`image_ar`='" . $subfilea . "',`right_text_1`='" . $right_text_1 . "',`right_text_1_ar`='" . $right_text_1_ar . "',`right_text_2`='" . $right_text_2 . "',`right_text_2_ar`='" . $right_text_2_ar . "' WHERE `id`='1'";
    }
    else if ($file_name != '') {

        $query = "UPDATE `tbl_middle_banner` SET `left_text_1`='" . $left_text_1 . "',`left_text_1_ar`='" . $left_text_1_ar . "',`left_text_2`='" . $left_text_2 . "',`left_text_2_ar`='" . $left_text_2_ar . "',`left_link`='" . $left_link . "',`left_link_ar`='" . $left_link_ar . "',`image`='" . $subfile . "',`right_text_1`='" . $right_text_1 . "',`right_text_1_ar`='" . $right_text_1_ar . "',`right_text_2`='" . $right_text_2 . "',`right_text_2_ar`='" . $right_text_2_ar . "' WHERE `id`='1'";
    }
    else if ($file_namea != '') {
        $query = "UPDATE `tbl_middle_banner` SET `left_text_1`='" . $left_text_1 . "',`left_text_1_ar`='" . $left_text_1_ar . "',`left_text_2`='" . $left_text_2 . "',`left_text_2_ar`='" . $left_text_2_ar . "',`left_link`='" . $left_link . "',`left_link_ar`='" . $left_link_ar . "',`image_ar`='" . $subfilea . "',`right_text_1`='" . $right_text_1 . "',`right_text_1_ar`='" . $right_text_1_ar . "',`right_text_2`='" . $right_text_2 . "',`right_text_2_ar`='" . $right_text_2_ar . "' WHERE `id`='1'";
    }
    else if (($file_name == '') && ($file_namea == '')) {
        $query = "UPDATE `tbl_middle_banner` SET `left_text_1`='" . $left_text_1 . "',`left_text_1_ar`='" . $left_text_1_ar . "',`left_text_2`='" . $left_text_2 . "',`left_text_2_ar`='" . $left_text_2_ar . "',`left_link`='" . $left_link . "',`left_link_ar`='" . $left_link_ar . "',`right_text_1`='" . $right_text_1 . "',`right_text_1_ar`='" . $right_text_1_ar . "',`right_text_2`='" . $right_text_2 . "',`right_text_2_ar`='" . $right_text_2_ar . "' WHERE `id`='1'";
    }

////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Middle Banner Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../middle_banner.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Middle Banner ";
        header("Location:../middle_banner.php");
    }
}
if ($index == 'newsletter') {
//

    $name = $_POST['name'];
    $email = $_POST['email'];

    $newsletter_exist = get_where_cond("tbl_newsletter", "`email`='" . $email . "'");
    if ($newsletter_exist->num_rows > 0) {
        $_SESSION["err"] = "Newsletter already subscribed for this mail";
        header("Location:../../index.php");
    }
    else {

        $query = "INSERT INTO `tbl_newsletter`(`name`, `email`) VALUES ('" . $name . "','" . $email . "')";

        mail($email, 'Newsletter Subscribed', 'You have successfully subscribed our newsletter');
//exit();
        $result = execute($query);
        if ($result == 'Newsletter Subscribed Successfully') {
            $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
            $res_preview = execute($preview);
            header("Location:../../index.php");
        }
        else {
            $_SESSION["err"] = "Error Subscribing ";
            header("Location:../../index.php");
        }
    }
}
if ($index == 'newsletter_mail') {
//
    $lang = $_POST['lang'];
    $protocol = 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '');
    echo $currURL = $protocol . '://' . $_SERVER['SERVER_NAME'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));

    $newsletter_title = addslashes($_POST['newsletter_title']);

    $newsletter_description = addslashes($_POST['newsletter_description']);
    $email = $_POST['email'];

    $newsletter_title_ar = addslashes($_POST['newsletter_title_ar']);
    $newsletter_description_ar = addslashes($_POST['newsletter_description_ar']);
    $email_ar = $_POST['email_ar'];
    if ($lang == 'en') {

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: Samsotech\r\n" .
                "X-Mailer: php/" . phpversion();
        $expl_email = explode(',', $email);
        for ($count = 0; $count < sizeof($expl_email); $count++) {
//            echo $expl_email[$count];
            $unsubscribe = 'For unsubscribing our newsletter <a href=' . $currURL . '/unsubscribe_newsletter.php?mailid=' . $expl_email[$count] . ' target=_blank>Unsubscribe</a>';
            $body = '<br>Hi' . $expl_email[$count] . ',<br>' . $newsletter_description . '<br> ' . $unsubscribe;
//         exit();
            mail($expl_email[$count], $newsletter_title, $body, $headers);
        }
    }
    else if ($lang == 'ar') {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: Samsotech\r\n" .
                "X-Mailer: php/" . phpversion();
        $expl_email_ar = explode(',', $email_ar);
        for ($count_ar = 0; $count_ar < sizeof($expl_email_ar); $count_ar++) {
//            echo $expl_email[$count];
            $unsubscribe = 'For unsubscribing our newsletter <a href=' . $currURL . '/unsubscribe_newsletter_ar.php?mailid=' . $expl_email_ar[$count_ar] . ' target=_blank>Unsubscribe</a>';
            $body_ar = '<br>Hi' . $expl_email_ar[$count_ar] . ',<br>' . $newsletter_description_ar . '<br> ' . $unsubscribe;
//         exit();
            mail($expl_email_ar[$count_ar], $newsletter_title_ar, $body_ar, $headers);
        }



//        mail($email_ar, $newsletter_title_ar, $newsletter_description_ar);
    }

//echo $query;
//        $result = execute($query);
//        if ($result == 'Query Executed Successfully') {
    $_SESSION["err"] = "Newsletter Mail Send Successfully";
    header("Location:../newsletter.php");
}
if ($index == 'add_faq') {

    $language = $_POST['lang'];


    $question = addslashes($_POST['question']);
    $answer = addslashes($_POST['answer']);

    $question_ar = addslashes($_POST['question_ar']);
    $answer_ar = addslashes($_POST['answer_ar']);




    $query = "INSERT INTO `tbl_faq`(`question`, `answer`, `question_ar`, `answer_ar`) VALUES ('" . $question . "','" . $answer . "','" . $question_ar . "','" . $answer_ar . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "FAQ Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../faq_admin.php");
    }
    else {
        $_SESSION["err"] = "Error Adding FAQ";
        header("Location:../faq_admin.php");
    }
}
if ($index == 'update_faq') {

    $faq_id = $_POST['faq_id'];

    $question = addslashes($_POST['question']);
    $answer = addslashes($_POST['answer']);

    $question_ar = addslashes($_POST['question_ar']);
    $answer_ar = addslashes($_POST['answer_ar']);





    $query = "UPDATE `tbl_faq` SET `question`='" . $question . "',`answer`='" . $answer . "',`question_ar`='" . $question_ar . "',`answer_ar`='" . $answer_ar . "' WHERE `id`='" . $faq_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "FAQ Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../faq_admin.php");
    }
    else {
        $_SESSION["err"] = "Error Updating FAQ";
        header("Location:../faq_admin.php");
    }
}

if ($index == 'delete_faq') {


    $faq_id = $_POST['id'];


    $query = "DELETE FROM `tbl_faq` WHERE `id`='" . $faq_id . "'";


    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "FAQ Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../faq_admin.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting FAQ";
        header("Location:../faq_admin.php");
    }
}
if ($index == 'status_activate') {


    $id = $_POST['about_id'];


    $query = "UPDATE tbl_about SET status='1' where `about_id`='" . $id . "'";


    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Status Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        if ($id == '9') {
            header("Location:../testimonials.php");
        }
        else {
            header("Location:../about-us.php?abid=" . $id . "");
        }
    }
    else {
        $_SESSION["err"] = "Error Updating Status";
        if ($id == '9') {
            header("Location:../testimonials.php");
        }
        else {
            header("Location:../about-us.php?abid=" . $id . "");
        }
    }
}
if ($index == 'status_deactivate') {
    $id = $_POST['about_id'];
    $query = "UPDATE tbl_about SET status='0' where `about_id`='" . $id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Status Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        if ($id == '9') {
            header("Location:../testimonials.php");
        }
        else {
            header("Location:../about-us.php?abid=" . $id . "");
        }
    }
    else {
        $_SESSION["err"] = "Error Updating Status";
        if ($id == '9') {
            header("Location:../testimonials.php");
        }
        else {
            header("Location:../about-us.php?abid=" . $id . "");
        }
    }
}
if ($index == 'add_career') {

    $language = $_POST['lang'];


    $title = addslashes($_POST['title']);
    $content = addslashes($_POST['content']);

    $title_ar = addslashes($_POST['title_ar']);
    $content_ar = addslashes($_POST['content_ar']);




    $query = "INSERT INTO `tbl_career`(`title`, `content`, `title_ar`, `content_ar`) VALUES ('" . $title . "','" . $content . "','" . $title_ar . "','" . $content_ar . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Career Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../career-admin.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Career";
        header("Location:../career-admin.php");
    }
}
if ($index == 'update_career') {

    $career_id = $_POST['career_id'];

    $title = addslashes($_POST['title']);
    $content = addslashes($_POST['content']);

    $title_ar = addslashes($_POST['title_ar']);
    $content_ar = addslashes($_POST['content_ar']);





    $query = "UPDATE `tbl_career` SET `title`='" . $title . "',`content`='" . $content . "',`title_ar`='" . $$title_ar . "',`content_ar`='" . $content_ar . "' WHERE `id`='" . $career_id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Career Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../career-admin.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Career";
        header("Location:../career-admin.php");
    }
}

if ($index == 'delete_career') {


    $career_id = $_POST['id'];


    $query = "DELETE FROM `tbl_career` WHERE `id`='" . $career_id . "'";


    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Career Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../career-admin.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting FAQ";
        header("Location:../career-admin.php");
    }
}
if ($index == 'update_background') {

    $page_id = $_POST['page_id'];


    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["header_image"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["header_image"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1['extension'];
    $filea = $target_dir . $filnma . $file_name;
    move_uploaded_file($_FILES['header_image']["tmp_name"], $filea);
    $subfile = substr($filea, 3);


    if ($file_name != '') {
        $query = "UPDATE `tbl_about` SET `header_image`='" . $subfile . "' WHERE `about_id`='" . $page_id . "'";
    }
    else {
        $query = "";
    }


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
//        $_SESSION["err"] = "Career Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../background.php?id=" . $page_id . "");
    }
    else {
        $_SESSION["err"] = "Error Updating Career";
        header("Location:../background.php?id=" . $page_id . "");
    }
}
if ($index == 'about_footer') {
    $lang = $_POST['lang'];
    $page_id = $_POST['page_id'];
    if ($lang == 'en') {
        if (trim($_POST['abt_footer']) == '') {
            $_SESSION["err"] = "Enter Valid Details";
            header("Location:../about-us.php?abid=" . $page_id . "");
        }
        else {
            $abt_footer = addslashes($_POST['abt_footer']);
            $query = "Update tbl_about set  about_description='" . $abt_footer . "' where about_id='" . $page_id . "'";
        }
    }
    else if ($lang == 'ar') {
        if (trim($_POST['abt_footer_ar']) == '') {
            $_SESSION["err"] = "Enter Valid Details";
            header("Location:../about-us.php?abid=" . $page_id . "");
        }
        else {
            $abt_footer_ar = addslashes($_POST['abt_footer_ar']);


            $query = "Update tbl_about set  about_description_ar='" . $abt_footer_ar . "' where about_id='" . $page_id . "'";
        }
    }
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../about-us.php?abid=" . $page_id . "");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../about-us.php?abid=" . $page_id . "");
    }
}
if ($index == 'add_gallery_image') {

    $filnma = rand(4500, 8000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["gal_image"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $image = $target_dir . basename($_FILES["gal_image"]["name"]);
    $ext1a = pathinfo($image);
    $exta = $ext1['extension'];
    $filea = $target_dir . $filnma . $file_name;
    move_uploaded_file($_FILES['gal_image']["tmp_name"], $filea);
    $subfile = substr($filea, 3);
    echo $query = "INSERT INTO `tbl_gallery`(`image`) VALUES ('" . $subfile . "')";
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../gallery.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../gallery.php");
    }
}
if ($index == 'delete_gallery_image') {

    $id = $_POST['id'];


    $query = "DELETE FROM `tbl_gallery` WHERE `id`='" . $id . "'";


////    $query = "INSERT INTO `tbl_news`(`title`, `title_ar`, `content`, `content_ar`, `image`, `image_ar`, `news_excerpt`, `news_excerpt_ar`, `type`, `date`) VALUES ('" . $news_title . "','" . $news_title_ar . "','" . $news_desc . "','" . $news_desc_ar . "','" . $subfile . "','" . $subfilea . "','" . $news_excerpt . "','" . $news_excerpt_ar . "','".$news_or_event."','" . $news_date . "')";
//    echo $query;
//    exit();
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Image Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../gallery.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting ";
        header("Location:../gallery.php");
    }
}



##################################################################### CSR ################################################
############################ ADD PRODUCTS #########################################
if ($index == 'csr_add') {
//    exit();
//    $lang = $_POST['lang'];
    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["prod_image"]["name"]);
    if ($file_name != '') {
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["prod_image"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['prod_image']["tmp_name"], $file);
        echo $subfile = substr($file, 3);
    }
    else {
        $subfile = '';
    }

    $file_name_s = basename($_FILES["prod_image_ar"]["name"]);
    if ($file_name_s != '') {
        $file_name_s = str_replace(" ", "-", $file_name_s);
        $sliderimg_s = $target_dir . basename($_FILES["prod_image_ar"]["name"]);
        $file_s = $target_dir . $filnm . $file_name_s;
        move_uploaded_file($_FILES['prod_image_ar']["tmp_name"], $file_s);
        echo $subfile_s = substr($file_s, 3);
    }
    else {
        $subfile_s = '';
    }
    $prod_main_title = addslashes($_POST['prod_main_title']);
    $prod_main_description = addslashes($_POST['prod_main_description']);
    $prod_excerpt = addslashes($_POST['prod_excerpt']);


    $prod_main_title_ar = addslashes($_POST['prod_main_title_ar']);
    $prod_main_description_ar = addslashes($_POST['prod_main_description_ar']);
    $prod_excerpt_ar = addslashes($_POST['prod_excerpt_ar']);

    echo $query = "INSERT INTO `tbl_csr`(`main_title`, `main_title_ar`, `main_description`, `main_description_ar`, `image`, `image_ar`) VALUES ('" . $prod_main_title . "','" . $prod_main_title_ar . "','" . $prod_main_description . "','" . $prod_main_description_ar . "','" . $subfile . "','" . $subfile_s . "')";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../csr.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../csr.php");
    }
}
############################ ADD PRODUCTS END #########################################
############################ EDIT PRODUCTS #########################################
if ($index == 'csr_edit') {
    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["prod_image"]["name"]);
    if ($file_name != '') {
        $file_name = str_replace(" ", "-", $file_name);
        $sliderimg = $target_dir . basename($_FILES["prod_image"]["name"]);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['prod_image']["tmp_name"], $file);
        echo $subfile = substr($file, 3);
    }
    else {
        $subfile = '';
    }

    $file_name_s = basename($_FILES["prod_image_ar"]["name"]);
    if ($file_name_s != '') {
        $file_name_s = str_replace(" ", "-", $file_name_s);
        $sliderimg_s = $target_dir . basename($_FILES["prod_image_ar"]["name"]);
        $file_s = $target_dir . $filnm . $file_name_s;
        move_uploaded_file($_FILES['prod_image_ar']["tmp_name"], $file_s);
        echo $subfile_s = substr($file_s, 3);
    }
    else {
        $subfile_s = '';
    }

    $csr_id = $_POST['csr_id'];
    $prod_main_title = addslashes($_POST['prod_main_title']);
    $prod_main_description = addslashes($_POST['prod_main_description']);

    $prod_main_title_ar = addslashes($_POST['prod_main_title_ar']);
    $prod_main_description_ar = addslashes($_POST['prod_main_description_ar']);

    if (($file_name != '') && ($file_name_s != '')) {
        $query = "UPDATE `tbl_csr` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`image`='" . $subfile . "',`image_ar`='" . $subfile_s . "' WHERE csr_id='" . $csr_id . "'";
    }
    else if ($file_name != '') {
        $query = "UPDATE `tbl_csr` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`image`='" . $subfile . "' WHERE csr_id='" . $csr_id . "'";
    }
    else if ($file_name_s != '') {
        $query = "UPDATE `tbl_csr` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "',`image_ar`='" . $subfile_s . "' WHERE csr_id='" . $csr_id . "'";
    }
    else if (($file_name == '') && ($file_name_s == '')) {
        $query = "UPDATE `tbl_csr` SET `main_title`='" . $prod_main_title . "',`main_title_ar`='" . $prod_main_title_ar . "',`main_description`='" . $prod_main_description . "',`main_description_ar`='" . $prod_main_description_ar . "' WHERE csr_id='" . $csr_id . "'";
    }
    echo $query;
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../csr.php");
    }
    else {
        $_SESSION["err"] = "Error updating ";
        header("Location:../csr.php");
    }
}


if ($index == 'delete_csr') {
    $csr_id = $_POST['csr_id'];
    echo $query = "DELETE FROM `tbl_csr` WHERE `csr_id`='" . $csr_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Deleted Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../csr.php");
    }
    else {
        $_SESSION["err"] = "Error Deleting Product";
        header("Location:../csr.php");
    }
}
############################ EDIT PRODUCTS #########################################

if ($index == 'add_csr_slider') {
    $main_title = $_POST['main_title'];
    $sub_title = $_POST['sub_title'];
    $main_title_ar = $_POST['main_title_ar'];
    $sub_title_ar = $_POST['sub_title_ar'];
    $position = $_POST['position'];

    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $file_name = str_replace(" ", "-", $file_name);
    $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
    $file = $target_dir . $filnm . $file_name;
    move_uploaded_file($_FILES['file']["tmp_name"], $file);
    $subfile = substr($file, 3);



    $query = "INSERT INTO `tbl_csr_slider`(`main_title`, `main_title_ar`, `sub_title`, `sub_title_ar`,`position`, `image`) VALUES ('" . $main_title . "', '" . $main_title_ar . "', '" . $sub_title . "', '" . $sub_title_ar . "','" . $position . "', '" . $subfile . "')";
    echo $query;

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Slider Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../csr_slider.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Slider";
        header("Location:../csr_slider.php");
    }
}
############################## ADD SLIDER END #############################

if ($index == 'edit_csr_slider') {
//

    $main_title = $_POST['main_title'];
    $sub_title = $_POST['sub_title'];
    $id = $_POST['slider_id'];
    $product_id = $_POST['product_id'];
    $main_title_ar = $_POST['main_title_ar'];
    $sub_title_ar = $_POST['sub_title_ar'];
    $position = $_POST['position'];
    $order = $_POST['order'];

    $filnm = rand(250000, 500000);
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["file"]["name"]);

    if ($file_name != '') {
        $sliderimg = $target_dir . basename($_FILES["file"]["name"]);
        $file_name = str_replace(" ", "-", $file_name);
        $file = $target_dir . $filnm . $file_name;
        move_uploaded_file($_FILES['file']["tmp_name"], $file);
        $subfile = substr($file, 3);
        $query = "UPDATE `tbl_csr_slider` set main_title = '" . $main_title . "',`main_title_ar`='" . $main_title_ar . "', sub_title = '" . $sub_title . "',`sub_title_ar`='" . $sub_title_ar . "', image = '" . $subfile . "',`position`='" . $position . "',`slider_order`='" . $order . "' where `id` = '" . $id . "'";
    }
    else {
        $query = "UPDATE `tbl_csr_slider` set main_title = '" . $main_title . "',`main_title_ar`='" . $main_title_ar . "', sub_title = '" . $sub_title . "',`sub_title_ar`='" . $sub_title_ar . "',`position`='" . $position . "',`slider_order`='" . $order . "' where `id` = '" . $id . "'";
    }

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Slider Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../csr_slider.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../csr_slider.php");
    }
}

if ($index == 'delete_csr_slider') {
//
    $id = $_POST['slider_id'];
    $product_id = $_POST['product_id'];
    $query = "DELETE from `tbl_csr_slider`  where `id` = '" . $id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Slider Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../csr_slider.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../csr_slider.php");
    }
}

if ($index == 'request_demo') {


    $get_con_email = get_where_cond("tbl_contact_main", "id=1");
    $res_con_email = $get_con_email->fetch_assoc();
    $recipient_email = $res_con_email['email'];
    $product = $_POST['product'];
    $from_email = $res_con_email['semail'];
    $sender_name = addslashes($_POST['name']);
    $sender_email = addslashes($_POST['email']);
    $sender_phone = addslashes($_POST['phone']);
    $company = $_POST['company'];
    $message = $_POST['message'];
    $subject = "Demo Request";


    $sender_name = filter_var($sender_name, FILTER_SANITIZE_STRING); //capture sender name
    $sender_email = filter_var($sender_email, FILTER_SANITIZE_STRING); //capture sender email
    $sender_phone = filter_var($sender_phone, FILTER_SANITIZE_STRING);
    $subject = filter_var($subject, FILTER_SANITIZE_STRING);
    $company = filter_var($company, FILTER_SANITIZE_STRING);
    $message = filter_var($message, FILTER_SANITIZE_STRING); //capture message
    $product = filter_var($product, FILTER_SANITIZE_STRING);
    //
    $boundary = md5("sanwebe.com");
    //construct a message body to be sent to recipient
    $message_body = "Message from $sender_name \n";
    $message_body .= "Demo Request: $product\n";
    $message_body .= "Company $company\n";
    $message_body .= "------------------------------\n";
    $message_body .= "$message\n";
    $message_body .= "------------------------------\n";
    $message_body .= "$sender_name\n";
    $message_body .= "$sender_phone\n";
    $message_body .= "$sender_email\n";
    $message_body;

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From:" . $from_email . "\r\n";
    $headers .= "Reply-To: " . $sender_email . "" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

    //message text
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($message_body));

    $sentMail = mail($recipient_email, $subject, $body, $headers);
    if ($sentMail) { //output success or failure messages
        header("Location:../../thank-you.php?thid=1");
        //print 'Thank you for your email';
        exit;
    }
    else {
        header("Location:../../thank-you.php");
        print 'Could not send mail!Please check your PHP mail configuration.';
        exit;
    }
}

if ($index == 'pro_order') {

    $products_id = $_POST['products_id'];
    $order_id = $_POST['order_id'];
    $query = "Update tbl_products set pro_order='" . $order_id . "' where products_id='" . $products_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../about-us.php?abid=8");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../about-us.php?abid=8");
    }
}

if ($index == 'sol_order') {

    $solution_id = $_POST['solution_id'];
    $order_id = $_POST['order_id'];
    $query = "Update tbl_solutions set sol_order='" . $order_id . "' where solution_id='" . $solution_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../about-us.php?abid=7");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../about-us.php?abid=7");
    }
}
if ($index == 'add_tag') {

    $tag_name = $_POST['tag_name'];
    $query = "INSERT INTO `tbl_posttags`(`tag_name`) VALUES ('" . $tag_name . "')";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Tag Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../posttags.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Tag";
        header("Location:../posttags.php");
    }
}
if ($index == 'edit_tag') {

    $tag_id = $_POST['tag_id'];
    $tag_name = $_POST['tag_name'];
    $query = "Update tbl_posttags set tag_name='" . $tag_name . "' where tag_id='" . $tag_id . "'";

    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Tag Added Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../posttags.php");
    }
    else {
        $_SESSION["err"] = "Error Adding Tag";
        header("Location:../posttags.php");
    }
}
if ($index == 'delete_tag') {
//
    $tag_id = $_POST['tag_id'];
    $query = "DELETE from `tbl_posttags` where `tag_id` = '" . $tag_id . "'";
    $result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Product Slider Updated Successfully";
        $preview = "Update`tbl_publish_status` set `status`='1' where `id`='1'";
        $res_preview = execute($preview);
        header("Location:../posttags.php");
    }
    else {
        $_SESSION["err"] = "Error Updating Slider";
        header("Location:../posttags.php");
    }
}

if ($index == 'contact_email') {
    $protocol = 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '');
    $currURL = $protocol . '://' . $_SERVER['SERVER_NAME'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
//    if (isset($_POST['g-recaptcha-response'])) {
//        $captcha = $_POST['g-recaptcha-response'];
//    }
//    if (!$captcha) {
//        header("Location:../../captcha-error.html");
//        exit;
//    }
    $secretKey = "6LfmcNEaAAAAAMA12JVFPlaBhTD9a2w8_n0_bWyP";
    $ip = $_SERVER['REMOTE_ADDR'];

    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response, true);
//    if ($responseKeys["success"]) {
        $name = addslashes($_POST['name']);
        $designation = addslashes($_POST['designation']);
        $email = addslashes($_POST['email']);
        $phone = addslashes($_POST['phone']);
        $company = addslashes($_POST['company']);
        $subject = addslashes($_POST['subject']);
        $messages = addslashes($_POST['message']);

        $get_form_email = get_where_cond("tbl_contact_main", "id=1");
        $res_form_email = $get_form_email->fetch_assoc();
        //$recipient_email = $res_form_email['email']; //recepient
        //
        $recipient_email = "sabhilash@outlook.com";
        $from_email = "noreply_website@samsotech-id.com"; //from email using site domain.
        //$subject = "Demo Request";

        $message = '<html><body>';
        $message .= '<h4>New General Enquiry:</h4>';
        $message .= 'Name of the Contact Person: ' . $name . '<br>';
        $message .= 'Designation: ' . $designation . '<br>';
        $message .= 'Email ID: ' . $email . '<br>';
        $message .= 'Company : ' . $company . '<br>';
        $message .= 'Message:<br>';
        $message .= '---------------------------------------------------<br>' . $messages . '<br>';
        $message .= '---------------------------------------------------<br><br>';
        $message .= 'Note: This is an auto-generated email and please do not reply.<br><br>';
        $message .= '<span style="font-size:11px;">DISCLAIMER: This email (including any attachments) is intended for the sole use of the intended recipient/s and may contain material that is CONFIDENTIAL AND PRIVATE COMPANY INFORMATION. Any review or reliance by others or copying or distribution or forwarding of any or all of the contents in this message is STRICTLY PROHIBITED. The opinions expressed are those of the sender, and do not necessarily reflect those of the Company. If you are not the intended recipient, please contact the sender by email and delete all copies; your cooperation in this regard is appreciated.</span>';
        $message .= '</body></html>';

        $to = $recipient_email;
        $subject = $subject;
        $headers = "From: " . strip_tags($from_email) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($email) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        //attachments
        if (mail($to, $subject, $message, $headers)) {
            header("Location:../../thank-you.html");
        }
        else {
            header("Location:../../error.html");
        }
//    }
//    else {
//        header("Location:../../captcha-error.html");
//    }
}