import * as globals from "./modules/rosance.js";
import * as media from "./modules/media.js";
window.media = media;
$(document).ready( (e) =>{
    const $_GET = window.location.href;
    if($_GET.includes("general"))
    {
        globals.getPage("pages/settings/general.php", "Settings :: General settings").then( () => {
            appendHandlersGeneral();
            globals.getSubPage();
        });
    }else if($_GET.includes("account")){
        globals.getPage("pages/settings/account.php", "Settings :: Account settings").then( () => {
            appendHandlersAccount();
            globals.getSubPage();
        });
    }else if($_GET.includes("legal"))
    {
        globals.getPage("pages/settings/legal.php", "Settings :: Legal settings").then( () => {
            appendHandlersLegal();
            globals.getSubPage();
        });
    }
})
const appendHandlersGeneral = () =>{
    $("#logo-preview").on("click",(e) =>{
        media.show({"object":"logo"});
    })
    $("#favicon_thumbnail").on("click",(e) =>{
        media.show({"object":"favicon"});
    })
    $("#general-settings").submit((event) =>{
        event.preventDefault();
        const serializedValues = $("#general-settings").serializeArray();
        console.log(serializedValues);
        const values = {"action":"general-settings", "data": {
            'website_name': serializedValues[0]['value'],
            'website_curency': serializedValues[1]['value'],
            'logo': serializedValues[2]['value'],
            'favicon': serializedValues[3]['value'],
            'google-maps': serializedValues[4]['value']
        }};
        $.ajax({
             url: "processors/settings.req.php",
             type: "post",
             data: values,
             beforeSend: () =>{ $("#overlayy").show();},
             success: (data) =>{ 
                 setInterval(function() {$("#overlayy").hide(); },250);
                $('#requests').html(data);
            },
            error: (error) =>{
                console.error(error);
            }
          });
    });
}
const appendHandlersAccount = () =>{
    $("#profile_pic_preview").on("click", (e) => {
        media.show({"object":"profile_pic"});
    })
    $("#account-settings").submit((event)=>{
        event.preventDefault();
        const serializedValues = $("#account-settings").serializeArray();
        const values = {"action":"account-settings",
        "data" : {
            "profile_pic": serializedValues[0]['value'],
            "first_name": serializedValues[1]['value'],
            "last_name": serializedValues[2]['value']
         }};
        $.ajax({
		   url: "processors/settings.req.php",
		   type: "post",
		   data: values,
		   beforeSend: () =>{$("#overlayy").show();},
		   success: (data) =>{ 
               setInterval(() =>{ $("#overlayy").hide(); },250);
				$('#requests').html(data);
                globals.getSubPage(); 
            },
            error: (error) =>{
                console.error(error);
            }
        });
    })
    $("#removeAccount").on("click",(e)=>{
        preRemoveAccount().then(
            (result) => {
                console.log(result);
            }
        );
    })
}

const appendHandlersLegal = () => {
    $("#legal-documents").submit(function(event) {
        event.preventDefault();
        const serializedValues = $("#legal-documents").serializeArray();
        const values = {"action":"legal-settings",
        "data" : {
            "GDPR": serializedValues[0]['value'],
            "TOS": serializedValues[1]['value'],
            "DSCL": serializedValues[2]['value']
        }};
        $.ajax({
            url: "processors/settings.req.php",
            type: "post",
            data: values,
            beforeSend: () =>{$("#overlayy").show();},
            success: (data) =>{ 
                setInterval(() =>{ $("#overlayy").hide(); },250);
                $('#requests').html(data);
                globals.getSubPage(); 
            },
            error: (error) =>{
                console.error(error);
            }
        });
    })
}
const preRemoveAccount = async () => {
    await Swal.fire
	({
		title: 'Are you sure do you want to delete your account ?',
        html: 'All your projects and data will be lost forever',
		icon: 'warning',
		cancelButtonText: 'No',
		cancelButtonColor: 'red',
		showConfirmButton: true,
		confirmButtonText: 'Yes!',
		showCancelButton: true
	}).then( (result) => {
        return result;
    }).catch((error) => {
        return error;
    });
}

const deleteproject = (projectname) =>{
    return Swal.fire({
    icon: 'question',
    title: 'Are you sure you want to delete this project ?',
    scrollbarPadding: false,
    confirmButtonText: 'Yes!',
    showCancelButton: true,
    cancelButtonColor: 'red',
    cancelButtonText: 'NO',
    allowOutsideClick: false
}).then((result) => {
if (result.value) {
     $.ajax({
        url: 'pages/overview.php?projecttodelete='+projectname,
        type: "GET",
        beforeSend: function(){$("#overlayy").show();},
        success: function(data){
        $("#content").empty();
        setInterval(function() {$("#overlayy").hide(); },250);
        $("#content").html(data);
        $("projectsdropdown").empty();
        globals.getSubPage();
        }
})}
});
}
window.deleteproject = deleteproject;