<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAR BOOKING</title>

    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>
</head>

<body background="images/book.jpg">

<style>
*{margin:0;padding:0;}

div.main{width:400px;margin:100px auto 0 auto;}

.btnn{
    width:240px;height:40px;background:#ff7200;border:none;
    margin-top:30px;margin-left:30px;font-size:18px;
    border-radius:10px;cursor:pointer;color:#fff;
}

.btnn:hover{background:#fff;color:#ff7200;}

h2{text-align:center;padding:20px;font-family:sans-serif;}

div.register{
    background-color:rgba(0,0,0,0.6);
    width:100%;font-size:18px;border-radius:10px;
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:2px 2px 15px rgba(0,0,0,0.3);
    color:#fff;
}

form#register{margin:40px;}

label{font-family:sans-serif;font-size:18px;font-style:italic;}

input#name,input#dfield,input#datefield{
    width:300px;border:1px solid #ddd;border-radius:3px;
    padding:7px;background:#fff;
}
</style>

<?php
require_once('connection.php');
session_start();

$carid=$_GET['id'];
$sql="select * from cars where CAR_ID='$carid'";
$cname=mysqli_query($con,$sql);
$email=mysqli_fetch_assoc($cname);

$value=$_SESSION['email'];
$sql="select * from users where EMAIL='$value'";
$name=mysqli_query($con,$sql);
$rows=mysqli_fetch_assoc($name);

$uemail=$rows['EMAIL'];
$carprice=$email['PRICE'];

if(isset($_POST['book'])){

    $bplace=$_POST['place'];
    $bdate=$_POST['date'];
    $dur=$_POST['dur'];
    $phno=$_POST['ph'];
    $des=$_POST['des'];
    $rdate=$_POST['rdate'];

    if(empty($bplace)||empty($bdate)||empty($dur)||empty($phno)||empty($des)||empty($rdate)){
        echo "<script>alert('Please fill all fields');</script>";
    }
    else{
        if($bdate <= $rdate){
            $price = $dur * $carprice;

            $sql="INSERT INTO booking 
            (CAR_ID,EMAIL,BOOK_PLACE,BOOK_DATE,DURATION,PHONE_NUMBER,DESTINATION,PRICE,RETURN_DATE)
            VALUES
            ($carid,'$uemail','$bplace','$bdate',$dur,$phno,'$des',$price,'$rdate')";

            if(mysqli_query($con,$sql)){
                header("Location: payment.php");
                exit();
            }else{
                echo "<script>alert('Database error');</script>";
            }
        }
        else{
            echo "<script>alert('Return date must be after booking date');</script>";
        }
    }
}
?>

<div class="main">
    <div class="register">
        <h2>BOOKING</h2>

        <form id="register" method="POST">
            <h2>CAR NAME : <?php echo $email['CAR_NAME']; ?></h2>

            <label>BOOKING PLACE :</label><br>
            <input type="text" name="place" id="name" placeholder="Enter booking place"><br><br>

            <label>BOOKING DATE :</label><br>
            <input type="date" name="date" id="datefield" placeholder="Select booking date"><br><br>

            <label>DURATION :</label><br>
            <input type="number" name="dur" min="1" max="30" id="name" placeholder="Duration (in days)"><br><br>

            <label>PHONE NUMBER :</label><br>
            <input type="tel" name="ph" maxlength="10" id="name" placeholder="Enter phone number"><br><br>

            <label>DESTINATION :</label><br>
            <input type="text" name="des" id="name" placeholder="Enter destination"><br><br>

            <label>Return date :</label><br>
            <input type="date" name="rdate" id="dfield" placeholder="Select return date"><br><br>

            <input type="submit" class="btnn" value="BOOK" name="book">
<input type="button" class="btnn" value="EXIT" onclick="window.location.href='index.php';">
        </form>
    </div>
</div>

<script>
    var today = new Date().toISOString().split('T')[0];
    var dateField = document.getElementById("datefield");
    var returnField = document.getElementById("dfield");
    var durationField = document.querySelector('input[name="dur"]');

    // Set minimum dates
    dateField.setAttribute("min", today);
    returnField.setAttribute("min", today);

    // Update return date when booking date or duration changes
    function updateReturnDate() {
        var bookingDate = new Date(dateField.value);
        var duration = parseInt(durationField.value);

        if (!isNaN(bookingDate.getTime()) && !isNaN(duration)) {
            // Add duration days to booking date
            bookingDate.setDate(bookingDate.getDate() + duration);
            var returnDate = bookingDate.toISOString().split('T')[0];
            returnField.value = returnDate;
            returnField.setAttribute("min", returnDate); // prevent selecting earlier date
        }
    }

    dateField.addEventListener("change", updateReturnDate);
    durationField.addEventListener("input", updateReturnDate);
</script>


</body>
</html>
