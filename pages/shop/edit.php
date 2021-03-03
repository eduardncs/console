<?php
if(!isset($_SESSION))
    session_start();
require_once("../../System/Database.class.php");
require_once("../../System/Data.class.php");
require_once("../../System/Project.class.php");
require_once("../../System/User.class.php");
require_once("../../System/Shop.class.php");
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$shop = new Shop();
if(isset($_GET['productID']))
{
    $product = new Product($user ,$project, explode(".",$_GET['productID'])[0]);
}
$info = $data->getWidgetJSON($user, $project,"info","../../");
?>
<div id="requests"></div>
<form id="product_edit">
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product details</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="inputName"><span style="color:red;">*</span> Product Name</label>
                    <input type="text" value="<?php echo $product->name; ?>" id="productName" class="form-control" name="productName" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="productDescription"><span style="color:red;">*</span> Product Description</label>
                    <textarea name="productDescription" id="productDescription"><?php echo $product->GetDescription($user, $project , $product->descriptionid,"../../"); ?></textarea>
                </div>
                <div class="form-group">
                <label for="inputStatus"><span style="color:red;">*</span> Status</label>
                    <select class="form-control custom-select" name="productavailable" required>
                    <option selected="" disabled="">Select one</option>
                    <option value="available" <?php if($product->available == "available") echo "selected=''"; ?>>Available</option>
                    <option value="unavailable" <?php if($product->available == "unavailable") echo "selected=''"; ?>>Unavailable</option>
                    <option value="preorder" <?php if($product->available == "preorder") echo "selected=''"; ?>>Preorder</option>
                    </select>
              </div>
                <div class="form-group">
                <label for="inputStatus"><span style="color:red;">*</span> Categories ( <a href="javascript:void(0)" onClick="getwidget('pages/shop/categories.php')">Click here to manage categories</a> )</label>
                    <select class="select2" required name="productCategories" multiple="multiple" data-placeholder="Select categories" style="width: 100%;">
                    <?php 
                    $categories = $shop->S_GetCategories($project);
                    foreach($categories as $parent)
                    {
                        echo "<optgroup label='".$parent['Parent']["Name"]."'>";
                        foreach($parent['Children'] as $chield)
                        {
                            echo "<option value='".$chield['ID']."'>".$chield['Name']."</option>";
                        }
                        echo "</optgroup>";
                    }
                     ?>
                  </select>
              </div>
              <div class="form-group">
                <label for="inputStatus"><span style="color:red;">*</span> Set this as featured item ?</label>
                    <select class="form-control custom-select" name="featured" required>
                    <option selected="" disabled="">Select one</option>
                    <option value="true" <?php if($product->featured) echo "selected=''"; ?>>Yes</option>
                    <option value="false" <?php if(!$product->featured) echo "selected=''"; ?>>No</option>
                    </select>
              </div>       
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product pricing</h4><br>
                <small>Manage product prices and discounts</small>
            </div>
            <div class="card-body">
                <div class="form-group clearfix">
                    <label for="BPrice"><span style="color:red;">*</span> Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <curency><?php echo $info['Curency']; ?></curency>
                            </div>
                        </div>
                        <input id="BPrice" value="<?php echo $product->BPrice; ?>" name="BPrice" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="isOnSale" <?php if($product->RPrice != 0) echo "checked"; ?>>
                        <label for="isOnSale">
                            This product is on sale 
                        </label>
                    </div>
                </div>
                <div class="row" id="reduction" <?php if($product->RPrice == 0) echo 'style="display:none;"'; ?>>
                    <div class="col-md-6">
                        <label for="RPrice">Reduced price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <curency><?php echo $info['Curency']; ?></curency>
                                </div>
                            </div>
                            <input id="RPrice" value="<?php echo $product->RPrice; ?>" name="RPrice" type="text" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="D_Val">Discount ammount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <i class="fas fa-percentage"></i>&nbsp;&nbsp;<input type="checkbox" <?php if(strpos($product->D_Val,"%") !== false) echo "checked"; ?> id="chkbxc" onClick="<?php if(strpos($product->D_Val,"%") !== false) echo "removePerc(this)"; else echo "addPerc(this)"; ?>">
                                </div>
                            </div>
                            <input id="D_Val" value="<?php echo $product->D_Val; ?>" name="D_Val" type="text" class="form-control">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <curency><?php echo $info['Curency']; ?></curency>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product analytics</h4><br>
                <small>Choose if you want Rosance to handle this product analytics. If not , leave these fields blank</small>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="Stock">Units in stock</label>
                    <input id="Stock" value="<?php echo $product->stock; ?>" name="Stock" placeholder="" type="text" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="Stock">Aquisition price</label>
                    <input id="APrice" value="<?php echo $product->APrice; ?>" name="APrice" type="text" class="form-control" required>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><span style="color:red;">*</span> Product pictures <small>(Max 5)</small></h4><br>
                <small>First picture will be main picture</small>
            </div>
            <div class="card-body text-center" id="picsContainer">
            <input type="hidden" id="picsA" name="picsA" value="<?php
            for ($i=0; $i < count($product->pictures) ; $i++) { 
                if($i == (count($product->pictures) -1))
                    echo trim($product->pictures[$i]);
                else echo trim($product->pictures[$i].",");
            }
            ?>">
                        <?php foreach($product->pictures as $pic)
                            {?>
                            <img src="<?php echo $pic; ?>" isRemovable="false" onClick="removeMe(this)" style="width:150px; height:150px;" class="elevation-2 mt-2 mb-3" alt="">
                            <?php } ?>
            </div>
            <div class="card-footer">
                <div class="float-left">
                <button type="button" class="btn btn-sm btn-danger btn-pill" onClick="makePicsRemovable(this)"><i class="fas fa-minus"></i>Remove</button>
                </div>
                <div class="float-right">
                    <button type="button" class="btn btn-sm btn-default btn-pill" onClick="getwidget('widgets/media.php')"><i class="fas fa-plus"></i>Add picture</button>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="ncs_did" value="<?php echo $product->descriptionid; ?>">
<input type="hidden" name="ncs_id" value="<?php echo explode(".",$_GET['productID'])[0]; ?>">
<input type="hidden" name="action" value="edit">
</form>
<script>
$(document).ready(function(){
    $("#productDescription").summernote({height:"200"});
    $("#addproduct").removeAttr("disabled");
    $("#addproduct").removeClass("disabled");
    var $f = $('.select2').select2();
    $f.val([<?php
    for ($i=0; $i < count($product->categories); $i++) { 
        if($i == (count($product->categories) -1))
            echo '"'.$product->categories[$i].'"';
        else
            echo '"',$product->categories[$i].'" , ';
    }
    ?>]).trigger("change");
})

function selectMedia(m)
{
    let n = ($("#picsContainer").find("img")).length;
        if(n >= 5)
            return notoast("You have reached maximum of pictures allowed!");
    let p = $("#picsA").val();
    if(p == "")
        p = m;
    else
        p += ","+m;
    $("#picsA").val(p);
    let i = '<img src="'+m+'" isRemovable="false" onClick="removeMe(this)" style="width:150px; height:150px;" class="elevation-2 mt-2 mb-3" alt="">';
    $("#picsContainer").append(i);
    toast ("Picture added!");
}
function makePicsRemovable(f){
    var r = $("#picsContainer").find("img");
    if(r.length == 0)
        return;
    r.each(function(){
        if($(this)==null)
        return;
        $(this).attr("isRemovable","true");
        if(!$(this).hasClass("elevation-4"))
            $(this).addClass("elevation-4");
    });
    $(f).html("<i class='fas fa-check'></i>Finish");
    $(f).attr("onClick","makePicsUnRemovable(this)");
}
function makePicsUnRemovable(f)
{
    $("#picsContainer").find("img").each(function(){
        $(this).attr("isRemovable","false");
        if($(this).hasClass("elevation-4"))
            $(this).removeClass("elevation-4");
    });
    $(f).html("<i class='fas fa-minus'></i>Remove");
    $(f).attr("onClick","makePicsRemovable(this)");
}
function removeMe(i)
{
    if($(i).attr("isRemovable") == "false")
        return;
    let val = $(i).attr("src");
    let mval = ($("#picsA").val()).split(',');

    mval = jQuery.grep(mval, function(value) {
    return value != val;
    });
    $("#picsA").val(mval.toString());
    $(i).remove();
    toast("Picture removed!");
}
$("#product_edit").submit(function(event){
    event.preventDefault();
    var values = $("#product_edit").serialize();
    $.ajax({
        url: "System/Shop.class.php",
        dataType: "html",
        method: "post",
        data: values,
        beforeSend: function(){$("#overlay").show();},
        success: function(data){$("#requests").html(data); $("#overlay").hide();}
    })
});
$("#product_edit").change(function(){
    $("#addproduct").removeAttr("disabled");
    $("#addproduct").removeClass("disabled");
});
$("#addproduct").on("click",function(){$("#product_edit").submit();});
$("#isOnSale").change(function(){
    let isChecked = $("#isOnSale").is(":checked");
    if(isChecked)
        $("#reduction").fadeIn();
    else{
        $("#reduction").fadeOut();
        $("#RPrice").val("");
        $("#D_Val").val("");
        $("#chkbxc").prop("checked",false);
    }
})
$("#D_Val").change(function(){
    recalculatePrice();
})
$("#BPrice").change(function(){
    recalculatePrice();
})
function recalculatePrice()
{
    var base = $("#BPrice").val();
    if(base == "" || base=="0")
        return;
    var val = $("#D_Val").val();
    if(val == "" || val == "0")
        return;
    var isPercent = val.includes("%");
    if(!isPercent)
    {
        $("#RPrice").val(getDiscountValueNonPercent(base,val));
    }else{
        $("#RPrice").val(getDiscountValuePercent(base,val));
    }
}
function addPerc(t)
{
    if($("#D_Val").val() == "0" || $("#D_Val").val() == ""){
        $(t).prop("checked",false);
        return notoast("Discount value is empty!");
    }
    let p = $("#D_Val").val();
    if(!p.includes("%"))
        $("#D_Val").val(p+"%");
    $(t).attr("onclick","removePerc(this)");
    recalculatePrice();
}
function removePerc(t)
{
    let p = $("#D_Val").val();
    $("#D_Val").val(p.replace("%",""));
    $(t).attr("onclick","addPerc(this)");
    recalculatePrice();
}
const getDiscountValueNonPercent = (b,n) =>{
    let isc = $("#isOnSale").is(":checked");
    if(isc)
        $("#chkbxc").prop("checked",false);
    return b-n;
    }
const getDiscountValuePercent = (b,n) =>{
    n = n.replace("%","");
    b = b - (n/100 * b);
    return toFixed(b,2);
}
function toFixed(value, precision) {
    var precision = precision || 0,
        power = Math.pow(10, precision),
        absValue = Math.abs(Math.round(value * power)),
        result = (value < 0 ? '-' : '') + String(Math.floor(absValue / power));

    if (precision > 0) {
        var fraction = String(absValue % power),
            padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');
        result += '.' + padding + fraction;
    }
    return result;
}
</script>