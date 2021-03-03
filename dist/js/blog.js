import * as globals from "./modules/rosance.js";
import * as media from "./modules/media.js";
window.media = media;
$(document).ready( function()
{
    const $_GET = window.location.href;
    if($_GET.includes("blog/overview"))
    {
        if($_GET.includes("blog/overview/author"))
        {
            globals.getPage("pages/blog/settings.php", "Blog :: Review author settings").then( () =>{
                appendHandlersAuthor();
                globals.getSubPage();
            })
        }else
        {
            globals.getPage("pages/blog/overview.php", "Blog :: Overview").then( () =>{
                appendHandlersOverview();
                globals.getSubPage();
            })
        }
    }else if($_GET.includes("blog/creative"))
    {
        if($_GET.includes("blog/creative/"))
        {
            const postID = window.location.pathname.replace("/blog/creative/","");
            if(postID == "")
                return;
            $.ajax({
                url: "pages/blog/creative.php?postid="+postID,
                type: "GET",
                beforeSend: () =>{$("#overlayy").show();},
                success: (data) =>{
                    $("#content").html(data);
                    $("#overlayy").hide();
                    $('#Header').html("Blog :: Edit post");
                    appendHandlersCreative();
                },
                error: (error) =>{
                    console.error(error);
                }
            });
            globals.getSubPage();
        }else{
            globals.getPage("pages/blog/creative.php", "Blog :: Unleash your creativity").then( () =>{
                appendHandlersCreative();
                globals.getSubPage();
            })
        }
    }
});

const appendHandlersAuthor = () =>{
    $("#profile_pic_preview").on("click",(e) =>{
        media.show("profile_pic");
    })
    $("#author").submit((event) =>{
        event.preventDefault();
        const serializedValues = $("#author").serializeArray();
        const values = {"action":"author-update", "data":{
            "profile_pic": serializedValues[0]['value'],
            "author_first_name": serializedValues[1]['value'],
            "author_last_name": serializedValues[2]['value'],
            "author_email": serializedValues[3]['value'],
            "author_description": serializedValues[4]['value']
        }};
        $.ajax({
            url: "processors/blog.req.php",
            type: "post",
            data: values,
            beforeSend: () =>{ $("#overlayy").show(); },
            success: (data) =>{
                $("#overlayy").hide();
                $("#requests").html(data);
            },
            error: (error) => { etoast("Something went wrong!"); }
        })
    });
}

const appendHandlersOverview = () => {

}

const appendHandlersCreative = () => {
    $("#opencategoryWidget").on("click",function(){
        $("#categoriesmodal").modal('hide');
        globals.getwidget('pages/blog/categories.php');
      });

      $("#choosemedia").on("click",(e) =>{
        $("#settingsmodal").modal('hide');
        media.show("post_image_id");
      });


      $("#post-new").submit(function(event) {
        event.preventDefault();
        let categories = [];

        $("#categories-list-primary .item").each(function(){
            let text = $(this).text();
            categories.push(text);
        });

        $("#categories-val").val(categories);

        const serializedValues = $("#post-new").serializeArray();
        let allow_featured = $("#allow-featured").is(":checked");
        let allow_comments = $("#allow-comments").is(":checked");
        let action = $("#action").val();
        let values;
        if(action == "edit-post")
        {
            values = {"action": action, "data": {
                "post-categories": $("#post-categories").val(),
                "post-title": $("#post-title").val(),
                "post-content": $("#post-content").val(),
                "post_image_id": $("#post_image_id").val(),
                "allow_comments": allow_comments,
                "allow_featured": allow_featured,
                "seo-slug": $("#seo-slug").val(),
                "seo-title": $("#seo-title").val(),
                "seo-description": $("#seo-description").val(),
                "categories": $("#categories-val").val(),
                "contentID": $("#contentID").val(),
                "post_id": $("#post_ID").val()
            }};
        }else{
            values = {"action": action, "data": {
                "post-categories": $("#post-categories").val(),
                "post-title": $("#post-title").val(),
                "post-content": $("#post-content").val(),
                "post_image_id": $("#post_image_id").val(),
                "allow_comments": allow_comments,
                "allow_featured": allow_featured,
                "seo-slug": $("#seo-slug").val(),
                "seo-title": $("#seo-title").val(),
                "seo-description": $("#seo-description").val(),
                "categories": $("#categories-val").val(),
            }};
        }
        $.ajax({
                url: "processors/blog.req.php",
                type: "post",
                data: values,
                beforeSend: () => {$("#overlayy").show();},
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

const removePost = (i) =>{
    Swal.fire
          ({
              title: 'Are you sure do you want to remove this article ?',
              icon: 'question',
              cancelButtonText: 'No',
              cancelButtonColor: 'red',
              showConfirmButton: true,
              confirmButtonText: 'Yes!',
              showCancelButton: true
      }).then((result) => {
     if (result.value) {
        $.ajax({
                url: "pages/blog/overview.php",
                type: "post",
                data: {"RemovePostsNo": i},
                beforeSend: () =>{$("#overlayy").show();},
                success: (data) =>{ 
                    setInterval(function() {$("#overlayy").hide(); },250);
                    $('#content').html(data);	
                    globals.getPage("pages/blog/overview.php", "Blog :: Overview");
                },
                error: (error) =>{
                    console.error(error);
                }
            })
        }
    });
}
window.removePost = removePost;