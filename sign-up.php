<?php

require_once "connection.php";

if(isset($_REQUEST['btn_register'])) //button name "btn_register"
{
    $username   = strip_tags($_REQUEST['txt_username']);    //textbox name "txt_email"
    $email      = strip_tags($_REQUEST['txt_email']);       //textbox name "txt_email"
    $password   = strip_tags($_REQUEST['txt_password']);    //textbox name "txt_password"
        
    if(empty($username)){
        $errorMsg[]="Veuillez entrer le nom d'utilisateur"; //check username textbox not empty 
    }
    else if(empty($email)){
        $errorMsg[]="Veuillez saisir un e-mail";    //check email textbox not empty 
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg[]="S'il vous plaît, mettez une adresse email valide"; //check proper email format 
    }
    else if(empty($password)){
        $errorMsg[]="Veuillez entrer le mot de passe";  //check passowrd textbox not empty
    }
    else if(strlen($password) < 6){
        $errorMsg[] = "Le mot de passe doit être au moins de 6 caractères"; //check passowrd must be 6 characters
    }
    else
    {   
        try
        {   
            $select_stmt=$db->prepare("SELECT username, email FROM tbl_user 
                                        WHERE username=:uname OR email=:uemail"); // sql select query
            
            $select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email)); //execute query 
            $row=$select_stmt->fetch(PDO::FETCH_ASSOC); 
            
            if($row["username"]==$username){
                $errorMsg[]="Désolé, le nom d'utilisateur existe déjà"; //check condition username already exists 
            }
            else if($row["email"]==$email){
                $errorMsg[]="Désolé, l'e-mail existe déjà"; //check condition email already exists 
            }
            else if(!isset($errorMsg)) //check no "$errorMsg" show then continue
            {
                $new_password = password_hash($password, PASSWORD_DEFAULT); //encrypt password using password_hash()
                
                $insert_stmt=$db->prepare("INSERT INTO tbl_user (username,email,password) VALUES
                                                                (:uname,:uemail,:upassword)");      //sql insert query                  
                
                if($insert_stmt->execute(array( ':uname'    =>$username, 
                                                ':uemail'   =>$email, 
                                                ':upassword'=>$new_password))){
                                                    
                    $registerMsg="Inscription avec succès ..... Veuillez cliquer sur le lien de compte de connexion"; //execute query success message
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
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
    <title>Dycys - Inscription</title>
    
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
    
    <!-- Preloader -->
	<div class="spinner-wrapper">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <!-- end of preloader -->
    

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">

            <!-- Text Logo - Use this if you don't have a graphic logo -->
            <!-- <a class="navbar-brand logo-text page-scroll" href="index.php">Dycys</a> -->

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
                        <a class="nav-link page-scroll" href="index.html#features">CARACTERISTIQUE</a>
                    </li>
                <span class="nav-item">
                    <a class="btn-outline-sm" href="log-in.php">CONNEXION</a>
                </span>
            </div>
        </div> <!-- end of container -->
    </nav> <!-- end of navbar -->
    <!-- end of navigation -->

        <?php
        if(isset($errorMsg))
        {
            foreach($errorMsg as $error)
            {
            ?>
                <div class="alert alert-danger">
                    <strong>MAUVAIS ! <?php echo $error; ?></strong>
                </div>
            <?php
            }
        }
        if(isset($registerMsg))
        {
        ?>
            <div class="alert alert-success">
                <strong><?php echo $registerMsg; ?></strong>
            </div>
        <?php
        }
        ?>  

    <!-- Header -->
    <header id="header" class="ex-2-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>S'inscrire</h1>
                   <p>Remplissez le formulaire ci-dessous pour vous inscrire à Dycys. Déjà inscrit ? Clique <a class="white" href="log-in.html">Ici</a></p> 
                    <!-- Inscription Form -->
                    <div class="form-container">
                        <form method="post" class="form-horizontal">
                            <div class="form-group">
                                <input type="text" name="txt_username" class="form-control" placeholder="Saisissez votre nom d'utilisateur" />
                            </div>
                            <div class="form-group">
                            <input type="text" name="txt_email" class="form-control" placeholder="Saisissez votre e-mail" />
                            </div>
                            <div class="form-group">
                                <input type="password" name="txt_password" class="form-control" placeholder="Saisissez votre mot de passe" />
                            </div>
                            <div class="form-group checkbox">
                                <input type="checkbox" id="sterms" value="Agreed-to-Terms" required>Je suis d'accord avec Dycys <a href="privacy-policy.html">Politique de confidentialité</a> et <a href="terms-conditions.html">Termes et conditions</a>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input type="submit"  name="btn_register" class="btn btn-primary " value="Inscription">
                            </div>
                            <div class="form-message">
                                <div id="smsgSubmit" class="h3 text-center hidden"></div>
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
</body>
</html>