<?php 
session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Blog;
use Rosance\Project;
use Rosance\User;
$data = new Data();
$project = new Project($_COOKIE['NCS_PROJECT']);
$blog = new Blog();
$user = $data->GetUser($_SESSION['loggedIN']);
$author = $blog->GetAuthor($project,$user->id);
?>
<form id="author">
<div class="card">
    <div class="card-header">
    <div class="card-tools float-right">
            <a href="javascript:void(0)" class="btn btn-pill btn-sm btn-success" id="savBtn">Save changes</a>
        </div>
        <h4 class="card-title">Author info</h4><br>
        <small>Let your visitors get to know you better</small>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <input type="hidden" name="profile_pic" id="profile_pic" value="<?php echo $author['profile_pic']; ?>">
                    <img src="<?php echo $author['profile_pic']; ?>" alt="Profile Picture" onError="this.onError=null;this.src='images/placeholder.jpg';" style="cursor:pointer;min-width:170px; min-height:170px;" id="profile_pic_preview" class="image img-circle img-fluid elevation-3" data-toggle="tooltip" data-placement="bottom" title="Change profile picture">
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label for="author_first_name">First Name <small>(Max 32 chars)</small></label>
                     <input type="text" name="author_first_name" id="author_first_name" value="<?php echo $author['First_Name']; ?>" class="form-control elevation-2" placeholder="First name">
                </div>
                <div class="form-group">
                    <label for="author_last_name">Last Name <small>(Max 32 chars)</small> </label>
                    <input type="text" name="author_last_name" name="author_last_name" value="<?php echo $author['Last_Name']; ?>" class="form-control elevation-2" placeholder="Last name">
                </div>
                <div class="form-group">
                    <label for="author_email">Email Adress</small></label>
                    <input type="email" name="author_email" name="author_email" value="<?php echo $author['Email']; ?>" class="form-control elevation-2" placeholder="Last name">
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="form-group">
                <label for="author_description">Tell your readers something about yourself <small>Max 300 chars</small></label>
                    <textarea name="author_description" id="author_description" class="form-control elevation-2" cols="30" rows="5" placeholder="Short description" maxlength="300"><?php echo $author['Optional']; ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready((e) =>{
        $("#setBtn").hide();
        $("#bkBtn").show();
        $("[data-toggle*=tooltip]").tooltip();
    })
    var selectMedia = (url) =>{
        $("#profile_pic").val(url);
        $("#profile_pic_preview").attr('src',url);
    }
    $("#savBtn").on("click",() =>{
        $("#author").submit();
    });
</script>