<?php
session_start();
$uname = $_SESSION["uname"];
include './includes/header.php';
if ($uname == null) {
    header("Location:index.php");
}
else {
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
                                        <h4 class="p-0 m-0" style="line-height:35px;">All News </h4>
                                    </div>

                                    <div class = "col-md-6 float-right text-right">
                                        <form method = "post" action = "add_news.php" enctype = "multipart/form-data">
                                            <input type="submit" class="btn btn-success" value="Add News">
                                        </form>
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
                            <div class="col-md-6 mt-3">
                                <div class="row">
                                    <?php
                                    $get_posts = get_alldata("tbl_posts");
                                    //if ($get_solutions->num_rows > 0) {
                                    while ($res_blogs = $get_posts->fetch_assoc()) {
                                        ?>
                                        <div class="col-md-12">

                                            <div class="row border rounded mb-1 p-1">
                                                <div class="col-md-3 logo">
                                                    <img src="<?php echo $res_blogs['image']; ?>"/>
                                                </div>
                                                <div class="col-md-9 mt-2">
                                                    <h5  style="line-height: 25px;"><?php echo $res_blogs['title']; ?></h5>
                                                    <?php echo $res_blogs['blog_excerpt']; ?>
                                                </div>

                                                <div class="col-md-8 text-right">
                                                    <form method="post" action="edit_news.php" enctype="multipart/form-data">
                                                        <input type="hidden" name="edit_news" value="edit"/>
                                                        <input type="hidden" name="id" value="<?php echo $res_blogs['id']; ?>"/>
                                                        <input type="submit" class="btn btn-danger float-right" value="Edit" style="font-size: 12px;"/>
                                                    </form>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                                        <input type="hidden" name="INDEX" value="delete_news"/>
                                                        <input type="hidden" name="id" value="<?php echo $res_blogs['id']; ?>"/>
                                                        <input type="submit" class="btn btn-danger float-right" value="Delete" onclick="return confirm('Are you sure you want to Delete?');" style="font-size: 12px;"/>
                                                    </form>
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
                </div>
            </div>




        </div>
    </div>
    <?php
    include './includes/footer.php';
}
?>