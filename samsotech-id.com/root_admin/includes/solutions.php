<?php
session_start();
$uname = $_SESSION["uname"];
include './includes/header.php';
if ($uname == null) {
    header("Location:index.php");
} else {
    $get_solutions = get_where_cond("tbl_solutions", "about_id=" . $about_page_id);
    $res_solutions = $get_about_details->fetch_assoc();
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
                                        <h4 class="p-0 m-0" style="line-height:35px;">Update | <?php echo $res_about_details['about_title']; ?> </h4>
                                    </div>
                                    <div class = "col-md-6 float-right text-right">
                                        <?php
                                        if ($about_page_id == 6) {
                                            ?>
                                            <form method = "post" action = "wcs-points.php" enctype = "multipart/form-data">
                                                <input type="submit" class="btn btn-success" value="Add Points">
                                            </form>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class = "col-md-12 mt-4">
                                <div class = "row">
                                    <div class = "col-md-6 border p-2 bg-dark text-white rounded">
                                        <h5>English</h5>
                                        <form method = "post" action = "includes/actions.php" enctype = "multipart/form-data">
                                            <div class = "form-group">
                                                <label for = "usertype">Page Title</label>
                                                <input type = "text" required = "" class = "form-control" id = "field-1" name = "about_title" value = "<?php echo $res_about_details['about_title']; ?>">
                                            </div>
                                            <div class = "form-group">
                                                <label for = "priv">Content</label>
                                                <?php
                                                if ($about_page_id == 5) {
                                                    ?>
                                                    <input type = "text" class = "form-control" id = "field-1" name = "about_description" value = "<?php echo $res_about_details['about_description']; ?>">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <textarea class = "ckeditor form-control" cols = "80" id = "editor1" name = "about_description" rows = "10"><?php echo $res_about_details['about_description'];
                                                    ?></textarea>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($about_page_id == 1) {
                                                ?>
                                                <div class="form-group">
                                                    <label for="priv">Excerpt (Short Description)</label>
                                                    <input type="text" class="form-control" id="field-1" name="about_excerpt" value="<?php echo $res_about_details['about_excerpt']; ?>">
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="form-group">
                                                <input type="hidden" name="INDEX" value="about_update"/>
                                                <input type="hidden" name="page_id" value="<?php echo $about_page_id; ?>"/>
                                                <input type="hidden" name="lang" value="en"/>
                                                <input type="submit" class="btn btn-danger float-right" value="Update English Content">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-6 border p-2 bg-dark text-white rounded">
                                        <h5>Arabic</h5>
                                        <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="usertype" class="w-100 text-right">Page Title</label>
                                                <input type="text" required="" class="form-control" id="field-1" dir="rtl" name="about_title_ar" value="<?php echo $res_about_details['about_title_ar']; ?>">
                                            </div>
                                            <div class = "form-group">
                                                <label for = "priv">Content</label>
                                                <?php
                                                if ($about_page_id == 5) {
                                                    ?>
                                                    <input type = "text" class = "form-control" id = "field-1" name = "about_description_ar" value = "<?php echo $res_about_details['about_description_ar']; ?>">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <textarea class = "ckeditor form-control" cols = "80" id = "editor2" name = "about_description_ar" rows = "10"><?php echo $res_about_details['about_description_ar'];
                                                    ?></textarea>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($about_page_id == 1) {
                                                ?>
                                                <div class="form-group">
                                                    <label for="priv">Excerpt (Short Description)</label>
                                                    <input type="text" class="form-control" id="field-1" dir="rtl" name="about_excerpt_ar" value="<?php echo $res_about_details['about_excerpt_ar']; ?>">
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <script>
                                                CKEDITOR.replace('editor2', {
                                                    language: 'ar'
                                                });
                                            </script>
                                            <div class="form-group">
                                                <input type="hidden" name="INDEX" value="about_update"/>
                                                <input type="hidden" name="page_id" value="<?php echo $about_page_id; ?>"/>
                                                <input type="hidden" name="lang" value="ar"/>
                                                <input type="submit" class="btn btn-danger float-right" value="Update Arabic Content">
                                            </div>
                                        </form>
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