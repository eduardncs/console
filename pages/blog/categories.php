<?php 
session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Project;
use Rosance\Blog;
$data = new Data();
$project = new Project($_COOKIE['NCS_PROJECT']);
$blog = new Blog();
$categories = $blog->GetCategories($project);
?>
<div id="categorymodal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Categories</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-3 border-right">
                <h4 class="text-center border-bottom">Menu</h4>
                <a href="javascript:void(0)" id="createCategoryTrigger" class="btn btn-primary btn-pill btn-sm btn-block">Create category</a>
                
                <a href="javascript:void(0)" id="removeCategoryTrigger" class="btn btn-danger btn-pill btn-sm btn-block">Remove category</a>
            </div>
            <div class="col-md-9 border-top pt-3">
                <ul id="categories-list" class="products-list product-list-in-card">
        <?php
        if($categories->num_rows == 0)
        {
          ?>
            <li class="item pl-2 pr-2 text-center">
                <p class="text-center">You have no categories created</p>
            </li>
        <?php
        }else{
            while($row = mysqli_fetch_assoc($categories)){
        ?>
            <li class="item pl-2 pr-2 text-center">
                <div class="float-right pt-1 pr-3">
                    <a href="javascript:void(0)" class="btn btn-pill btn-default" onClick="selectCategory('<?php echo $row['Category_Name']; ?>')" cat_id="<?php echo $row['ID']; ?>" value=`<?php echo $row['Category_Name'] ?>`> Add </a>
                </div>
                <p class="text-left ml-3">
                    <b><?php echo $row['Category_Name']; ?></b>
                </p>
            </li>
        <?php }}?>
        </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="createCategoryModal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Create categories</h4>
      </div>
      <div class="modal-body justify-content-center">
        <form id="createCategoryForm">
          <div class="form-group">
            <input type="text" class="form-control elevation-2" name="categoryName" id="categoryName" placeholder="Category name , ex: Food , Travel , Fitness ...">
          </div>
          <div class="form-group border-top pt-4">
            <button type="submit" id="submitCrateCategory" disabled="true" class="btn btn-default btn-block btn-pill elevation-1 disabled">Create category</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div id="removeCategoryModal" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Remove categories</h4>
      </div>
      <div class="modal-body justify-content-center">
        <form id="removeCategoryForm">
          <div class="form-group">
              <select name="category-to-remove" id="category-to-remove" class="form-control elevation-2">
                  <option value="" selected> Nothing selected </option>
                  <?php
                    $categories = $blog->GetCategories($project);
                    while($rowz= mysqli_fetch_assoc($categories)){
                  ?>
                    <option value="<?php echo $rowz['Category_Name']; ?>"><?php echo $rowz['Category_Name']; ?></option>
                    <?php } ?>
              </select>
          </div>
          <div class="form-group border-top pt-4">
            <button type="submit" id="submitRemoveCategory" disabled="true" class="btn btn-danger btn-block btn-pill elevation-1 disabled">Remove category</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#categorymodal").modal("show").draggable({handle: ".modal-header"});

    $("#createCategoryTrigger").on("click",function(){
        $("#createCategoryModal").modal("show").draggable({handle: ".modal-header"});
    });

    $("#removeCategoryTrigger").on("click",function(){
        $("#removeCategoryModal").modal("show").draggable({handle: ".modal-header"})
    })

    $("#createCategoryForm").submit(function(event) {
    event.preventDefault();
    const values = {"action":"createCategory","data": {
        "categoryName": $("#categoryName").val()
      }};
      $.ajax({
		   url: "processors/blog.req.php",
		   type: "post",
		   data: values,
		   success: function(data){ $('#requests').html(data);}
        });
    });

    $("#removeCategoryForm").submit(function(event) {
    event.preventDefault();
    const values = {"action":"removeCategory","data":{
      "categoryToRemove": $("#category-to-remove").val()
    }};
      $.ajax({
		   url: "processors/blog.req.php",
		   type: "post",
		   data: values,
		   success: function(data){ $('#requests').html(data);}
        });
    });

});

$(function(){
  var input = $("#categoryName");
  var submit = $("#submitCrateCategory");
  input.keyup(function(){
    let value = $(this).val();
    if(value == "")
    {
      submit.addClass("disabled");
      submit.attr("disabled","true");
    }else{
      submit.removeClass("disabled");
      submit.removeAttr("disabled");
    }
  });
});

$(function(){
    $("#categories-list").find(".item").each(function(){
        var btn = $(this).find(".form-control");
        $(this).hover(function(){
            btn.stop(false,false).fadeIn();
        }
        ,
        function(){
            btn.stop(false,false).fadeOut();
        }
        );
    });
});

$(function(){
$("#category-to-remove").on("change",function(){
    let value = $("#category-to-remove").val();
    let btn = $("#submitRemoveCategory");
    if(value == "")
    {
        btn.addClass("disabled");
        btn.attr("disabled","true");
    }
    else
    {
        btn.removeClass("disabled");
        btn.removeAttr("disabled");
    }
})
})
</script>