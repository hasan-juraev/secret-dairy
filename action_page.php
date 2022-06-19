<?php
//Starting a session to keep user logged in
session_start();

if(array_key_exists("logut", $_GET)){
    unset($_SESSION);
    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";

}else if(array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)){
    header("Location: mypage.php");
}

// define variables and set to empty values
$emailErr = $userErr = $passwordErr = $cpasswordErr = $firstErr = $lastErr = $teamErr = "";
$email = $username = $password = $cpassword = $firstname = $lastname = $teamname = "";

// Function to test input. If input has space, slash, or special characters.
function test_input($data)
{
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

// The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    $email = test_input($_POST["email"]);
    
    //Validates Username
    if (empty($_POST["name"])) {
        $userErr = "You Forgot to Enter Your Username!";
        echo $userErr;
    }
    //Validates email
    else if (empty($_POST["email"])) {
        $emailErr = "You Forgot to Enter Your Email!";
        echo $emailErr;
    } else if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
        
        // check if e-mail address syntax is valid            
        $emailErr = "You Entered An Invalid Email Format"; 
        echo $emailErr;
    }
    
    //Validates password & confirm passwords.
    else if(!empty($_POST["password"]) && ($_POST["password"] == $_POST["cpassword"])) {
        $password = test_input($_POST["password"]);        
        $cpassword = test_input($_POST["cpassword"]);

        if (strlen($_POST["password"]) < '8') {
            $passwordErr = "Your Password Must Contain At Least 8 Characters!";
             echo $passwordErr;
        }
        else if(!preg_match("#[0-9]+#",$password)) {
            $passwordErr = "Your Password Must Contain At Least 1 Number!";
            echo $passwordErr;
        }
        else if(!preg_match("#[A-Z]+#",$password)) {
            $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
            echo $passwordErr;
        }
        else if(!preg_match("#[a-z]+#",$password)) {
            $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
            echo $passwordErr;
        }else{
            $conn = mysqli_connect("sdb-m.hosting.stackcp.net", "usersd-313935a355", "rfik7u2b5q","usersd-313935a355");
            if(mysqli_connect_error()){
                die("There was an error connecting to database");
            }else{
                echo "db connection success!";
                //Sanitizing variables to prevent SQL Injection Attack
                $username = mysqli_real_escape_string($conn, $_POST['name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $password = mysqli_real_escape_string($conn, $_POST['password']);        
                echo $username;    

                //Insert into DB                
                $query= "INSERT INTO `dairy`(`email`, `password`, `username`) VALUES('$email', '$password', '$username')";
                
                //Updating user password to stronger password
                if($insert = mysqli_query($conn, $query)){

                    //Using md5() function, password is hashed. Also, added salt into a password. Salt is hashed which consists of row id.
                    $query = "UPDATE `dairy` SET `password` = '".md5(md5(mysqli_insert_id($conn)) .$_POST['password'])."' WHERE id = ".mysqli_insert_id($conn)." LIMIT 1";
                    mysqli_query($conn, $query);

                    //Creating session to keep user logged in
                    $_SESSION['id'] = mysqli_insert_id($conn);

                    if($_POST['stayLoggedIn'] == '1'){

                        setcookie("id", mysqli_insert_id($conn), time() + 60*60*24*365);

                    }

                   header("Location: mypage.php");
                }else{
                    echo "failed";
                }
            } 
        } 

    }
    //Checking if user entered only password but not confirm password
    else if(!empty($_POST["password"])){
        $cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
        echo $cpasswordErr;
    }
    
    //Checking if user not entered passowrd
    else if(empty($_POST["password"])){
        $passwordErr = "Password is empty";
        echo $passwordErr;
    }   
  
}

?>



