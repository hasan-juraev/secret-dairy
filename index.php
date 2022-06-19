<?php
	if(isset($_POST['submit'])) {   
		
		//DB connection
		$conn = mysqli_connect("sdb-m.hosting.stackcp.net", "usersd-313935a355", "rfik7u2b5q","usersd-313935a355") or die ("Connection failed: ".mysqli_connect_error());

		$error = "";
		$successMessage = ""; 

		if(!$_POST["email"]){ /* if there is nothing inside the POST variable of email | if email is empty*/
			$error .= "Email address is required<br>";
		}

		if(!$_POST["password"]){ /* if there is nothing inside the POST variable of subject | if subject is empty*/
			$error .= "Password is required<br>";
		}
		
		// check if e-mail address is well-formed
		if ($_POST["email"] && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL === FALSE)) {
		$error .= "Invalid email format.<br>";
		}

		if($error != ""){
			$error = '<div class="alert alert-danger" role="alert"> <strong>The following field(s) are missing: <br> </strong>' . $error . '</div>';
		} 
		//Sanitize form data
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		//Query the database
		$query = "select * from dairy where email = '$email' and password = '$password' LIMIT 1";
		$result = mysqli_query($conn, $query);

		//Check if user exists in DB
		
		$loginError = "";
		if ($rowcount = mysqli_num_rows($result) > 0) {		
			header("Location: mypage.php");
		
		}
		else{
			$loginError  = '<div class="alert alert-danger" role="alert"> <strong>You have not been signed up yet! <br> </strong> Please, sign up first and try again! </div>' ;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="signin.css">
    <title>SecretDiary</title>   
</head>
<body>
<div class="container">
    
    <div id="successMessage"></div>
    <div id="errorMessage"> <?php echo $errors= $error.$successMessage ? $errors= $error.$successMessage : $errors= $loginError; ?> </div>
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<!--Card Header-->
			<div class="card-header">
            <h1 class="login-title"> Secret Dairy </h1>
				<h3>Sign In</h3>
				<div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>
					<span><i class="fab fa-twitter-square"></i></span>
				</div>
			</div>
			<!--Card Body-->
			<div class="card-body">
				<form id=testForm method="post" action="">
                    <!--Input Email-->
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>                        
						<input type="email" class="form-control" placeholder="username" name="email">						
					</div>
                    <!--Input Password-->
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" placeholder="password" name="password">
					</div>
                    <!--Remember me button-->
					<div class="row align-items-center remember">
						<input type="checkbox">Remember Me
					</div>
                    <!--Log in button-->
					<div class="form-group">
						<input type="submit" id="submit" value="Login" class="btn float-right login_btn" name="submit">
					</div>
				</form>
			</div>
            <!--Sign up button -->
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					Don't have an account?<a href="signup.php">Sign Up</a>
				</div>
				<div class="d-flex justify-content-center">
					<a href="#">Forgot your password?</a>
				</div>
			</div>
		</div>
	</div>
    
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>