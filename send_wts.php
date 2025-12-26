<?php
if(isset($_POST['name'], $_POST['phone'], $_POST['message'])){

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $text = "Name: $name\nMessage: $message";

    $url = "https://wa.me/91".$phone."?text=".urlencode($text);

    header("Location: ".$url);
    exit;
}
else{
    echo "Form data not received";
}
?>
