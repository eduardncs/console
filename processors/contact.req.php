<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header("Content-type: application/json");
if(isset($_POST["action"]) && $_POST['action'] === "sendEmail"){
    if(empty($_POST['data']['name']) or empty($_POST['data']['email']) or empty($_POST['data']['message']) or empty($_POST['data']['subject']))
        exit(json_encode(["error" => "Please fill out all fields"]));
    $subject = $_POST['data']['subject']." from ".$_POST['data']['name'];
    $message = $_POST['data']['message']." message sent from https://eduardncs.com by ".$_POST['data']['email'];
    $header = "From: admin@eduardncs.com \r\n";
    $header .= "MIME-Version: 1.0 \r\n";
    $header .= "Content-type: text/html\r\n";

    $return = mail("contact.eduard.ncs@gmail.com",$subject,$message, $header);
    if($return)
        exit(json_encode(["success" => "Thank you for contacting me. I will get back to you as soon as possible"]));
    else
        exit(json_encode(["error" => "Ooops , it's embarasing it looks like something went wrong , please use other contact method ..."]));
}
?>