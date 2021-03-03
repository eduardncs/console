const facebook = {
  signIn: _ => {
    FB.login((response)=>{
      if (response.status === 'connected') 
      {
          const values = {"NCS_ACTION":"loginWithFacebook","NCS_TOKEN":response.authResponse.accessToken};
          $.ajax({
		            url: "processors/registration.req.php",
                type: "post",
                cache: false,
                data : values,
                beforeSend: function() { $("#overlay").show(); $("#content").hide(); },
                success: function(data){ $("#overlay").hide(); $("#content").show(); $('#ajax').html(data); },
                error: function() { notoast("Something went wrong with your authentication, please try again later!"); }
          });
      } else {
        itoast("Something went wrong or you refused to connect, please try again later !");
      }
    },{scope : 'public_profile,email'});
  }
}

export default facebook;