<?php

require_once 'connection.php';

session_start();

if(isset($_SESSION["user_login"]))  //check condition user login not direct back to index.php page
{
    header("location: welcome.php");
}

if(isset($_REQUEST['btn_login']))   //button name is "btn_login" 
{
    $username   =strip_tags($_REQUEST["txt_username_email"]);   //textbox name "txt_username_email"
    $email      =strip_tags($_REQUEST["txt_username_email"]);   //textbox name "txt_username_email"
    $password   =strip_tags($_REQUEST["txt_password"]);         //textbox name "txt_password"
        
    if(empty($username)){                       
        $errorMsg[]="veuillez entrer un nom d'utilisateur ou un e-mail";    //check "username/email" textbox not empty 
    }
    else if(empty($email)){
        $errorMsg[]="veuillez entrer un nom d'utilisateur ou un e-mail";    //check "username/email" textbox not empty 
    }
    else if(empty($password)){
        $errorMsg[]="veuillez entrer le mot de passe";  //check "passowrd" textbox not empty 
    }
    else
    {
        try
        {
            $select_stmt=$db->prepare("SELECT * FROM tbl_user WHERE username=:uname OR email=:uemail"); //sql select query
            $select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email));   //execute query with bind parameter
            $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
            
            if($select_stmt->rowCount() > 0)    //check condition database record greater zero after continue
            {
                if($username==$row["username"] OR $email==$row["email"]) //check condition user taypable "username or email" are both match from database "username or email" after continue
                {
                    if(password_verify($password, $row["password"])) //check condition user taypable "password" are match from database "password" using password_verify() after continue
                    {
                        $_SESSION["user_login"] = $row["user_id"];  //session name is "user_login"
                        $loginMsg = "Connexion réussie...";     //user login success message
                        header("refresh:2; welcome.php");           //refresh 2 second after redirect to "welcome.php" page
                    }
                    else
                    {
                        $errorMsg[]="mauvais mot de passe";
                    }
                }
                else
                {
                    $errorMsg[]="mauvais nom d'utilisateur ou e-mail";
                }
            }
            else
            {
                $errorMsg[]="mauvais nom d'utilisateur ou e-mail";
            }
        }
        catch(PDOException $e)
        {
            $e->getMessage();
        }       
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Dycys le nouveau Marketing Holding">
    <meta name="author" content="Dycys">

    <!-- Website Title -->
    <title>Dycys - Connexion</title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/fontawesome-all.css" rel="stylesheet">
    <link href="css/swiper.css" rel="stylesheet">
    <link href="css/magnific-popup.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">  
    
    <!-- Favicon  -->
    <link rel="icon" href="images/favicon.png">
</head>
<body data-spy="scroll" data-target=".fixed-top">
    

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">

            <!-- Text Logo - Use this if you don't have a graphic logo -->
            <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Tivo</a> -->

            <!-- Image Logo -->
            <a class="navbar-brand logo-image" href="index.php"><img src="images/logo.gif" alt="alternative"></a> 
            
            <!-- Mobile Menu Toggle Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-awesome fas fa-bars"></span>
                <span class="navbar-toggler-awesome fas fa-times"></span>
            </button>
            <!-- end of mobile menu toggle button -->

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="index.php">ACCUEIL <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="index.php#features">CARACTERISTIQUE</a>
                    </li>
                <span class="nav-item">
                    <a class="btn-outline-sm" href="log-in.php">CONNEXION</a>
                </span>
            </div>
        </div> <!-- end of container -->
    </nav> <!-- end of navbar -->
    <!-- end of navigation -->


    <!-- Header -->
    <header id="header" class="ex-2-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Connexion</h1>
                   <p>Vous n'avez pas de compte ? Alors inscrivez-vous <a class="white" href="sign-up.php">ici</a></p> 
                    <!-- Sign Up Form -->
                    <div class="form-container">
                    <form method="post" class="form-horizontal">
                                
                <div class="form-group">
                <label class="label-control" for="lemail"></label>
                <div class="col-sm-15">
                <input type="text" name="txt_username_email" class="form-control" placeholder="Saisissez le nom d'utilisateur ou l'e-mail" />
                </div>
                </div>
                    
                <div class="form-group">
                <label class="label-control" for="lpassword"></label>
                <div class="col-sm-15">
                <input type="password" name="txt_password" class="form-control" placeholder="Saisissez le mot de passe" />
                </div>
                </div>
                
                <div class="form-group">
                <input type="submit" name="btn_login" class="btn btn-success" value="Connexion">
                </div>
                </div>
                        </form>
                    </div> <!-- end of form container -->
                    <!-- end of sign up form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->


    <!-- Scripts -->
    <script src="js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->
    <script src="js/jquery-1.12.4-jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script> 
</body>
</html>