<?php
session_start();
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
$data = new Data();
$project = new Project($_COOKIE['NCS_PROJECT']);
$user = $data->GetUser($_SESSION['loggedIN']);
$menuwidget = $data->getWidgetJSON($user,$project,"menu");
?>
<div id="menuwidget" data-backdrop="false" class="modal right fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header justify-content-center">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="fas fa-times" style="color:#FF3636;"></i>
			</button>
			<h4 class="title title-up">Site menu</h4>
      	</div>
      	<div class="modal-body h-100">
		  <div class="container-fluid">
		  	<div class="nested-sortable" id="menu-container-master">
			   <?php 
				$menu = $menuwidget['Menu'];
				foreach($menu as $m)
				{
					if($m['P_Key'] == 0 or $m['P_Key'] == "0")
					{
						$m['Text'] === "Home" ? $icon = "home" : $icon = "file-alt";

						echo "<div class='list-group-item menu-link-item d-flex justify-content-between align-items-center ".$m['Key']."' data-link='".$m['Key']."'>
						<i class='fas fa-".$icon." pr-1'></i><span class='text-name'>".$m['Text']."</span>
						<div class='dropdown'>
							<span class='badge badge-default badge-pill ellipsis' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								<i class='fas fa-ellipsis-h'></i>
							</span>
							<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
								<a class='dropdown-item edit_btn' data-link='".$m['Key']."' data-href='".$m['Href']."' data-target='".$m['Target']."' data-text='".$m['Text']."' onClick='editMe(this)' href='javascript:void(0)'>Edit</a>
								<a class='dropdown-item remove_btn' data-link='".$m['Key']."' onClick='removeMe(this)' href='javascript:void(0)'>Remove</a>
							</div>
						</div></div>";
					}elseif($m['P_Key'] == 1 or $m['P_Key'] == "1")
					{
						//Aici e parintele
						echo "<div class='list-group-item menu-link-item menu-folder ".$m['Key']."'  data-link='".$m['Key']."' data-isFolder='true'>
						<i class='fas fa-folder pr-1'></i><span class='text-name'>".$m['Text']."</span>
						<div class='dropdown float-right mt-1'>
							<span class='badge badge-default badge-pill ellipsis' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								<i class='fas fa-ellipsis-h'></i>
							</span>
							<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
								<a class='dropdown-item edit_btn' data-link='".$m['Key']."' data-text='".$m['Text']."' data-isfolder='true' onClick='editMe(this)' href='javascript:void(0)'>Edit</a>
								<a class='dropdown-item remove_btn' data-link='".$m['Key']."' onClick='removeMe(this)' href='javascript:void(0)'>Remove</a>
							</div>
						</div>";

						echo "<div class='list-group nested-sortable pt-1' data-parent='".$m['Key']."'>";
						foreach($m['Children'] as $chield){
							if($chield['P_Key'] == $m['Key'])
							{
							//Asta e copilul lui
							echo "<div class='list-group-item menu-link-item d-flex justify-content-between align-items-center ".$chield['Key']."'  data-link='".$chield['Key']."'>
							<i class='fas fa-file-alt pr-1'></i><span class='text-name'>".$chield['Text']."</span>
							<div class='dropdown'>
								<span class='badge badge-default badge-pill ellipsis' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
									<i class='fas fa-ellipsis-h'></i>
								</span>
								<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
									<a class='dropdown-item' data-link='".$chield['Key']."' data-href='".$chield['Href']."' data-target='".$chield['Target']."' data-text='".$chield['Text']."' onClick='editMe(this)' href='javascript:void(0)'>Edit</a>
									<a class='dropdown-item' data-link='".$chield['Key']."' onClick='removeMe(this)' href='javascript:void(0)'>Remove</a>
								</div>
							</div></div>";
							}
						}
						echo "</div>";
						echo "</div>";
					}
				}
			  ?>
			</div>
		  </div>
		</div>
		<div class="modal-footer text-center">
			<a href="javascript:void(0)" id="addFolderBtn" class="btn btn-flat btn-primary btn-pill"><i class="fas fa-folder pr-1"></i> Add folder</a>
			<a href="javascript:void(0)" id="addLinkBtn" class="btn btn-flat btn-primary btn-pill"><i class="fas fa-file-alt pr-1"></i> Add link</a>
		</div>
    </div>
  </div>
</div>
<div id="minimenuwidget" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" onClick="$('#menuwidget').modal('show');" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Edit link</h4>
      </div>
      <div class="modal-body">
		<form id="menu_form" name="menu_form">
			<input type="hidden" name="link_key" id="link_key">
			<input type="hidden" name="link_isfolder" id="link_isfolder">
			<div class="form-group">
				<label for="link_text" class="text-muted">What text should this link display ?</label>
				<input type="text" class="form-control elevation-2" placeholder="Text to be shown" id="link_text" name="link_text">
			</div>
			<div class="form-group">
				<label for="link_href" class="text-muted">Where should this item link to ?</label>
				<input type="text" class="form-control elevation-2" placeholder="www.example.com" id="link_href" name="link_href">
			</div>
			<div class="form-group">
				<label for="link_text" class="text-muted">How would you like this link to behave?</label>
				<select class="form-control elevation-2" id="link_target" name="link_target">
					<option value="_self" selected="">Open on the same window</option>
					<option value="_blank">Open on a new window</option>
				</select>
			</div>
		</form>
      </div>
      <div class="modal-footer d-flex">
		  <div class="ml-auto">
			<button id="link_save" type="button" class="btn btn-pill btn-success" data-toggle="tooltip" data-placement="bottom" title="Save changes"><i class="fas fa-save pr-1" style="color: #fff;"></i> Save changes</button>
		  </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#menuwidget").modal("show");
	menu.createSortable();
});
var editMe = (obj) => {
	let object = $(obj);
	if(object.data("link") === "" || typeof object.data("link") === typeof undefined)
	{
		cosole.error("No data-link passed !");
		return;
	}
	updateMiniMenu(object).then(_=>{
		$("#minimenuwidget").modal('show');
	})
}
var removeMe = (obj) =>{
	let object = $(obj);
	if(object.data("link") === "" || typeof object.data("link") === typeof undefined)
	{
		cosole.error("No data-link passed !");
		return;
	}
	$("."+object.data("link")).remove();
	menu.removeLink(object.data("link"));
}
var updateMiniMenu = async (obj) =>{
	// Edit menu to be made
	let key = obj.data("link");
	let text = obj.data("text");
	let href = obj.data("href");
	let target = obj.data("target");
	$("#link_key").val(key);
	if(obj.data("isfolder") == true)
	{
		$("#link_isfolder").val(true);
		$("#link_text").val(text);
		$("#link_target").parent().hide();
		$("#link_href").parent().hide();
	}else{
		$("#link_isfolder").val(false);
		$("#link_target").parent().show();
		$("#link_href").parent().show();
		$("#link_text").val(text);
		$("#link_target").val(target);
		$("#link_href").val(href);
	}
}
$("#addFolderBtn").on("click",function(event){
	let id = ID();
	let folder = "<div class='list-group-item menu-link-item "+id+"' data-link='"+id+"'><span><i class='fas fa-folder pr-1'></i> New folder</span><div class='dropdown float-right mt-1'><span class='badge badge-default badge-pill ellipsis' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></span><div class='dropdown-menu' aria-labelledby='dropdownMenuButton'><a class='dropdown-item edit_btn' data-link='"+id+"' onClick='editMe(this)' href='javascript:void(0)'>Edit</a><a class='dropdown-item remove_btn' data-link='"+id+"' onClick='removeMe(this)' href='javascript:void(0)'>Remove</a></div></div><div class='list-group nested-sortable pt-1'></div></div>";
	$("#menu-container-master").append(folder);
	menu.destroySortable();
	menu.createSortable();
	menu.addFolder(id);
});
$("#addLinkBtn").on("click",function(event){
	const id = ID();
	let item = "<div class='list-group-item menu-link-item d-flex justify-content-between align-items-center "+id+"' data-link='"+id+"'><span><i class='fas fa-file-alt pr-1'></i> New link</span><div class='dropdown'><span class='badge badge-default badge-pill ellipsis' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-ellipsis-h'></i></span><div class='dropdown-menu' aria-labelledby='dropdownMenuButton'><a class='dropdown-item edit_btn' data-link='"+id+"' onClick='editMe(this)' href='javascript:void(0)'>Edit</a><a class='dropdown-item remove_btn' data-link='"+id+"' onClick='removeMe(this)' href='javascript:void(0)'>Remove</a></div></div></div>";
	$("#menu-container-master").append(item);
	menu.addLink(id);
});
$("#link_save").on("click",function(event){
	$("#menu_form").submit();
})
$("#menu_form").submit(function(event){
	event.preventDefault();
	event.stopPropagation();
	menu.editLink($(this).serializeArray());
	$("#minimenuwidget").modal('hide');
})
</script>