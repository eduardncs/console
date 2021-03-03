<div id="mediamodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          <i class="fas fa-times" style="color:#FF3636;"></i>
        </button>
        <h4 class="title title-up">Project media files</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-2 border-right">
            <h4 class="text-center border-bottom">Menu</h4>
            <a href="javascript:void(0)" id="addmedia" class="btn btn-primary btn-pill btn-block btn-sm"> <i class="fas fa-plus"></i> Upload media</a><br>
            <h5 class="text-center border-bottom">Manage</h5>
            <a href="javascript:void(0)" id="sortby-allmedia" class="btn btn-default btn-pill btn-block btn-sm"> All media</a>
            <a href="javascript:void(0)" id="sortby-uploadedmedia" class="btn btn-default btn-pill btn-block btn-sm">From computer</a>
            <a href="javascript:void(0)" id="sortby-urlmedia" class="btn btn-default btn-pill btn-block btn-sm">From URL</a>
          </div>
          <div class="col-md-10 border-top">
                <!-- 
              -- Actual content of the modal
              -->              
              <div class="overlay" id="media_overlay" style="position:inherit;background:#fff;"><i style="position:absolute; top:50%; left:50%;" class="fas fa-2x fa-sync-alt fa-spin"></i></div>
              <div class="row" id="media-contents">
              </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<div id="addmediamodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Upload media</h4>
      </div>
      <div class="modal-body">
        <div class="card card-tabs border-top-0">
          <div class="card-header p-0 pt-1 border-bottom">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-three-computer-tab" data-toggle="pill" href="#custom-tabs-three-computer" role="tab" aria-controls="custom-tabs-three-computer" aria-selected="true">Upload from your computer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-url-tab" data-toggle="pill" href="#custom-tabs-three-url" role="tab" aria-controls="custom-tabs-three-url" aria-selected="false">Upload via URL</a>
          </li>
        </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-three-tabContent">
              <div class="tab-pane fade active show" id="custom-tabs-three-computer" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                <div class="form-group">
                  <div class="custom-file">
                  <form enctype="multipart/form-data">
                    <input type="file" class="custom-file-input form-control" id="customFile" accept="image/*">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                  </form>
                  </div>
                </div> <br>
                <div id="progress-wrp">
                  <div class="progress-bar"></div>
                  <div class="status">0%</div>
                </div>
                <br>
                <p class="border-top">
                  <small>All image file types are accepted</small><br>
                  <small>*Make sure your image files are not larger than 5MB</small>
                </p>
              </div>
          <div class="tab-pane fade" id="custom-tabs-three-url" role="tabpanel" aria-labelledby="custom-tabs-three-url-tab">
            <form id="url-link-form">
              <div class="form-group">
                <input type="text" id="url-link" name="url-link" class="form-control" placeholder="https://somesite.com/yourphoto.jpg">
              </div>
              <div class="text-center border">
                <img id="url-preview" src="images/placeholder.jpg" onError="this.onError=null;this.src='images/placeholder.jpg';" alt="Preview" class="image" style="width:100%; height:auto; max-height:300px;">
              </div>
                <br>
                <p>
                  <small>Because it's not stored on our server you can upload any size you wish to</small><br>
                  <small>*Big size images will slow down the loading of your website</small>
                </p>
                <div class="border"></div>
                <div class="text-center">
                  <button type="submit" id="url-upload" class="btn btn-block btn-default disabled" disabled="true">I want to use this media for my project</button>
                </div>
              </form>
          </div>
        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('#mediamodal').modal('show').draggable({handle: ".modal-header"});
  $(".modal-header").css("cursor","move");
  media.loadMedia(<?php if(isset($_POST['object'])) echo '"'.$_POST['object'].'"'; ?>);
});
$("#mediamodal").on("hidden.bs.modal", () =>{
  $("#media-master-container").empty();
});
$("#addmedia").on("click",() => {
    $('#addmediamodal').modal('show').draggable({handle: ".modal-header"});
    $(".modal-header").css("cursor","move");
});
$("#customFile").on("change", function (e) {
    media.uploadFile($(this));
  });
  $("#url-link").keyup(() =>{
    const uploadbtn = $("#url-upload");
    const url = $("#url-link").val();
    const preview = $("#url-preview");
    if(url == "")
    {
        uploadbtn.addClass("disabled");
        uploadbtn.attr("disabled","true");
        preview.src = url;
        return;
    }else{
        const tester = new Image();
        tester.src = url;

        tester.onload = () => {
          uploadbtn.removeClass("disabled");
          uploadbtn.removeAttr("disabled");
          preview.attr('src',url);
        }

        tester.onerror = () => {
          console.log("error");
          return;
        }
    }
});
$("#url-link-form").submit((e) => {
  e.preventDefault();
  media.uploadFileFromURL();
});

var removeMedia = (context, key) => {
  media.remove(context,key);
}

$(function(){
    var mediacontents = $("#media-contents");
    $("#sortby-allmedia").on("click",function(){
      mediacontents.find(".X-element").each(function(){
        $(this).fadeIn();
      })
    });
    $("#sortby-uploadedmedia").on("click", function(){
      mediacontents.find(".X-element").each(function(){
        $(this).fadeIn();
        if($(this).attr("uploadsource") == "url"){
          $(this).fadeOut();
        }
      })
    });
    $("#sortby-urlmedia").on("click", function(){
      mediacontents.find(".X-element").each(function(){
        $(this).fadeIn();
        if($(this).attr("uploadsource") == "computer"){
          $(this).fadeOut();
        }
      })
    });
})
    </script>