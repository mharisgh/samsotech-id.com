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
                        <h4 class="text-center">Newsletter</h4>
                        <?php
                        session_start();
                        if ($_SESSION["err"]) {
                            ?><h3 style="color: maroon;text-align: center;"><?php echo $_SESSION["err"]; ?> </h3> <?php
                            unset($_SESSION['err']);
                        }
                        ?>
                            <div class="row">
                        <div class="col-md-5 border p-2 bg-dark text-white rounded">
                            <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <?php 
                                        $email_eng='';
                                        $newsletter_eng_res= get_alldata("tbl_newsletter");
                                        if($newsletter_eng_res->num_rows>0){
                                            while($row_eng=$newsletter_eng_res->fetch_assoc()){
                                                $email_eng=$email_eng.','.$row_eng['email'];
                                            }
                                            $email_eng= substr($email_eng, 1);
                                        }
                                        ?>
                                          <h5>English</h5>
                                          <input type="hidden" name="email" value="<?php echo $email_eng;?>">
                                        <div class="form-group">
                                            <label for="usertype">Newsletter Title</label>
                                            <input type="text" required="" value="<?php echo $row_news['title']; ?>" maxlength="50" class="form-control" id="field-1" placeholder="Newsletter Title" name="newsletter_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="usertype">Newsletter Content</label>
                                            <textarea class="ckeditor form-control" cols="80" id="editor1" name="newsletter_description" rows="10"></textarea>
                                        </div>
                                       
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-7">

                                                </div>
                                                <div class="col-md-5">
                                                    <input type="hidden" name="INDEX" value="newsletter_mail"/>
                                                             <input type="hidden" name="lang" value="en"/>
                                                    <input type="submit" value="Send Mail" class="btn btn-danger">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                                   <div class="col-md-5 border p-2 bg-dark text-white rounded">
                            <form method="post" action="includes/actions.php" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                          <h5>Arabic</h5>
                                        <div class="form-group">
                                            <label for="usertype">Newsletter Title</label>
                                            <input type="text" required="" dir="rtl" value="" maxlength="50" class="form-control" id="field-1" placeholder="Newsletter Title" name="newsletter_title_ar">
                                        </div>
                                        <div class="form-group">
                                            <label for="usertype">Newsletter Content</label>
                                            <textarea class = "ckeditor form-control" cols = "80" id = "editor2" name = "newsletter_description_ar" rows = "10"></textarea>
                                        </div>
                                       
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-7">

                                                </div>
                                           <?php 
                                        $email_eng='';
                                        $newsletter_eng_res= get_alldata("tbl_newsletter");
                                        if($newsletter_eng_res->num_rows>0){
                                            while($row_eng=$newsletter_eng_res->fetch_assoc()){
                                                $email_eng=$email_eng.','.$row_eng['email'];
                                            }
                                            $email_eng= substr($email_eng, 1);
                                        }
                                        ?>
                                                <div class="col-md-5">
                                                     <input type="hidden" name="email_ar" value="<?php echo $email_eng;?>">
                                                    <input type="hidden" name="INDEX" value="newsletter_mail"/>
                                                             <input type="hidden" name="lang" value="ar"/>
                                                    <input type="submit" value="Send Mail" class="btn btn-danger">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
</div>

                    </div>

                </div>


            </div>
        </div>
   <script>
                                                CKEDITOR.replace('editor2', {
                                                    language: 'ar'
                                                });
                                            </script>
        <?php
        include './includes/footer.php';
    }
    ?>