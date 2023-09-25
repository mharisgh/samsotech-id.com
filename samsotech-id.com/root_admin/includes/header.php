<?php
error_reporting(0);
include './includes/connections.php';

//print_r($_SESSION);
//print_r($_POST);
function limit_words($string, $word_limit) {
    $words = explode(" ", $string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

$uname = $_SESSION["uname"];
$utype = $_SESSION["utype"];
if ($uname == null) {
    header("Location:../index.php");
}
else {
    $resutype = get_where_cond("tbl_usertype", "usertype='" . $utype . "'");
    if ($resutype->num_rows > 0) {
        while ($rowpriv = $resutype->fetch_assoc()) {
            $cmsh = $rowpriv['cms'];
            $adsh = $rowpriv['ads'];
            $cate_tagsh = $rowpriv['cate_tags'];
            $businessh = $rowpriv['business'];
            $settingsh = $rowpriv['settings'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            <?php
            $site_tit = get_where_cond("tbl_appearance", 'id=1');
            if ($site_tit->num_rows > 0) {
                $row_title = $site_tit->fetch_assoc();
            }
            echo $row_title['description'];
            ?>

        </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap-4.3.1/css/bootstrap.css" rel="stylesheet"/>
        <link href="./css/style.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700&display=swap" rel="stylesheet">

        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>

    </head>
    <body>
        <div class="container-fluid bg-dark">
            <div class="row">
                <div class="col-md-12 border-bottom bg-turf">
                    <div class="row">
                        <div class="col-md-2 logo">

                            <h5 style="line-height: 50px;">Samsotech</h5>
                        </div>
                        <div class="col-md-7">
                            <h6 class="text-center text-danger">
                                <?php
                                session_start();
                                if ($_SESSION["err"]) {
                                    ?>
                                    <h3 class="text-center text-danger"><?php echo $_SESSION["err"]; ?> </h3> <?php
                                    unset($_SESSION['err']);
                                }
                                ?>
                            </h6>
                        </div>

                    </div>
                </div>
            </div>
        </div>
