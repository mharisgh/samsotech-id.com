<?php
session_start();
$uname = $_SESSION["uname"];
$SESSION['mode'] = "web";
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
                        <h4 class="text-center">Website Preview</h4>
                        <div class="card" style="height:500px;width:100%;">
                            <iframe src="https://samsotech-id.com/" style="height:500px;width:100%;"></iframe>
                            <!--<marquee><h4>Website Preview Goes Here</h4></marquee>-->
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