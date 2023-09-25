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
                                        <h4 class="p-0 m-0" style="line-height:35px;">Change Password</h4>
                                    </div>
                                    <div class="col-md-6 float-right text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <div class="col-md-4 border p-2 bg-dark text-white rounded">
                                        <?php
                                        include 'includes/encryption_dynamicyards_unknowncode.php';
                                        session_start();
                                        if ($_SESSION["err"]) {
                                            ?><h3 style="color: maroon;text-align: center;"><?php echo $_SESSION["err"]; ?> </h3> <?php
                                            unset($_SESSION['err']);
                                        }
                                        $user_id = $_SESSION['UID'];
                                        $result = get_where_cond("tbl_users", "user_id='" . $user_id . "'");
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $curpswd = $row['Password'];
                                            $pass = Decrypt_dynamicyards($curpswd);
                                            ?>
                                            <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="title" class="control-label">Current Password</label>
                                                            <input class="form-control" type="hidden" id="oldpswd" name="oldpswd" value="<?php echo $pass; ?>">
                                                            <input type="password" maxlength="30" required="" class="form-control" onchange="return Validatecur()" id="curpswd" placeholder="" name="curpswd">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="title" class="control-label">New Password</label>
                                                            <input type="password" maxlength="30" class="form-control" required="" id="password" placeholder="" name="newpswd">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="title" class="control-label">Confirm Password</label>
                                                            <input type="password" maxlength="30" required="" class="form-control" onchange="return Validate()" id="cpassword" placeholder="" name="cpswd">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="hidden" name="INDEX" value="change_password"/>
                                                            <input type="submit" class="btn btn-danger float-right" value="Change Password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-1">&nbsp;</div>
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
    <script>
        function Validate() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("cpassword").value;
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
        function Validatecur() {
            var password = document.getElementById("oldpswd").value;
            var confirmPassword = document.getElementById("curpswd").value;
            if (password != confirmPassword) {
                alert("Current Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
    <?php
    include './includes/footer.php';
}
?>