<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRATION</title>
    <style>
        body {
            background: url("images/new_car1.png") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Center the form */
        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
            vertical-align: middle;
        }

        .gender-group {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        input[type="submit"], #back a {
            display: block;
            text-align: center;
            background-color: #ff7200;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover, #back a:hover {
            background-color: #e65c00;
        }

        #back {
            text-align: center;
            margin: 20px 0;
        }

        /* Password message box */
        #message {
            display: none;
            background: #f1f1f1;
            color: #000;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }

        #message p {
            padding: 5px 0;
            font-size: 14px;
        }

        .valid { color: green; }
        .valid:before { content: "✔ "; }
        .invalid { color: red; }
        .invalid:before { content: "✖ "; }
    </style>
</head>
<body>

<?php
require_once('connection.php');
if(isset($_POST['regs'])) {
    $fname=mysqli_real_escape_string($con,$_POST['fname']);
    $lname=mysqli_real_escape_string($con,$_POST['lname']);
    $email=mysqli_real_escape_string($con,$_POST['email']);
    $lic=mysqli_real_escape_string($con,$_POST['lic']);
    $ph=mysqli_real_escape_string($con,$_POST['ph']);
    $pass=mysqli_real_escape_string($con,$_POST['pass']);
    $cpass=mysqli_real_escape_string($con,$_POST['cpass']);
    $gender=mysqli_real_escape_string($con,$_POST['gender']);
    $Pass=md5($pass);

    if(!preg_match("/^[A-Za-z]+$/", $fname)) { echo '<script>alert("First Name must contain letters only")</script>'; exit; }
    if(!preg_match("/^[A-Za-z]+$/", $lname)) { echo '<script>alert("Last Name must contain letters only")</script>'; exit; }
    if(!preg_match("/^[A-Z]{2}[0-9]{13}$/", strtoupper($lic))) { echo '<script>alert("License must be 2 letters followed by 13 numbers")</script>'; exit; }
    if(!preg_match("/^[0-9]{10}$/", $ph)) { echo '<script>alert("Phone must be 10 digits")</script>'; exit; }

    if(empty($fname)||empty($lname)||empty($email)||empty($lic)||empty($ph)||empty($pass)||empty($gender)){
        echo '<script>alert("Please fill all fields")</script>';
    } else {
        if($pass==$cpass){
            $sql2="SELECT * FROM users WHERE EMAIL='$email'";
            $res=mysqli_query($con,$sql2);
            if(mysqli_num_rows($res)>0){
                echo '<script>alert("EMAIL ALREADY EXISTS")</script>';
                echo '<script> window.location.href = "index.php";</script>';
            } else {
                $sql="INSERT INTO users (FNAME,LNAME,EMAIL,LIC_NUM,PHONE_NUMBER,PASSWORD,GENDER) 
                      VALUES('$fname','$lname','$email','$lic','$ph','$Pass','$gender')";
                $result=mysqli_query($con,$sql);
                if($result){
                    echo '<script>alert("Registration Successful Press ok to login")</script>';
                    echo '<script> window.location.href = "index.php";</script>';       
                } else {
                    echo '<script>alert("Database Error")</script>';
                }
            }
        } else {
            echo '<script>alert("PASSWORD DID NOT MATCH")</script>';
            echo '<script> window.location.href = "register.php";</script>';
        }
    }
}
?>

<div id="back"><a href="index.php">HOME</a></div>
<h1>JOIN OUR FAMILY OF CARS!</h1>

<div class="main">
    <div class="register">
        <h2>Register Here</h2>
        <form id="register" action="register.php" method="POST">    

            <label>First Name</label>
            <input type="text" name="fname" placeholder="Enter Your First Name" pattern="[A-Za-z]+" title="Letters only" required>

            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Enter Your Last Name" pattern="[A-Za-z]+" title="Letters only" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter Valid Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="ex: example@ex.com" required>

            <label>Your License Number</label>
            <input type="text" name="lic" id="lic" placeholder="Ex: MH1234567890123" pattern="^[A-Z]{2}[0-9]{13}$" title="2 letters followed by 13 numbers" maxlength="15" required>

            <label>Phone Number</label>
            <input type="tel" name="ph" maxlength="10" onkeypress="return onlyNumberKey(event)" pattern="[0-9]{10}" title="10 digits" placeholder="Enter Your Phone Number" required>

            <label>Password</label>
            <input type="password" name="pass" maxlength="12" id="psw" placeholder="Enter Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="At least 1 uppercase, 1 lowercase, 1 number, 8 chars min" required>

            <label>Confirm Password</label>
            <input type="password" name="cpass" id="cpsw" placeholder="Re-enter Password" required>

            <label>Gender</label>
            <div class="gender-group">
                <label><input type="radio" name="gender" value="male" required> Male</label>
                <label><input type="radio" name="gender" value="female"> Female</label>
            </div>

            <input type="submit" class="btnn" value="REGISTER" name="regs">
        </form>

        <div id="message">
            <h3>Password must contain the following:</h3>
            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
            <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
            <p id="number" class="invalid">A <b>number</b></p>
            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>
    </div> 
</div>

<script>
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

myInput.onfocus = function() { document.getElementById("message").style.display = "block"; }
myInput.onblur = function() { document.getElementById("message").style.display = "none"; }

myInput.onkeyup = function() {
    var lowerCaseLetters = /[a-z]/g;
    letter.className = myInput.value.match(lowerCaseLetters) ? "valid" : "invalid";
    var upperCaseLetters = /[A-Z]/g;
    capital.className = myInput.value.match(upperCaseLetters) ? "valid" : "invalid";
    var numbers = /[0-9]/g;
    number.className = myInput.value.match(numbers) ? "valid" : "invalid";
    length.className = myInput.value.length >= 8 ? "valid" : "invalid";
}

// LICENSE INPUT VALIDATION (2 letters + 13 numbers)
document.getElementById("lic").addEventListener("input", function () {
    let v = this.value.toUpperCase().replace(/[^A-Z0-9]/g, "");
    if (v.length <= 2) v = v.replace(/[^A-Z]/g, "");
    else v = v.substring(0,2).replace(/[^A-Z]/g,"") + v.substring(2).replace(/[^0-9]/g,"");
    this.value = v.substring(0,15);
});

// PHONE NUMBER ONLY
function onlyNumberKey(evt) {
    var code = evt.which ? evt.which : evt.keyCode;
    if(code > 31 && (code < 48 || code > 57)) return false;
    return true;
}
</script>
</body>
</html>