<?php
session_start();
$uname = $_SESSION["uname"];
include './includes/header.php';
if ($uname == null) {
    header("Location:index.php");
}
else {
    ?>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <div class="container-fluid">
        <div class="row">
            <?php
            include './includes/left-menu.php';
            ?>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 border p-2  rounded ">
                        <h4 class="text-center">Contact Main Content</h4>
                        <?php
                        session_start();
                        if ($_SESSION["err"]) {
                            ?><h3 style="color: maroon;text-align: center;"><?php echo $_SESSION["err"]; ?> </h3> <?php
                            unset($_SESSION['err']);
                        }
                        ?>

                        <?php
                        $get_contact_details = get_where_cond("tbl_contact_main", "id='1'");
                        $res_contact_details = $get_contact_details->fetch_assoc();
                        ?>
                        <div class="col-md-12 card p-1 mb-2">
                            <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="usertype">Phone 1</label>
                                                    <input type="text" onkeypress="return isNumber(event);" maxlength="15"  class="form-control"  id="phone" placeholder="Phone" name="phone" value="<?php echo $res_contact_details['phno']; ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="usertype">Phone 2</label>
                                                    <input type="text" onkeypress="return isNumber(event);" maxlength="15"  class="form-control"  id="phone" placeholder="Phone" name="phone2" value="<?php echo $res_contact_details['phno2']; ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="usertype">Contact Email</label>
                                                    <input type="email" maxlength="80" required="" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $res_contact_details['email']; ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="usertype">Schedule a Demo Email</label>
                                                    <input type="email" maxlength="80" required="" class="form-control" id="email" placeholder="Schedule a demo Email" name="semail" value="<?php echo $res_contact_details['semail']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-7">
                                                </div>
                                                <div class="col-md-5 text-right">
                                                    <input type="hidden" name="INDEX" value="update_contact_header"/>
                                                    <input type="submit" value="Update Contact Details" class="btn btn-danger">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="col-md-5 ">





                    </div>
                </div>
            </div>


        </div>
    </div>


    <?php
    include './includes/footer.php';
}
?>