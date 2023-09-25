<?php
session_start();
$uname = $_SESSION["uname"];
include './includes/header.php';
if ($uname == null) {
    header("Location:index.php");
}
else {
    $newsid = $_POST['id'];
    $get_news = get_where_cond("tbl_posts", "id=" . $newsid);
    $row_news = $get_news->fetch_assoc();
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
                                        <h4 class="p-0 m-0" style="line-height:35px;">Edit News</h4>
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
                                <form method = "post" action = "includes/actions.php" enctype = "multipart/form-data">
                                    <div class = "row">

                                        <div class = "col-md-6 border p-2 bg-dark text-white rounded">
                                            <h5>English</h5>

                                            <div class = "form-group">
                                                <label for = "usertype">News Title</label>
                                                <input type = "text" required = "" value="<?php echo $row_news['title']; ?>" class = "form-control" id = "field-1" name = "news_title" value = "">
                                            </div>
                                            <div class = "form-group">
                                                <label for = "priv">Excerpt (Short Description)</label>
                                                <input type="text" maxlength="400" class="form-control" id="field-1" name="news_excerpt" value="<?php echo $row_news['news_excerpt']; ?>" >
                                                <span>Maximum 400 charecters</span>
                                            </div>
                                            <!-- Initialize the plugin: -->
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    $('#example-getting-started').multiselect();
                                                });
                                            </script>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for = "priv">Select tags</label>
                                                        <select id="example-getting-started" name="tags[]" multiple="multiple" class="form-control">
                                                            <?php
                                                            $get_tagname = get_alldata("tbl_posttags");
                                                            while ($res_tagname = $get_tagname->fetch_assoc()) {
                                                                $sel_tag = $row_news['tags'];
                                                                print_r($one_tag = explode(",", $sel_tag));
                                                                ?>
                                                                <option value="<?php echo $res_tagname['tag_id']; ?>" <?php
                                                                foreach ($one_tag as $stag) {
                                                                    if ($stag == $res_tagname['tag_id']) {
                                                                        ?> selected=""
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>  ><?php echo $res_tagname['tag_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for = "priv">Date</label>
                                                        <?php echo $date = $row_news['date']; ?>
                                                        <input class="form-control" type="date" name="date" value="<?php echo $date; ?>"/>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class = "form-group">
                                                <label for = "priv">Content</label>
                                                <textarea class = "ckeditor form-control" cols = "80" id = "editor1" name = "news_description" rows = "10"><?php echo $row_news['content']; ?></textarea>
                                            </div>


                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <img src="<?php echo $row_news['image']; ?>" style="width:100%;">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label for="usertype">Banner Image</label>
                                                        <input type="file" accept="image/*" name="news" id="file" class="form-control required" value="" >
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="INDEX" value="update_news"/>
                                                <input type="hidden" value="<?php echo $newsid; ?>" name="news_id">

                                                <input type="submit" class="btn btn-danger float-right" value="Update News">
                                            </div>

                                        </div>


                                    </div>
                                </form>
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