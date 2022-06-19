<?php
    session_start();
    $error = "";

    require_once "config.php";  

	if (isset($_SESSION['access_token'])) {
		header('Location: loggedinpage.php');
		exit();
	}

	$loginURL = $gClient->createAuthUrl();

    //check if user Logged out. If so, unset session    
    if (array_key_exists("logout", $_GET)) {
       
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  
        
        session_destroy();      
    }

    //if user did not log out, then redirect user to loggedinpage.php  
    else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        include("db_connect.php");
        
        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } 
        else {

            
            //to check if user Sign up
            if ($_POST['signUp'] == '1') {
                
               
                //generate vkey
                $vkey = md5(time().$_POST['email']);
                
                //check if the email that user enters not taken already
                $query = "SELECT id FROM `dairy` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                }else{

                    //if email is not taken already, then insert into DB      
                    
                    $query = "INSERT INTO `dairy` (`email`, `password`, `vkey` ) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', '".$vkey."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";
                    }

                    else{

                        $query = "UPDATE `dairy` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
                        
                        $id = mysqli_insert_id($link);
                        
                        mysqli_query($link, $query);

                        $_SESSION['id'] = $id;

                        //if user checks stayloggedin box, then cookie will be set up
                        if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", $id, time() + 60*60*24*365);

                        }

                        //Send Email
                        $to = mysqli_real_escape_string($link, $_POST['email']);
                        $subject = "Email verification";
                        $message = "<a href = 'https://hasan-juraev25.stackstaging.com/secretdairy/verify.php?vkey=$vkey'> Register Account </a>";
                        $headers = "From: info@hasan.juraev25 \r\n";
                        $headers .= "MIME-Version: 1.0". "\r\n";
                        $headers .= "Content-type:text/html; charset= UTF-8". "\r\n";
                        
                        if(mail($to, $subject, $message, $headers)){
                            
                            header("Location: thankyou.php");
                            exit();                        
                        }
                    }
                    
                }
        
            }   
            //to check if user Login
            else{                    
                    $query = "SELECT * FROM `dairy` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['id'] = $row['id'];
                            
                            //if user checks stayloggedin box, then cookie will be set up
                            if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            }
                            header("Location: loggedinpage.php");
                                
                        } else {
                            
                            $error = "That email/password combination could not be found.";
                            
                        }
                        
                    } else {
                        
                        $error = "That email/password combination could not be found.";
                        
                    }                    
        
        
                }
        }   
        
        

          
    }

?>
<?php include("header.php"); ?>

      <div class="container" id="homePageContainer">
      
    <h1>Secret Diary</h1>
          
          <p><strong>Store your thoughts permanently and securely.</strong></p>
          
          <div id="error"><?php if ($error!="") {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
    
} ?></div>
<!--Sign up form-->
<form method="post" id = "signUpForm">
    
    <p>Interested? Sign up now.</p>
    
    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">
        
    </fieldset>
    
    <fieldset class="form-group">
    
        <input class="form-control" type="password" name="password" placeholder="Password">
        
    </fieldset>
    
    <div class="checkbox">
    
        <label>
    
        <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
            
        </label>
        
    </div>
    
    <fieldset class="form-group">
        <!--HIDDEN Signup button-->
        <input type="hidden" name="signUp" value="1">

        <!-- Signup button-->
        <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
        
    </fieldset>
    
    <p><a class="toggleForms">Log in</a></p>
    <p> <input type="button" onclick="window.location = '<?php echo $loginURL ?>';" value="Log In With Google" class="btn btn-danger"> </p>

</form>
<!--Log in form-->
<form method="post" id = "logInForm">
    
    <p>Log in using your username and password.</p>
    
    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">
        
    </fieldset>
    
    <fieldset class="form-group">
    
        <input class="form-control"type="password" name="password" placeholder="Password">
        
    </fieldset>
    
    <div class="checkbox">
    
        <label>
    
            <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
            
        </label>
        
    </div>
        <!--HIDDEN Login button-->
        <input type="hidden" name="signUp" value="0">
    
    <fieldset class="form-group">
        <!-- Login button-->
        <input class="btn btn-success" type="submit" name="submit" value="Log In!">
        
    </fieldset>
    
    <p><a class="toggleForms">Sign up</a></p>

</form>
          
      </div>

<?php include("footer.php"); ?>