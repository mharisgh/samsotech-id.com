<?php

session_start();
unset($_SESSION['err']);
unset($_SESSION['uname']);
unset($_SESSION['utype']);
header("Location:index.php");
