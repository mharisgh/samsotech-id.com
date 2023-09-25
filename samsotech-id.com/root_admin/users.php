<?php
session_start();
$uname = $_SESSION["uname"];
include './includes/header.php';
if ($uname == null) {
    header("Location:index.php");
} else {
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
            include './includes/left-menu.php';
            ?>
            <div class="col-md-12">
                <div class="row">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mt-2 border">
                                <div class="row">
                                    <div class="col-md-6 float-left">
                                        <h4 class="p-0 m-0" style="line-height:35px;">Users</h4>
                                    </div>
                                    <div class="col-md-6 float-right text-right">
                                    </div>
                                </div>
                            </div>
                              <?php
                            session_start();
                            if ($_SESSION["err"]) {
                                ?><h3 style="color: maroon;text-align: center;"><?php echo $_SESSION["err"]; ?> </h3> <?php
                                unset($_SESSION['err']);
                            }
                            ?>
                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <div class="col-md-4 border p-2 bg-dark text-white rounded">
                                        <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="usertype">User Role</label>
                                                        <select class="form-control" id="usertype" name="usertype">
                                                            <option>--User Type--</option>
                                                            <?php
                                                            $uty = get_where_cond("tbl_usertype", "usertype!='SUPER ADMIN'");
                                                            if ($uty->num_rows > 0) {
                                                                while ($rowuty = $uty->fetch_assoc()) {
                                                                    ?>
                                                                    <option value="<?php echo $rowuty['usertype']; ?>"><?php echo $rowuty['usertype']; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="title" class="control-label">Name</label>
                                                        <input type="text" class="form-control"  id="field-1" placeholder="Name" name="name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="title" class="control-label">Mobile</label>
                                                        <input type="text" class="form-control" id="field-1" maxlength="12" placeholder="Mobile" onkeypress="return isNumber(event);" name="mob">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="title" class="control-label">Email</label>
                                                        <input type="email" class="form-control" id="field-1" maxlength="40" placeholder="Email" name="email">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="title" class="control-label">Password</label>
                                                        <input type="password" class="form-control" id="password" maxlength="30" placeholder="Password" name="pswd">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="title" class="control-label">Confirm Password</label>
                                                        <input type="password" class="form-control" id="cpassword" maxlength="30" onchange="return Validate()" placeholder="Confirm-Password" name="cpswd">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="hidden" name="INDEX" value="add_user"/>
                                                        <input type="submit" class="btn btn-danger float-right" value="Add User">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-1">&nbsp;</div>
                                    <div class="col-md-7 p-2">
                                        <?php
                                        $user_get = get_where_cond("tbl_users", "Usertype!='SUPER ADMIN'");
                                        while ($user_list = $user_get->fetch_assoc()) {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 border rounded mb-1">
                                                    <h5 class="w-100 mb-2"><?php echo $user_list['Name']; ?> (<?php echo $user_list['Usertype']; ?>)</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <i class="fa fa-phone" aria-hidden="true"></i> <span><?php echo $user_list['Phone']; ?></span>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <i class="fa fa-envelope" aria-hidden="true"></i> <span><?php echo $user_list['Username']; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <div class="row">
                                                                <?php
                                                                $status = $user_list['Status'];
                                                                if ($status == 0) {
                                                                    ?>
                                                                    <div class="col-md-6">
                                                                        <form method="post" action="includes/actions.php">
                                                                            <input type="hidden" name="user_id" value="<?php echo $user_list['user_id']; ?>">
                                                                            <input type="hidden" name="INDEX" value="approve_user"/>
                                                                            <input type="submit" name="sub-btn" class="btn btn-success float-right" value="Approve">
                                                                        </form>
                                                                    </div>
                                                                <?php } else if ($status == 1) { ?>
                                                                    <div class="col-md-6">
                                                                        <form method="post" action="includes/actions.php">
                                                                            <input type="hidden" name="user_id"  value="<?php echo $user_list['user_id']; ?>">
                                                                            <input type="hidden" name="INDEX" value="disapprove_user"/>
                                                                            <input type="submit" name="sub-btn" class="btn btn-outline-success float-right" onclick="return confirm('Are you sure you want to Disapprove?');" value="Disapprove">
                                                                        </form>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if ($settingsh == 'YES') {
                                                                    ?>
                                                                    <div class="col-md-6">
                                                                        <form method="post" action="includes/actions.php">
                                                                            <input type="hidden" name="user_id" value="<?php echo $user_list['user_id']; ?>">
                                                                            <input type="hidden" name="INDEX" value="delete_user"/>
                                                                            <input type="submit" onclick="return confirm('Are you sure you want to Delete?');" name="sub-btn" class="float-right btn btn-danger ml-1" value="Delete User">
                                                                        </form>
                                                                    </div>


                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>


                        </div>


                        <div class="col-md-12">
                            <div class="row">

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
    include './includes/footer.php';
}
?>