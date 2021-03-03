<?php
session_start();
require_once("../system/Database.class.php");
require_once("../system/Data.class.php");
require_once("../system/User.class.php");
$data = new Data();
$user = $data->getUser($_SESSION['loggedIN']);
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']))
{
    $callback = new Callback();
    $subject = "Contact Rosance ->".$_POST['subject'];
    $message = $_POST['message'];
    $header = "From:".$_POST['email']." \r\n";
    $header .= "MIME-Version: 1.0 \r\n";
    $header .= "Content-type: text/html\r\n";

    $return = mail("contact.eduard.ncs@gmail.com",$subject,$message, $header);
    $return = mail("contact@rosance.com",$subject,$message, $header);
    if($return)
        return $callback->SendSuccessOnMainPage("Message successfully sent! Thank you for contacting us. We will answer as soon as possible on the E-Mail adress you provided us!");
    return $callback->SendErrorOnMainPage("Message could not be sent! Please try again! If problem pesist please contact us at the following E-Mail adresses: contact.eduard.ncs@gmail.com / contact@rosance.com");
}
?>
<form id="contact_formz">
<div class="card card-outline card-info">
    <div class="card-body">
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Your Email Adress" value="<?php if($user->Email != "") echo $user->Email; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="Your Name" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
        </div>
        <div>
            <textarea class="textarea" placeholder="Message"
                    style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="message" required></textarea>
        </div>
    </div>
    <div class="card-footer clearfix">
        <button type="submit" class="float-right btn btn-default disabled" disabled id="submitBtn">Send
            <i class="fas fa-arrow-right"></i>
    </button>
    </div>
</div>
</form>
<script>
var form = $("#contact_formz");
var submitBtn = $("#submitBtn");
form.submit(function(event){
    event.preventDefault();
    var values = $("#contact_formz").serialize();
    console.log(values);
    $.ajax({
        url: 'pages/contact.php',
        dataType: 'html',
        method: 'post',
        data: values,
        beforeSend: function(){ 
            $("#overlayy").show();
         },
        success: function(data){
            $("#overlayy").hide();
            $("#content").html(data);
            console.log(values);
        }
    })
})
form.change(function(){
    var allok = true;
    var values = form.serializeArray();
    var elements = [];
    values.forEach(element => {
        if(element.value == "")
            elements.push(false);
        else
            elements.push(true);
    });
    for (let index = 0; index < elements.length; index++) {
        const element = elements[index];
        if(element == false)
            allok = false;
        else
            continue;
    }
    if(allok){
        submitBtn.removeClass('disabled');
        submitBtn.removeAttr('disabled');
    }
    else
    {
        if(!submitBtn.hasClass('disabled'))
            submitBtn.addClass('disabled');
        submitBtn.attr('disabled');
    }
})
</script>
