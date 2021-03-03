import * as globals from "./modules/rosance.js";

$(document).ready( () => {
    const $_GET = window.location.href;
    if($_GET.includes('readmail'))
    {
        const postID = window.location.pathname.replace("/dashboard/mailbox/readmail/","");
        console.log(postID);
        $.ajax({
            url:"pages/mailbox.php",
            dataType:"html",
            method:"post",
            data: {
                "action":"readmail",
                "postID": postID
            },
            success: (data) => {
                $("#content").html(data);
                $("#header").text("Mailbox :: Read mail");
                globals.getSubPage();
            }})
    }else if($_GET.includes('compose')){
        $.ajax({
            url:"pages/mailbox.php",
            dataType:"html",
            method:"post",
            data: {"action":"compose"},
            success: function(data){
                $("#content").html(data);
                $("#header").text("Mailbox :: Compose");
            }})
    }else if($_GET.includes('sent')){
        $.ajax({
            url:"pages/mailbox.php",
            dataType:"html",
            method:"post",
            data: {"action":"sent"},
            success: function(data){
                $("#content").html(data);
                $("#header").text("Mailbox :: Compose");
            }
        })
    }else{
        globals.getPage("pages/mailbox.php","Mailbox :: Inbox").then( () => globals.getSubPage());
    }
})

const loadInbox = (context) =>{
	$.ajax({
		url: context+'.php',
		dataType: 'html',
		cache:false,
		beforeSend: function(){ $("#mailboxLoader").show(); },
		success: function(data){ $("#mailboxLoader").hide(); $("#mailboxContainer").html(data); }
	})
}
const removeMails = () =>{
    const checkboxes = $(document).find(".checkedMail");
    let MailsToDelete = [];

    for (let index = 0; index < checkboxes.length; index++) {
        
        if($(checkboxes[index]).is(":checked"))
            MailsToDelete.push($(checkboxes[index]).val());
    }

    if(MailsToDelete.length == 0)
        return;

    Swal.fire
    ({
        title: 'Are you sure do you want to remove '+MailsToDelete.length+" mails ?",
        icon: 'question',
        cancelButtonText: 'No',
        cancelButtonColor: 'red',
        showConfirmButton: true,
        confirmButtonText: 'Yes!',
        showCancelButton: true
}).then((result) => {
if (result.value) {
    var values = {"action":"RemoveMails","MailsToRemove": JSON.stringify(MailsToDelete)};
    $.ajax({
        url:"pages/mailbox/inbox.php",
        method: "post",
        data: values,
        success: function(data){$("#mailInbox").html(data); loadInbox('pages/mailbox/inbox'); getsubpage();}
    });
    }
	});
}
window.removeMails = removeMails;
window.loadInbox = loadInbox;