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

                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12 mt-2 border">
                                <div class="row">
                                    <div class="col-md-6 float-left">
                                        <h4 class="p-0 m-0" style="line-height:35px;">Add Post Tags</h4>
                                    </div>
                                    <div class = "col-md-6 float-right text-right">
                                        <a href="news.php" class="btn btn-success">Back</a>
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
                            <div class = "col-md-12 mt-4">
                                <div class = "row">
                                    <div class = "col-md-6 border p-2 bg-dark text-white rounded">
                                        <?php
                                        if ($_GET['edit'] == "on") {
                                            $get_tag = get_where_cond("tbl_posttags", "tag_id='" . $_GET['id'] . "'");
                                            $res_tag = $get_tag->fetch_assoc();
                                            ?>
                                            <form method = "post" action = "includes/actions.php" enctype = "multipart/form-data">
                                                <h5>English</h5>
                                                <div class = "form-group">
                                                    <label for = "priv">Tag name</label>
                                                    <input type="text" class="form-control" value="<?php echo $res_tag['tag_name']; ?>" id="field-1" name="tag_name" >
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="INDEX" value="edit_tag"/>
                                                    <input type="hidden" name="tag_id" value="<?php echo $res_tag['tag_id']; ?>"/>
                                                    <input type="submit" class="btn btn-danger float-right" value="Edit Tag">
                                                </div>
                                            </form>
                                            <?php
                                        } else {
                                            ?>
                                            <form method = "post" action = "includes/actions.php" enctype = "multipart/form-data">
                                                <h5>English</h5>
                                                <div class = "form-group">
                                                    <label for = "priv">Tag name</label>
                                                    <input type="text" class="form-control" id="field-1" name="tag_name" >
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="INDEX" value="add_tag"/>
                                                    <input type="submit" class="btn btn-danger float-right" value="Add Tag">
                                                </div>
                                            </form>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class = "col-md-4 border bg-dark text-white rounded">
                                        <h5>Added tags</h5>
                                        <?php
                                        $get_all_tags = get_alldata("tbl_posttags");
                                        while ($res_all_tags = $get_all_tags->fetch_assoc()) {
                                            ?>
                                            <div class="row border rounded mb-1 ml-1 mr-1 p-1">
                                                <div class="col-md-7">
                                                    <?php echo $res_all_tags['tag_name']; ?>
                                                </div>
                                                <div class="col-md-5 text-right">
                                                    <a class="btn btn-warning btn-sm" href="posttags.php?edit=on&id=<?php echo $res_all_tags['tag_id']; ?>">Edit</a>
                                                    <form method="post" action="includes/actions.php" enctype="multipart/form-data" class="float-right">
                                                        <input type="hidden" name="INDEX" value="delete_tag"/>
                                                        <input type="hidden" name="tag_id" value="<?php echo $res_all_tags['tag_id']; ?>"/>
                                                        <input type="submit" class="btn btn-danger ml-1" onclick="return confirm('Are you sure you want to Delete?');" value="Delete" style="font-size: 12px;"/>
                                                    </form>
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
    </div>

    <?php
    include './includes/footer.php';
}
?>