
<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "myhealth");

if(mysqli_connect_errno()) 
{
    echo "Failed to connect: ".mysqli_connect_errno();
}

//Declaring variables to prevent errors

$fname = ""; //First name
$lname = ""; //Last name
$em = ""; //email
$em2 = ""; //confirm email
$password = ""; //password
$password2 = ""; //confirm password
$date = ""; //sign up date
$error_array = array(); //holds error messages

if(isset($_POST['register_button'])) 

//Registration form values go here
{
    //First Name
    $fname = strip_tags($_POST['reg_fname']); //Removes html tags if user adds them
    $fname = str_replace('', '',$fname); //Removes spaces if user enters space
    $fname = ucfirst(strtolower($fname)); //Takes name variable and converts all to lowercase then keep first letter uppercase
    $_SESSION ['reg_fname'] = $fname; //stores first name

      //Last Name
    $lname = strip_tags($_POST['reg_lname']); //Removes html tags if user adds them
    $lname = str_replace('', '',$lname); //Removes spaces if user enters space
    $lname = ucfirst(strtolower($lname));
    $_SESSION ['reg_lname'] = $lname; //stores last name

    //Email
    $em = strip_tags($_POST['reg_email']); //Remove html tags if user adds them
    $em = str_replace('', '',$em); //Removes spaces if user enters space
    $em = ucfirst(strtolower($em));
    $_SESSION ['reg_email'] = $em; //stores email

    //Email
    $em2 = strip_tags($_POST['reg_email2']); //Remove html tags if user adds them
    $em2 = str_replace('', '',$em2); //Removes spaces if user enters space
    $em2 = ucfirst(strtolower($em2));
    $_SESSION ['reg_email2'] = $em2; //stores email

    //Password
    $password = strip_tags($_POST['reg_password']); //Remove html tags if user adds them
    
    //Password Confirm
    $password2 = strip_tags($_POST['reg_password2']); //Remove html tags if user adds them

    $date = date("Y-m-d"); //Gets the current date 

    if ($em == $em2)
    {
        //check if email is in valid format
        if(filter_var($em, FILTER_VALIDATE_EMAIL))
        {
        $em = filter_var($em, FILTER_VALIDATE_EMAIL);

        //check if email exists in the system
        $e_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$em'");

        //count the number of rows returned 
        $num_rows = mysqli_num_rows($e_check);

        if($num_rows > 0)
        {
        array_push($error_array, "Email already in use<br>");
        }


        }
        else
        {
        array_push($error_array,"Invalid email format!<br>");
        }
    
    }
    else 
    {
        array_push($error_array,"Emails don't match!<br>");
    }
    
    if(strlen($fname) > 25 || strlen($fname) < 2){
        array_push($error_array,"Your first name should be between 2 and 25 characters<br>");
    }

    if(strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array,"Your last name should be between 2 and 25 characters.<br>");
        
    }

    if($password != $password2) {
        array_push($error_array, "Your passwords do not match<br>");
        
    }
    
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password))
        {
        array_push($error_array, "Your password can only contain english characters or numbers!<br>");
        }
    }

    if (strlen ($password > 30 || strlen($password) < 5)) {
        array_push($error_array, "Your password needs to be between 5 and 38 characters!<br>");
    }

    if (empty($error_array)) {
        $password = md5($password); //This encrpyts the password before it is sent to the database
        $username = strtolower($fname."_".$lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        while (mysqli_num_rows($check_username_query)!= 0){
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        }

        $rand = rand(1,2); //random number
        if ($rand==1){
            $profile_pic = "assets/images/profile_pics/head_green.png";
        }
        else if ($rand == 2 ){
            $profile_pic = "assets/images/profile_pics/head_grey.png";
        }

        $query = mysqli_query($con, "INSERT INTO users VALUES ('' ,'$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0','no', ',')" );
        array_push($error_array, "You are all set! Go ahead and login!<br>");
        

        //clearing variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }

}


?>

<html>
<head>
    <title>Welcome to MyHealth!</title>
</head>
<body>
    <form action="register.php" method = "POST">
        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
        if(isset($_SESSION['reg_fname'])) {
        echo $_SESSION['reg_fname'];
        }
        ?>" required>
        <br>
        <?php if(in_array("Your first name should be between 2 and 25 characters<br>", $error_array)){
            echo "Your first name should be between 2 and 25 characters<br>";
        }
        ?>

        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
        if(isset($_SESSION['reg_lname'])) {
        echo $_SESSION['reg_lname'];
        }
        ?>" required>
        <br>
        <?php if(in_array("Your last name should be between 2 and 25 characters.<br>", $error_array)){
            echo "Your last name should be between 2 and 25 characters.<br>";
        }
        ?>

        <input type="email" name="reg_email" placeholder="Email" value="<?php 
        if(isset($_SESSION['reg_email'])) {
        echo $_SESSION['reg_email'];
        }
        ?>" required>
        <br>

        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
        if(isset($_SESSION['reg_email2'])) {
        echo $_SESSION['reg_email2'];
        }
        ?>" required>
        <?php if(in_array("Email already in use<br>", $error_array)){
            echo "Email already in use";
        }
        
        else if(in_array("Invalid email format!<br>", $error_array)){
            echo "Invalid email format!<br>";
        }
        
        else if(in_array("Emails don't match!<br>", $error_array)){
            echo "Emails don't match!<br>";
        }
        ?>
        <br>
        <input type="password" name="reg_password" placeholder="Password" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
        <?php if(in_array("Your passwords do not match<br>", $error_array)){
            echo "Your passwords do not match<br>";
        }
        
        else if(in_array("Your password can only contain english characters or numbers!<br>", $error_array)){
            echo "Your password can only contain english characters or numbers!<br>";
        }
        
        else if(in_array("Your password needs to be between 5 and 38 characters!<br>", $error_array)){
            echo "Your password needs to be between 5 and 38 characters!<br>";
        }
        ?>
        <br>
        <input type="submit" name="register_button" value="Register">
        <br>

        <?php if (in_array("You are all set! Go ahead and login!<br>", $error_array)) {
        echo "You are all set! Go ahead and login!<br>";
        }
        ?>



    </form>
</body>
</html>