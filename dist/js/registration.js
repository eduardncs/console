import Utils from "./modules/utils.js";
import * as rosance from "./modules/rosance.js";
import google from "./modules/google.js";
import facebook from "./modules/facebook.js";
window.google = google;
window.facebook = facebook;
$(window).resize((e) =>{
    adjustWidth();
})

$(document).ready((e) =>{
    google.init();
    const $_GET = Utils.HTTP_GET_ALL();
    if(typeof $_GET['action'] !== typeof undefined)
    {
        if($_GET['action'] === "locked")
        {
            rosance.getPage("pages/registration/locked.php");
        }
    }else{
        loadLogin();
    }
})

const loadLogin = async() => {
  rosance.getPage("pages/registration/login.php").then( _ => appendHandlers());
}
window.loadLogin = loadLogin;
const loadRegister = async () => {
  rosance.getPage("pages/registration/register.php").then( _ => appendHandlers());
}
window.loadRegister = loadRegister;

const appendHandlers = () =>{
    $("#signInWithGoogle").on("click",(e)=>{ 
        google.signIn();
    });
    $("#signInWithFacebook").on("click",(e)=>{
        facebook.signIn();
    });
}

const adjustWidth = _ => {
    if ($(window).width() < 960) {
      $("#container").removeClass("col-9");
      $("#container").addClass("col-12");
    }
    else {
      $("#container").removeClass("col-12");
      $("#container").addClass("col-9");
    }
  };
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '847597679307514',
      cookie     : true,
      xfbml      : true,
      version    : 'v9.0'
    });
      
    FB.AppEvents.logPageView();   
  };
  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  