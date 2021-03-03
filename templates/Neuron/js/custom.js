

  /*-------------------------------------------------------------------------------
    PRE LOADER
  -------------------------------------------------------------------------------*/

  $(window).on('load',function(){
    $('.preloader').fadeOut(1000); // set duration in brackets    
  });


  /*-------------------------------------------------------------------------------
    jQuery Parallax
  -------------------------------------------------------------------------------*/

    function initParallax() {
    $('#home').parallax("50%", 0.3);
  }
  initParallax();


  /* Back top
  -----------------------------------------------*/
  
  $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
        $('.go-top').fadeIn(200);
        } else {
          $('.go-top').fadeOut(200);
        }
        });   
        // Animate the scroll to top
      $('.go-top').click(function(event) {
        event.preventDefault();
      $('html, body').animate({scrollTop: 0}, 300);
      })

      function showerror(message)
      {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: message,
          scrollbarPadding: false,
          allowOutsideClick: false
      })
      }
      function showsuccess(message, static=true, redirectpage)
      {
        Swal.fire({
          icon: 'success',
          title: 'Success! :)',
          html: message,
          scrollbarPadding: false,
          confirmButtonText: 'Great!',
          allowOutsideClick: false
       }).then(() =>
      {
          if(!static)
            window.location.href = redirectpage;
      });}

      $("#comment_form").submit(function(event){
        event.preventDefault();
        var values = $(this).serialize();
        $.ajax({
          url:"core/blog.class.php",
          type: "post",
          data:values,
          beforeSend: function(){ $(".preloader").show(); },
          success: function(data){
            $(".preloader").hide();
            $("body").append(data);
            location.reload();
          }
        })
      });

      $("#contact_form").submit(function(event){
        event.preventDefault();
        var values = $("#contact_form").serialize();
        $.ajax({
          url:"core/main.class.php",
          type: "post",
          data:values,
          beforeSend: function(){ $(".preloader").show(); },
          success: function(data){
            $(".preloader").hide();
            $("body").append(data);
            //location.reload();
          }
        })
      });