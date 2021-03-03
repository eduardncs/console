<?php 
session_start();
require_once("../../System/Database.class.php");
require_once("../../System/Data.class.php");
require_once("../../System/Project.class.php");
require_once("../../System/Callback.class.php");
require_once("../../System/Shop.class.php");
$data = new Data();
$project = new Project($_COOKIE['NCS_PROJECT']);
$shop = new Shop();
?>
<div id="categorymodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Categories</h4>
      </div>
      <div class="modal-body">
        <?php 
        $c = $shop->S_GetCategories($project);
        ?>
         <div class="row">
          <div class="col-md-2 border-right">
            <h4 class="text-center border-bottom">Menu</h4>
            <a href="javascript:void(0)" id="addcat_btn_main" class="btn btn-primary btn-pill btn-block btn-sm"> <i class="fas fa-plus"></i> Add category</a>
            <a href="javascript:void(0)" id="rmcat_btn_main" class="btn btn-danger btn-pill btn-block btn-sm"> <i class="fas fa-minus"></i> Remove category</a><br>

            <a href="javascript:void(0)" id="infocat_btn_main" class="btn btn-primary btn-pill btn-block btn-sm"> <i class="fas fa-question"></i> Info center</a><br>
          </div>
          <div class="col-md-10 border-top">
            <div class="row">
                <?php 
                $keys = array_keys($c);
                foreach($keys as $key)
                {
                ?>
                <div class="col-md-3 pt-3">
                  <div class="card">
                    <div class="card-header">
                      <b><?php echo $c[$key]['Parent']['Name']; ?></b>
                    </div>
                    <div class="card-body p-0">
                    <ul class="nav flex-column">
                      <?php
                        foreach($c[$key]['Children'] as $s)
                        {
                      ?>
                      <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link">
                          <i class="fas fa-chevron-right"></i>
                          <?php echo $s['Name']; ?>
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                    </div>
                  </div>
                </div>
                <?php 
                }
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="create_category_modal_info" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">How categories work ?</h4>
      </div>
      <div class="modal-body">
        <h4>What is a category ?</h4>
        <p>
          On Rosance context category refeers to a container that will hold several items.
          Categories are divided into 2 sections . Parent category and Chield category. <br>
          Parent categories -> Are just header title for chield categories <br>
          Chield categories -> Containers for items. <br>
          Example : 
        </p>
        <p>
          <blockquote>
            Parent category : "MEN" -> Will act as a header. <br>
            Children categories: "T-SHIRTS","TROUSERS","SHOES" -> Will contain links and filters for your products
          </blockquote>
        </p>
      </div>
    </div>
  </div>
</div>
<div id="create_category_modal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Create category</h4>
      </div>
      <div class="modal-body">
                <form id="create_cat_form">
                  <div class="form-group">
                    <input type="text" class="form-control elevation-2" name="cat_name" id="cat_name" placeholder="Category name">
                  </div>
                  <div class="form-group">
                    <label for="isparent">Is this a parent category ?</label>
                    <select class="form-control" name="isparent" id="isparent" required>
                    <option selected="" disabled="">Select one</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                    </select>
                  </div>
                  <div class="form-group" id="p_cont" style="display:none;">
                    <label for="parent">Who is this category parent ?</label>
                    <select class="form-control" name="parent" id="parent">
                      <option selected="" disabled="">Select one</option>
                      <?php 
                      foreach($keys as $key)
                      {
                      ?>
                      <option value="<?php echo $c[$key]['Parent']['ID']; ?>"><?php echo $c[$key]['Parent']['Name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <button type="submit" id="submit_btn_cat_new" class="btn btn-block btn-primary disabled" disabled>Add new category</button>
                  </div>
                </form>
      </div>
    </div>
  </div>
</div>

<div id="remove_category_modal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Remove Category</h4>
      </div>
      <div class="modal-body">
        <form id="cat_remove_form">
          <div class="form-group">
          <input type="hidden" name="action" value="remove">
            <label for="productCategories">Category to remove</label>
                <select class="form-control" id="cat_id" name="cat_id">
                  <option selected="" disabled="">Select one</option>
                <?php 
                foreach($c as $parent)
                {
                    echo "<option value='".$parent['Parent']['ID']."'>".$parent['Parent']['Name']."</option>";
                    foreach($parent['Children'] as $chield)
                    {
                        echo "<option value='".$chield['ID']."'>".$chield['Name']."</option>";
                    }
                }
                  ?>
              </select>
          </div>
          <input type="submit" value="Remove category" id="remove_cat_btn" name="remove_cat_btn" class="btn btn-danger btn-block disabled" disabled>
          </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$("#categorymodal").modal("show").draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});

$("#addcat_btn_main").on("click",function(){
    $("#create_category_modal").modal("show").draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});
});
$("#infocat_btn_main").on("click",function(){
    $("#create_category_modal_info").modal("show").draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});
})
$("#rmcat_btn_main").on("click",function(){
  $("#remove_category_modal").modal("show").draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});
})
$("#isparent").change(function(){
  var sel = $("#isparent").val();
  if(sel == "true")
    $("#p_cont").fadeOut();
  else
    $("#p_cont").fadeIn();
})
$("#create_cat_form").change(function(){
var name = $("#cat_name").val();
var isp = $("#isparent").val();
var par = $("#parent").val();
if(!name || !isp)
{
  $("#submit_btn_cat_new").attr("disabled");
  if(!$("#submit_btn_cat_new").hasClass("disabled"))
    $("#submit_btn_cat_new").addClass("disabled");
}
else if(name && isp)
{
  if(isp=="false" && !par)
  {
    $("#submit_btn_cat_new").attr("disabled");
    if(!$("#submit_btn_cat_new").hasClass("disabled"))
      $("#submit_btn_cat_new").addClass("disabled");
  }else{
    $("#submit_btn_cat_new").removeAttr("disabled");
    $("#submit_btn_cat_new").removeClass("disabled");
  }
}
})
$("#create_cat_form").submit(function(event){
  event.preventDefault();
  var values = $("#create_cat_form").serialize();
  $.ajax({
    url:"System/Shop.class.php",
    dataType:"html",
    method:"post",
    data: values,
    beforeSend: function(){$("#categorymodal").modal('hide');$("#create_category_modal").modal('hide');},
    success: function(data){
      $("#ajax").html(data);
    }
  })
})
$("#cat_remove_form").submit(function(event){
  event.preventDefault();
  var values = $("#cat_remove_form").serialize();
  $.ajax({
    url:"System/Shop.class.php",
    dataType:"html",
    method:"post",
    data: values,
    beforeSend: function(){$("#categorymodal").modal('hide');$("#remove_category_modal").modal('hide');},
    success: function(data){
      $("#ajax").html(data);
    }
  })
})
$("#cat_id").change(function(){
var val = $(this).val();
if(val == "" || val == null)
{
  $("#remove_cat_btn").attr("disabled");
  if(!$("#remove_cat_btn").hasClass("disabled"))
    $("#remove_cat_btn").addClass("disabled");
}
else
{
  $("#remove_cat_btn").removeAttr("disabled");
  $("#remove_cat_btn").removeClass("disabled");
}
});
</script>