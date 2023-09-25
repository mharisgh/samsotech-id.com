<?php
session_start();
$uname = $_SESSION["uname"];
include './includes/header.php';
if ($uname == null) {
    header("Location:index.php");
} else {
//     print_r($_POST);
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
                                        <h4 class="p-0 m-0" style="line-height:35px;">Add Tags</h4>
                                    </div>
                                    <div class = "col-md-6 float-right text-right">

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
                             <?php
                        if (isset($_POST['edit_tag'])) {
                            $products_id = $_POST['products_id'];
                            $get_products = get_where_cond("tbl_products", "products_id=" . $products_id);
                            $res_products = $get_products->fetch_assoc();
                          $tags=$res_products['tags'];
                           $expl_tags= explode(',', $tags)
                            ?>
                            <form method = "post" action = "includes/actions.php" enctype = "multipart/form-data">
                                <div class = "col-md-12 mt-4">
                                    <div class = "row">
                                        <div class="form-group tag_select w-100">
                                            <?php
                                            $ct = 1;
                                            $tag_item = get_alldata_order("tbl_solutions", "main_title ASC");
                                            if ($tag_item->num_rows > 0) {
                                                while ($row = $tag_item->fetch_assoc()) { 
                                                    foreach ($expl_tags as $tag) {
//                                    echo '<br>'.$tag;
//                                    echo $row['tag_id'].'<br>';
                                    if (trim($tag) == trim($row['solution_id'])) {
                                        $class = "checked";
                                        $value = $tag;
                                        break;
                                    } else {
                                        $class = "";
                                        $value = 0;
                                    }
                                }
                                                    
                                                    ?>
                                                   
                                                    <input type="checkbox" name="tags[]" value="<?php echo $row['solution_id']; ?>" id="tags<?php echo $ct; ?>" class="btn" <?php if ($class == "checked") { ?> checked="checked"<?php } ?> >
                                                    <label for="tags<?php echo $ct; ?>"><?php echo $row['main_title']; ?></label>
                                                    <?php
                                                    
                                                    $ct++;
                                                }
                                            }
                                            ?>

                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="INDEX" value="add_tags"/>
                                            <input type="hidden" name="products_id" value="<?php echo $_POST['products_id']; ?>"/>
                                            <input type="submit" class="btn btn-danger float-right" value="Add Tags">
                                        </div>

                                    </div>
                                </div>
                            </form>
                        <?php } else { ?> 
                              <form method = "post" action = "includes/actions.php" enctype = "multipart/form-data">
                                <div class = "col-md-12 mt-4">
                                    <div class = "row">
                                        <div class="form-group tag_select w-100">
                                            <?php
                                            $ct = 1;
                                            $tag_item = get_alldata_order("tbl_solutions", "main_title ASC");
                                            if ($tag_item->num_rows > 0) {
                                                while ($row = $tag_item->fetch_assoc()) {
                                                    ?>
                                                    <input type="checkbox" name="tags[]" value="<?php echo $row['solution_id']; ?>" id="tags<?php echo $ct; ?>" class="btn" >
                                                    <label for="tags<?php echo $ct; ?>"><?php echo $row['main_title']; ?></label>
                                                    <?php
                                                    $ct++;
                                                }
                                            }
                                            ?>

                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="INDEX" value="add_tags"/>
                                            <input type="hidden" name="products_id" value="<?php echo $_POST['products_id']; ?>"/>
                                            <input type="submit" class="btn btn-danger float-right" value="Add Tags">
                                        </div>

                                    </div>
                                </div>
                            </form>
                            <?php } ?>
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