<?php 
    if(isset($_GET['vkey'])){
        
        $vkey = $_GET['vkey'];

        include("db_connect.php");
            
        if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }
        $query = "SELECT `verified`, `vkey` FROM dairy WHERE `verified` = 0 AND `vkey`='$vkey' LIMIT 1";
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) == 1){

            $query = "UPDATE dairy SET `verified` = 1 WHERE `vkey`='$vkey' LIMIT 1";
            $update = mysqli_query($link, $query);

            if($update){
                echo  "<div class='alert alert-success' role='alert'> Your account has been verified successfully! <a href='signup.php?signUp=0'>Log in</a></div>";
                /*$query = "SELECT `id`,`verified` FROM dairy WHERE `id`= '$id' AND `verified`= 1 LIMIT 1";
                $result = mysqli_query($link, $query);

                if($result){
                    header("Location: loggedinpage.php");
                    exit();
                } */
            }else{
                echo mysqli_error($update);
            }

        }
        else{
            echo "This account invalid";
        }        

    }else{
        die("Something went wwrong");
    }
?>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>