<?php
session_start();
require_once("../autoload.php");
use Rosance\Database;
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Editor;
$data = new Data();
$editor = new Editor();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$portofolio = $editor->getPortofolio($user,$project);
?>
<div id="portofolio" data-backdrop="false" class="modal right fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header justify-content-center">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="fas fa-times" style="color:#FF3636;"></i>
				</button>
				<h4 class="title title-up">Edit projects</h4>
      </div>
      <div class="modal-body">
	  <img src="images/m_24.svg" alt="" class="img" style="width:100%;">
          <div class="dropdown-menu border-0 show" style="position:relative; top:0%; width:100%; box-shadow:none;" id="projectsList">
            <?php
            if($portofolio != null){
            while($proj = mysqli_fetch_assoc($portofolio))
            {
            ?>
            <a href="javascript:void(0)" class="dropdown-item" key="<?php echo $proj['ID']; ?>" p-title="<?php echo $proj['Title']; ?>" p-descr="<?php echo $proj['Description']; ?>" p-demo="<?php echo $proj['Demo']; ?>" p-source="<?php echo $proj['Source']; ?>" p-cover="<?php echo $proj['Cover']; ?>" onClick="editproject(this)">
                <i class="fas fa-chevron-right mr-2"></i> <?php echo $proj['Title']; ?>
            </a>
                <div class="dropdown-divider"></div>
            <?php }} ?>
          </div>
        <div class="text-center">
            <a href="javascript:void(0)" class="btn btn-default btn-pill" id="addProjBtn" onClick="addNewProject()">Add new project</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="project" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
			<div class="modal-header justify-content-center">
      					<button type="button" class="close" data-dismiss="modal" onClick="$('#portofolio').modal('show');" aria-hidden="true">
      						<i class="fas fa-times" style="color:#FF3636;"></i>
      					</button>
      					<h4 class="title title-up">Edit link</h4>
      </div>
		<form id="proj_form" name="proj_form">
			<input type="hidden" value="" name="proj_key" id="proj_key">
      <div class="modal-body">
          <div class="text-center">
            <input type="hidden" id="proj_cover" name="proj_cover">
            <input type="hidden" id="action" name="action">
            <img id="cover" src="images/placeholder.jpg" alt="" style="width:150px; height:150px; cursor:pointer;" class="img m-2 elevation-2" onClick="getwidget('widgets/media.php',null,'mediaContainerPort')" data-toggle="tooltip" data-placement="bottom" title="Choose a cover photo from project media">
        </div><br>
					<div class="form-group">
						<input type="text" class="form-control elevation-2" placeholder="Project title" id="proj_title" name="proj_title" maxlength="32" required>
					</div>
					<div class="form-group">
						<textarea class="form-control elevation-2" placeholder="Project description" id="proj_descr" name="proj_descr" required></textarea>
					</div>
					<div class="form-group">
						<input type="text" class="form-control elevation-2" placeholder="Demo link" id="proj_demo" name="proj_demo" maxlength="100" required>
                    </div>
                    <div class="form-group">
						<input type="text" class="form-control elevation-2" placeholder="Source link" id="proj_src" name="proj_src" maxlength="120">
                        <p class="ml-1 mt-2"><small><i class="fas fa-question-circle"></i> This field is not required!</small></p>
                    </div>
      </div>
      <div class="modal-footer">
        <button id="link_save" type="submit" onClick="$('#action').val('save')" class="btn btn-pill btn-success" data-toggle="tooltip" data-placement="bottom" name="savesocial" id="savesocial" title="Save changes"><i class="fas fa-save" style="color: #fff;"></i></button>
        <button id="link_remove" type="submit" onClick="$('#action').val('remove')" class="btn btn-pill btn-danger" data-toggle="tooltip" data-placement="bottom" name="deletesocial" title="Remove project"><i class="fas fa-trash-alt" style="color: #fff;"></i></button>
      </div>
	    </form>
    </div>
  </div>
</div>
<div id="mediaContainerPort"></div>
<script type="text/javascript">
$(document).ready(function(){
    $('#portofolio').modal('show').draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});
    enabletooltips();
});
function editproject(e)
{
    if(e == "")
        return;
    $("#portofolio").modal('hide');
    $("#project").modal('show').draggable({handle: ".modal-header"}).after(()=>{$(".modal-header").css("cursor","move");});
    let key = e.getAttribute('key');
    let title = e.getAttribute('p-title');
    let descr = e.getAttribute('p-descr');
    let src = e.getAttribute('p-source');
    let demo = e.getAttribute('p-demo');
    let cover = e.getAttribute('p-cover');
    $("#proj_key").val(key);
    $("#proj_title").val(title)
    $("#proj_cover").val(cover);
    $("#proj_demo").val(demo)
    $("#proj_src").val(src);
    $("#proj_descr").val(descr)
    $("#cover").attr('src',cover);
}
function addNewProject()
{
    let itm = '<a href="javascript:void(0)" class="dropdown-item" key="" p-title="" p-descr="" p-source="" p-demo=""  p-cover="images/placeholder.jpg" onClick="editproject(this)"><i class="fas fa-chevron-right mr-2"></i> New Project</a><div class="dropdown-divider"></div>';
    $("#projectsList").append(itm);
    $("#addProjBtn").attr("onClick","");
}
function selectMedia(x)
{
    $("#cover").attr('src',x);
    $("#proj_cover").val(x);
    toast('Cover selected!');
}
$("#proj_form").submit(function(event){
    event.preventDefault();
    var page = $("#currentpage").text();
    var values = $("#proj_form").serialize();
    $.ajax({
        url: "System/requestprocessor.php",
        method: 'post',
        dataType: 'html',
        data: values,
        success: function(data){
            $("#ajax").html(data); 
            $("#project").modal('hide');
            $("#portofolio").modal('hide');
            changepage("../clients/<?php echo $user->Business_Name."/".$project->project_name_short;?>/"+page.toLowerCase(),page);
        }
    })
});
</script>
