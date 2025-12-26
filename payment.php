<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/all.min.css"
    />
    <script src="main.js"></script>
    <link rel="stylesheet" href="css/pay.css" />
    <title>Payment Form</title>

    <script type="text/javascript">
      function preventBack() {
        window.history.forward();
      }
      setTimeout("preventBack()", 0);
      window.onunload = function () { null };
    </script>
  </head>

<body>
<style>
@import url("https://fonts.googleapis.com/css?family=Poppins&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: orange url("images/paym.jpg") center/cover;
  overflow: hidden;
}

.card {
  margin-left: -500px;
  background: linear-gradient(
    to bottom right,
    rgba(255, 255, 255, 0.2),
    rgba(255, 255, 255, 0.05)
  );
  box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(0.8rem);
  padding: 1.5rem;
  border-radius: 1rem;
}

.card__row {
  display: flex;
  justify-content: space-between;
  padding-bottom: 2rem;
}

.card__title {
  font-size: 2.5rem;
  color: black;
  margin: 1rem 0 1.5rem;
}

.card__input {
  background: none;
  border: none;
  border-bottom: dashed 0.2rem rgba(255, 255, 255, 0.15);
  font-size: 1.2rem;
  color: #fff;
}

.card__input--large {
  font-size: 2rem;
}

.card__label {
  color: #fff;
}

.pay {
  width:200px;
  background: #ff7200;
  border:none;
  height: 40px;
  font-size: 18px;
  border-radius: 5px;
  cursor: pointer;
  color:white;
  margin-left: 100px;
}

.btn {
  width:200px;
  background: #ff7200;
  border:none;
  height: 40px;
  font-size: 18px;
  border-radius: 5px;
  cursor: pointer;
  color:white;
}

.payment {
  margin-top: -550px;
  margin-left: 1000px;
}
</style>

<?php
require_once('connection.php');
session_start();
$email = $_SESSION['email'];

$sql = "select * from booking where EMAIL='$email' order by BOOK_ID DESC";
$cname = mysqli_query($con,$sql);
$email = mysqli_fetch_assoc($cname);
$bid = $email['BOOK_ID'];
$_SESSION['bid'] = $bid;

if(isset($_POST['pay'])){
  $cardno = mysqli_real_escape_string($con,$_POST['cardno']);
  $exp = mysqli_real_escape_string($con,$_POST['exp']);
  $cvv = mysqli_real_escape_string($con,$_POST['cvv']);
  $price = $email['PRICE'];

  if(empty($cardno) || empty($exp) || empty($cvv)){
    echo '<script>alert("please fill the place")</script>';
  } else {
    $sql2="insert into payment (BOOK_ID,CARD_NO,EXP_DATE,CVV,PRICE)
           values($bid,'$cardno','$exp',$cvv,$price)";
    $result = mysqli_query($con,$sql2);
    if($result){
      header("Location: psucess.php");
    }
  }
}
?>

<h2 class="payment">TOTAL PAYMENT : ₹<?php echo $email['PRICE']?>/-</h2>

<div class="card">
  <form method="POST">
    <h1 class="card__title">Enter Payment Information</h1>

    <div class="card__row">
      <div class="card__col">
        <label class="card__label">Card Number</label>
        <input
          type="text"
          class="card__input card__input--large"
          name="cardno"
          maxlength="15"
          required
          onkeypress="return event.charCode >= 48 && event.charCode <= 57"
        />
      </div>
    </div>

    <div class="card__row">
      <div class="card__col">
        <label class="card__label">Expiry Date</label>
        <input
          type="text"
          class="card__input"
          name="exp"
          maxlength="5"
          required
          oninput="
            this.value = this.value
              .replace(/[^0-9]/g,'')
              .replace(/^(\d{2})(\d{1,2})$/,'$1/$2')
          "
          onblur="checkExpiry(this)"
        />
      </div>

      <div class="card__col">
        <label class="card__label">CVV</label>
        <input
          type="password"
          class="card__input"
          name="cvv"
          maxlength="3"
          required
          onkeypress="return event.charCode >= 48 && event.charCode <= 57"
        />
      </div>
    </div>

    <input type="submit" value="PAY NOW" class="pay" name="pay">
    <button class="btn"><a href="cancelbooking.php" style="color:white;text-decoration:none;">CANCEL</a></button>
  </form>
</div>

<!-- ✅ ONLY THIS SCRIPT ADDED -->
<script>
function checkExpiry(input) {
  if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(input.value)) {
    alert("Expiry must be in MM/YY format");
    input.value = "";
    input.focus();
    return;
  }

  let yy = parseInt(input.value.split('/')[1], 10);

  if (yy < 25) {
    alert("Card expiry year must be 25 or later");
    input.value = "";
    input.focus();
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script src="main.js"></script>
</body>
</html>
