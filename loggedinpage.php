<?php

    session_start();

    if (isset($_SESSION['access_token'])) {
	
	    unset($_SESSION['access_token']);
	$gClient->revokeToken();
	session_destroy();
	header('Location: signup.php');
	exit();
	}

    //if user logged in
    if(array_key_exists("id", $_COOKIE) && $_COOKIE['id']){
        
        $_SESSION['id'] = $_COOKIE['id'];
    }

    //if user Session exist, show column `diary` from dairy TABLE
    if(array_key_exists("id", $_SESSION)){

        include("db_connect.php");

        $query = "SELECT diary FROM `dairy` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
   
        $diaryContent = $row['diary'];
    

    }else{

        header("Location: signup.php");
        exit();
    }

    include("header.php");
?>
<nav class="navbar navbar-light bg-faded navbar-fixed-top">
  

  <a class="navbar-brand" href="#">Secret Diary</a>

    <div class="pull-xs-right">
      <a href ='signup.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>

    

</nav>

    <div  class="container-fluid" id="containerLoggedInPage">
        <textarea id="diary" class="form-control"> <?php echo $diaryContent ?> </textarea>
    </div>

<?php   
    include("footer.php");

?>