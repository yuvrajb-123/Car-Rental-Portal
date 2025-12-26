<?php
require_once('connection.php');
session_start();

$bid = $_SESSION['bid'];

// payment + booking details join करून fetch
$sql = "SELECT p.*, b.EMAIL, b.PRICE 
        FROM payment p 
        JOIN booking b ON p.BOOK_ID = b.BOOK_ID
        WHERE p.BOOK_ID = $bid
        ORDER BY p.PAY_ID DESC LIMIT 1";

$result = mysqli_query($con, $sql);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment Receipt</title>
  <style>
    body{
      font-family: Arial;
      background:#f4f4f4;
    }
    .receipt{
      width:500px;
      margin:50px auto;
      background:#fff;
      padding:30px;
      border-radius:10px;
      box-shadow:0 0 10px rgba(0,0,0,0.2);
    }
    h2{
      text-align:center;
      color:green;
    }
    table{
      width:100%;
      margin-top:20px;
    }
    td{
      padding:8px;
    }
    .btn{
      margin-top:20px;
      display:inline-block;
      padding:10px 20px;
      background:#ff7200;
      color:#fff;
      text-decoration:none;
      border-radius:5px;
    }
  </style>
</head>
<body>

<div class="receipt">
  <h2>Payment Successful ✅</h2>

  <table>
    <tr>
      <td><b>Booking ID</b></td>
      <td><?php echo $data['BOOK_ID']; ?></td>
    </tr>
    <tr>
      <td><b>Email</b></td>
      <td><?php echo $data['EMAIL']; ?></td>
    </tr>
    <tr>
      <td><b>Card Number</b></td>
      <td>XXXX XXXX XXXX <?php echo substr($data['CARD_NO'], -4); ?></td>
    </tr>
    <tr>
      <td><b>Payment Date</b></td>
      <td><?php echo date("d-m-Y"); ?></td>
    </tr>
    <tr>
      <td><b>Total Amount</b></td>
      <td>₹<?php echo $data['PRICE']; ?>/-</td>
    </tr>
  </table>

  <center>
    <a href="index.php" class="btn">Go to Home</a>
    <button onclick="window.print()" class="btn">Print Receipt</button>
  </center>
</div>

</body>
</html>
