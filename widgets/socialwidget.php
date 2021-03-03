<?php
session_start();
if(!isset($_SESSION['loggedIN']) or ($_SESSION['loggedIN'] != true) or (!isset($_COOKIE['NCS_USER'])))
{
	return;
}
if(!isset($_COOKIE['NCS_PROJECT']))
{
	return;
}
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$socialWidget = $data->getWidgetJSON($user,$project,"social");
$globals = $data->GetGlobals();
?>
<style>
	ul.ui-autocomplete {
    z-index: 1200;
}
</style>
<div id="socialwidget" data-backdrop="false" class="modal right fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header justify-content-center">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="fas fa-times" style="color:#FF3636;"></i>
			</button>
			<h4 class="title title-up">Social links</h4>
      	</div>
		<div class="modal-body">
		<img src="images/41.svg" alt="" class="img" style="min-width:275px; width:100%; height:150px;">
			<div class="dropdown-menu border-0 show" id="socialmedia" style="position:relative; top:0%; width:100%; box-shadow:none;">
			<?php
				$sociallinks = $socialWidget['SocialMedia'];
				foreach($sociallinks as $link){
			?>
			<a href="javascript:void(0)" class="dropdown-item" data-toggle="tooltip" data-placement="bottom" title="Click to edit me" onClick="editthissociallink(this)" key="<?php echo $link['Key']; ?>" icon="<?php echo $link["Icon"]; ?>" link="<?php echo $link["Link"]; ?>">
			<i class="fab fa-<?php echo lcfirst($link["Icon"]); ?> mr-2"></i> <?php echo ucfirst($link["Icon"]); ?><br>
			<span class="text-sm text-muted ml-1"><?php echo $link['Link'] ?></span>
			</a>
			<div class="dropdown-divider"></div>
			<?php } ?>
			</div>
			<div class="text-center">
				<a href="javascript:void(0)" onClick="additem(this)" class="btn btn-pill btn-default text-center">Add a new social media link</a>
			</div>
		</div>
    </div>
  </div>
</div>
<div id="minimodalsocial" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Edit link</h4>
      </div>
		<form id="socialform" name="socialform">
      <div class="modal-body">
        <div class="input-group mb-3">
		<div class="input-group-prepend">
            <button type="button" class="btn btn-default"><i id="sicon" class="fab fa-facebook"></i></button>
        </div>
            <input type="hidden" name="action" id="action">
			<input type="hidden" name="key" id="socialkey">
                  <input type="text" id='socialicon' class="form-control" name="socialicon" placeholder="Social page name">
                </div>
		      				<div class="input-group mb-3">
            	<div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                </div>
                <input type="text" data-toggle="tooltip" data-placement="bottom" title="Where does this button link to ?" class="form-control" id="sociallink" name="sociallink" placeholder="Link to page">
            		</div>
      </div>
      <div class="modal-footer">
        <button type="submit" onClick="$('#action').val('savechanges')" class="btn btn-pill btn-success" data-toggle="tooltip" data-placement="bottom" name="savesocial" id="savesocial" title="Save changes"><i class="fas fa-save" style="color: #fff;"></i></button>
        <button type="submit" onClick="$('#action').val('removelink')" class="btn btn-pill btn-danger" data-toggle="tooltip" data-placement="bottom" name="deletesocial" title="Remove link"><i class="fas fa-trash-alt" style="color: #fff;"></i></button>
      </div>
		  		  </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#socialwidget').modal('show').draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});
	enabletooltips();
});
$("#socialicon").change(function(){
	update();
})
function update(){
	var i = uncapitalize($("#socialicon").val());
	$("#sicon").removeClass();
	$("#sicon").addClass("fab fa-"+i);
};
$(function() {
	var availableTags = ["Facebook","Twitter","Google","Linkedin","Youtube","Instagram","Pinterest","Snapchat-ghost","Skype","Android","Dribble","Vimeo","Tumblr","Vine","Foursquare","Stumbleupon,","Flickr","Yahoo","Reddit","Github","Apple","Microsoft","Ubuntu","Tiktok"];
	$("#socialicon").autocomplete({
      source: availableTags
    });
});
	$("#socialform").submit(function(event) {
	var ajaxRequest;
	var page = $("#currentpage").text();
    event.preventDefault();
    var values = $("#socialform").serialize();
       ajaxRequest= $.ajax({
		   url: "System/requestprocessor.php",
		   type: "post",
		   data: values,
		   success: function(data){$('#ajax').html(data); $("#minimodalsocial").modal('hide'); $("#socialwidget").modal('hide');	changepage("../clients/<?php echo $user->Business_Name."/".$project->project_name_short;?>/"+page.toLowerCase(),page);},
		   error: toast(false, "Error , please try again latter , if problem persist contact us")
        });
	});
	function editthissociallink(obj){
		$('#minimodalsocial').modal('show').draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});

		var key = $(obj).attr('key');
		var icon = $(obj).attr('icon');
		var link = $(obj).attr('link');
		$("#socialicon").val(icon);
		$("#socialkey").val(key);
		$("#sociallink").val(link);
		update();
	}
	function selectthis(obj){var socialname = $(obj).text();$("#socialicon").val(socialname);}
	function additem(obj){$(obj).attr("onclick","");
	var item = '<a href="javascript:void(0)" class="dropdown-item"  data-toggle="tooltip" data-placement="bottom" title="Click to edit me" onClick="editthissociallink(this)" key="" icon="" link=""><i class="fas fa-question-circle mr-2"></i> New link<br><span class="text-sm text-muted ml-1">https://somesite.com</span></a><div class="dropdown-divider"></div>';
	$("#socialmedia").append(item);enabletooltips();}
</script>
