<?php
error_reporting(1);
include './includes/connections.php';
//print_r($_SESSION);
//print_r($_POST);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Samsotech
        </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap-4.3.1/css/bootstrap.css" rel="stylesheet"/>
        <link href="./css/style.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700&display=swap" rel="stylesheet">
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    </head>
    <body>
        <div class="container-fluid bg-dark">
            <div class="row">
                <div class="col-md-12 border-bottom bg-turf">
                    <div class="row">

                        <div class="col-md-2 logo">
                            <h5 style="line-height: 50px;"> 
                                Samsotech</h5>

                        </div>
                        <div class="col-md-10"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <?php
            session_start();
            if ($_SESSION["err"]) {
                ?><h3 style="color: maroon;text-align: center;"><?php echo $_SESSION["err"]; ?> </h3> <?php
                unset($_SESSION['err']);
            }
            ?>
            <div class="row">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4 mt-5">
                    <div class="card p-5">
                        <form method="post" action="includes/actions.php" class="tm-login-form">
                            <h4 class="text-center">Login</h4>
                            <div class="form-group">
                                <label class="text-center w-100" for="username">Username</label>
                                <input type="text"  class="form-control validate"  required id="field-1" placeholder="" name="uname">
                            </div>
                            <div class="form-group mt-3">
                                <label class="text-center w-100" for="password">Password</label>
                                <input type="password" class="form-control validate" required id="field-1" placeholder="" name="pswd">
                            </div>
                            <div class="form-group mt-4">
                                <input type="hidden" name="INDEX" value="login">
                                <input type="submit"  class="btn btn-primary btn-block text-uppercase" value="Login"  name="sub-btn">
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-md-4">&nbsp;</div>
            </div>
        </div>
        <?php
        include './includes/footer.php';
        ?>
