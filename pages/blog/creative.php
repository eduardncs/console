<?php
session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Blog;
use Rosance\Project;
use Rosance\User;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$blog = new Blog();
if(isset($_GET['postid']))
{
  //////////////////////////////////////////- Begin edit article - /////////////////////////////////////////////
  $post = $blog->GetPost($user,$project, $_GET['postid']);
  ?>
<form id="post-new">
  <div id="requests"></div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body text-center">
            <button type="button" data-toggle="tooltip" id="settings" data-placement="bottom" title="Settings" class="btn btn-default btn-circle elevation-2"><i class="fas fa-cog"></i></button>
            &nbsp;
            <button type="button" data-toggle="tooltip" id="seo" data-placement="bottom" title="SEO" class="btn btn-default btn-circle elevation-2"><i class="fas fa-search"></i></button>
            &nbsp;
            <button type="button" data-toggle="tooltip" id="category" data-placement="bottom" title="Categories" class="btn btn-default btn-circle elevation-2"><i class="fas fa-barcode"></i></button>
            <input type="hidden" name="post_id" value="<?php echo $_GET['postid']; ?>" id="post_ID">
            <input type="hidden" name="action" id="action" value="edit-post">
            <button type="submit"  data-toggle="tooltip" id="publish" data-placement="bottom" title="Edit this post" class="btn btn-primary btn-circle elevation-2 float-right"><i class="fas fa-check"></i></button>
      </div>
  </div>
  <div class="col-md-12">
    <div class="card card-outline card-primary">
      <div class="card-body">
        <div class="form-group pr-5 pl-5 pt-2 pb-1">
          <input type="hidden" name="post-categories" id="post-categories" value="">
          <input type="hidden" name="contentID" value="<?php echo $post->contentID; ?>" id="contentID">
          <input type="text" name="post-title" value="<?php echo $post->title; ?>" required class="form-control" style="border:none; border-bottom:1px solid gray;" id="post-title" placeholder="An amazing title for an amazing post">
        </div>
        <div class="form-group pr-5 pl-5 pt-1">
          <textarea name="post-content" class="form-control" rows="40" id="post-content">
            <?php echo $post->content; ?>
          </textarea>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="settingsmodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Post general settings</h4>
      </div>
      <div class="modal-body justify-content-center">
      <ul id="settings-list" class="products-list product-list-in-card">
                  <li class="item pl-2 pr-2">
                    <div class="product-img">
                      <input type="hidden" id="post_image_id" value="<?php echo $post->thumbnail; ?>" name="post_image_id">
                      <a href="javascript:void(0)" id="choosemedia"><img src="<?php echo $post->thumbnail; ?>" id="post_image_thumbnail" onError="this.onError=null;this.src='images/placeholder-error.svg';" alt="Product Image" class="img-size-64" data-toggle="tooltip" data-placement="bottom" title="Add media"></a>
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Should this post have a cover photo ?</a>
                      <span class="product-description text-sm">
                        You can choose a photo from your project media
                      </span>
                    </div>
                  </li>
                  <li class="item pl-2 pr-2">
                    <div class="">
                      <a href="javascript:void(0)" class="product-title">Allow users to comment ?</a>
                      <div class="float-right mt-2 mr-4 align-items-md-start">
                      <input id="allow-comments" name="allow_comments" type="checkbox" <?php if ($post->comments == "true") echo "checked"; ?> class="form-control mr-3">
                      </div>
                      <span class="product-description text-sm">
                        You can always change your mind later
                      </span>
                    </div>
                  </li>
                  <li class="item pl-2 pr-2">
                    <div class="">
                      <a href="javascript:void(0)" class="product-title">Set this as featured post ?</a>
                      <div class="float-right mt-2 mr-4 align-items-md-start">
                      <input id="allow-featured" name="allow_featured" type="checkbox" <?php if ($post->featured == "true") echo "checked"; ?> class="form-control mr-3">
                      </div>
                      <span class="product-description text-sm">
                        Featured posts appear on your home page
                      </span>
                    </div>
                  </li>
                    </ul>
      </div>
      <div class="modal-footer">
        <small>After you finish editing just close this window</small>
      </div>
    </div>
  </div>
</div>

<div id="seomodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Post SEO options</h4>
      </div>
      <div class="modal-body justify-content-center">
      <ul id="posts-list" class="products-list product-list-in-card">
                  <li class="item pl-2 pr-2">
                    <label for="seo-slug">URL slug <small>( Only low case alphanumerical characters)</small></label>
                      <input id="seo-slug" value="<?php echo $post->seo_slug; ?>" name="seo-slug" type="text" class="form-control" placeholder="some-awesome-content">
                  </li>
                  <li class="item pl-2 pr-2">
                      <label for="seo-title" >Title on search engines</label>
                      <input id="seo-title" value="<?php echo $post->seo_title; ?>" name="seo-title" type="text" class="form-control" placeholder="This is the first thing people see when they find this post on Google">
                  </li>
                  <li class="item pl-2 pr-2">
                  <label for="seo-description" >Insert a small description <small>( Max 300 characters )</small> </label>
                      <textarea id="seo-description" name="seo-description" class="form-control" placeholder="This description will be automatically generated by search engines. To override that description, enter a description here."><?php echo $post->seo_description; ?></textarea>
                  </li>
                    </ul>
      </div>
      <div class="modal-footer">
        <i class="text-sm">Preview on Google </i>
        <div id="preview" class="border p-2">
        <a href="javascript:void(0)" style="color:#2945AB;"><strong id="seo-title-preview">Your SEO title</strong></a><br>
        <small><a href="javascript:void(0)" style="color:#3B7537;">https://yoursite.com/posts/<span id="seo-slug-preview">this-is-the-content</span></a></small><br>
        <small class="text-muted" id="seo-description-preview">Some description</small>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="categoriesmodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Categories</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 border-top pt-3">
                <ul id="categories-list-primary" class="products-list product-list-in-card">
                  <li class="pl-2 pr-2 text-center">
                  <input type="hidden" name="categories" id="categories-val">
                    <a href="javascript:void(0)" class="btn btn-block btn-default btn-pill" id="opencategoryWidget">Open categories</a>
                  </li>
                  <?php
                  $items = explode(",",$post->categories);
                  foreach($items as $item){
                  ?>
<li class="item pl-2 pr-2 text-center"><b><?php echo $item; ?></b><a href="javascript:void(0)" onClick="$(this).parent().remove()" class="btn btn-xs btn-pill btn-danger float-right mr-3"><i class="fas fa-times"></i></a></li>
                  <?php } ?>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
  <?php
  //////////////////////////////////////////- Finish edit article - /////////////////////////////////////////////
} else {
  //////////////////////////////////////////- Begin post new article - /////////////////////////////////////////////
?>
<form id="post-new">
  <div id="requests"></div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body text-center">
            <button type="button" data-toggle="tooltip" id="settings" data-placement="bottom" title="Settings" class="btn btn-default btn-circle elevation-2"><i class="fas fa-cog"></i></button>
            &nbsp;
            <button type="button" data-toggle="tooltip" id="seo" data-placement="bottom" title="SEO" class="btn btn-default btn-circle elevation-2"><i class="fas fa-search"></i></button>
            &nbsp;
            <button type="button" data-toggle="tooltip" id="category" data-placement="bottom" title="Categories" class="btn btn-default btn-circle elevation-2"><i class="fas fa-barcode"></i></button>
            <input type="hidden" name="action" id="action" value="post-new">
            <button type="submit"  disabled data-toggle="tooltip" id="publish" data-placement="bottom" title="Publish this post" class="btn btn-primary btn-circle elevation-2 float-right disabled"><i class="fas fa-check"></i></button>
      </div>
  </div>
  <div class="col-md-12">
    <div class="card card-outline card-primary">
      <div class="card-body">
        <div class="form-group pr-3 pl-3 pt-2 pb-1">
          <input type="hidden" name="post-categories" id="post-categories" value="">
          <input type="text" name="post-title" required class="form-control" style="border:none; border-bottom:1px solid gray;" id="post-title" placeholder="An amazing title for an amazing post">
        </div>
        <div class="form-group pr-3 pl-3 pt-1">
          <textarea name="post-content" class="form-control" rows="40" id="post-content">
            State of the art content 
          </textarea>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="settingsmodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Post general settings</h4>
      </div>
      <div class="modal-body justify-content-center">
      <ul id="settings-list" class="products-list product-list-in-card">
                  <li class="item pl-2 pr-2">
                    <div class="product-img">
                      <input type="hidden" id="post_image_id" name="post_image_id">
                      <a href="javascript:void(0)" id="choosemedia"><img src="images/placeholder.jpg" id="post_image_thumbnail" onError="this.onError=null;this.src='images/placeholder.jpg';" alt="Product Image" class="img-size-64" data-toggle="tooltip" data-placement="bottom" title="Add media"></a>
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Should this post have a cover photo ?</a>
                      <span class="product-description text-sm">
                        You can choose a photo from your project media
                      </span>
                    </div>
                  </li>
                  <li class="item pl-2 pr-2">
                    <div class="">
                      <a href="javascript:void(0)" class="product-title">Allow users to comment ?</a>
                      <div class="float-right mt-2 mr-4 align-items-md-start">
                      <input id="allow-comments" name="allow_comments" type="checkbox" checked class="form-control mr-3">
                      </div>
                      <span class="product-description text-sm">
                        You can always change your mind later
                      </span>
                    </div>
                  </li>
                  <li class="item pl-2 pr-2">
                    <div class="">
                      <a href="javascript:void(0)" class="product-title">Set this as featured post ?</a>
                      <div class="float-right mt-2 mr-4 align-items-md-start">
                      <input id="allow-featured" name="allow_featured" type="checkbox" checked class="form-control mr-3">
                      </div>
                      <span class="product-description text-sm">
                        Featured posts appear on your home page
                      </span>
                    </div>
                  </li>
                    </ul>
      </div>
      <div class="modal-footer">
        <small>After you finish editing just close this window</small>
      </div>
    </div>
  </div>
</div>

<div id="seomodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Post SEO options</h4>
      </div>
      <div class="modal-body justify-content-center">
      <ul id="posts-list" class="products-list product-list-in-card">
                  <li class="item pl-2 pr-2">
                      <label for="seo-slug">URL slug <small>( Only low case alphanumerical characters)</small></label>
                      <input id="seo-slug" name="seo-slug" type="text" class="form-control" placeholder="some-awesome-content" >
                  </li>
                  <li class="item pl-2 pr-2">
                      <label for="seo-title" >Title on search engines</label>
                      <input id="seo-title" name="seo-title" type="text" class="form-control" placeholder="This is the first thing people see when they find this post on Google" >
                  </li>
                  <li class="item pl-2 pr-2">
                      <label for="seo-description" >Insert a small description <small>( Max 300 characters )</small> </label>
                      <textarea id="seo-description" name="seo-description" class="form-control" placeholder="This description will be automatically generated by search engines. To override that description, enter a description here." maxlength="300"></textarea>
                  </li>
                    </ul>
      </div>
      <div class="modal-footer">
        <i class="text-sm">Preview on Google </i>
        <div id="preview" class="border p-2">
        <div><a href="javascript:void(0)" style="color:#2945AB;""><strong id="seo-title-preview" style="word-wrap: break-word;">Your SEO title</strong></a><br></div>
        <div><small style="word-wrap: break-word;"><a href="javascript:void(0)" style="color:#3B7537;">https://yoursite.com/posts/<span id="seo-slug-preview">this-is-the-content</span></a></small><br></div>
        <div><small style="word-wrap: break-word;" class="text-muted" id="seo-description-preview">Some description</small></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="categoriesmodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Categories</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 border-top pt-3">
                <ul id="categories-list-primary" class="products-list product-list-in-card">
                  <li class="pl-2 pr-2 text-center">
                  <input type="hidden" name="categories" id="categories-val">
                    <a href="javascript:void(0)" class="btn btn-block btn-default btn-pill" id="opencategoryWidget">Open categories</a>
                  </li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
<?php }
//////////////////////////////////////////- Finish post article - /////////////////////////////////////////////
?>
<script type="text/javascript">
$(document).ready((e) =>{
  let a = $("#seo-slug").val();
  let b = $("#seo-title").val();
  let c = $("#seo-description").val();
  if(a != "")
    $("#seo-slug-preview").text(a);
  if(b != "")
    $("#seo-title-preview").text(b);
  if(c != "")
    $("#seo-description-preview").text(c);

  $('#post-content').summernote({
    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Roboto'],
    fontNamesIgnoreCheck: ['Roboto'],
    height: 300
  });

$('[data-toggle="tooltip"]').tooltip();

  $("#settings").on("click", (e) =>{
    $('#settingsmodal').modal('show').draggable({handle: ".modal-header"});
  });
  $("#seo").on("click", (e) =>{
    $('#seomodal').modal('show').draggable({handle: ".modal-header"});
  });
  $("#settings-list .item").each(function(e){
    $(this).hover(
      function(e){
        $(this).find(".form-control").stop(false,false).fadeIn("slow");
      },
      function(e){
        $(this).find(".form-control").stop(false,false).fadeOut("slow");
    })
  });

  $("#category").on("click",function(){
    $('#categoriesmodal').modal('show').draggable({handle: ".modal-header"});
  });
});
  $("#post-title").keyup( function(){
    let publish = $("#publish");
    let title = $(this).val();
  if(title == ""){
    publish.addClass("disabled");
    publish.attr("disabled",'true');
  }else{
    publish.removeClass("disabled");
    publish.removeAttr("disabled");
  }})

const selectMedia = (id) =>{
  $('#settingsmodal').modal('show').draggable({handle: ".modal-header"});
  $("#post_image_id").val(id);
  $("#post_image_thumbnail").attr('src',id);
}

const selectCategory = (x) =>{
  $("#categorymodal").modal('hide');
  $("#categoriesmodal").modal('show');
  var a = false;
  let t = $('<li class="item pl-2 pr-2 text-center"><b>'+x+'</b><a href="javascript:void(0)" onClick="$(this).parent().remove()" class="btn btn-xs btn-pill btn-danger float-right mr-3"><i class="fas fa-times"></i></a></li>');
  $("#categories-list-primary .item").each(function(){
    if($(this).text() == x)
      a = true;
  });
  if(!a)
  {
    $("#categories-list-primary").append(t);
    return toast("Category &nbsp;<i> "+x+" </i>&nbsp; selected");
  }
  else
    return etoast("Category &nbsp;<i>"+x+"</i> &nbsp; allready selected");
}
  $("#seo-slug").keyup(function(){
    var string = $("#seo-slug").val();
    string = string.replace(" ", "-").toLowerCase();
    $("#seo-slug").val(string);
    $("#seo-slug-preview").text(string);
  });
  $("#seo-title").keyup(function(){
    $("#seo-title-preview").text($("#seo-title").val());
  });
  $("#seo-description").keyup(function(){
    $("#seo-description-preview").text($("#seo-description").val());
  });
</script>
