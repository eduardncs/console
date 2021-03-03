<?php 
session_start();
if(!isset($_SESSION['loggedIN']) or (!isset($_COOKIE['NCS_USER'])))
{
	die();
}
if(!isset($_COOKIE['NCS_PROJECT']))
{
	die();
}
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Editor;
use Rosance\Project;
$data = new Data();
$editor= new Editor();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$media = $editor->getAlbum($user,$project);
isset($_POST['object']) ? $object = $_POST['object'] : $object = null;
for ($i=0; $i < count($media['UPLOADEDFROMCOMPUTER']) ; $i++) { 
  $item = $media['UPLOADEDFROMCOMPUTER'][$i];
  $entry = $media['UPLOADEDFROMCOMPUTERENTRIES'][$i];
?>
              <div class="col-md-2 mb-2 mt-2 X-element" uploadsource="computer">
                  <div class="card elevation-2 element">
                    <div class="card-body p-0">
                        <div class="overlay actions" style="z-index:1053; display:none;">
                            <a href="javascript:void(0)" class="btn btn-primary btn-pill btn-sm" style="z-index:9999;opacity:1;" data-toggle="dropdown" aria-expanded="false">Action <i class="fas fa-angle-right"></i></a>
                            <ul class="dropdown-menu">
                            <li class="dropdown-item"><a href="javascript:void(0)" style="color:blue;" onClick="$('#mediamodal').modal('hide');selectMedia('<?php echo $item; ?>'<?php echo ",'".$object."'"; ?>)"><i class="fas fa-check"></i> Select</a></li>
                            <li class="dropdown-item"><a href="javascript:void(0)" style="color:red;" onClick="removeMedia('computer','<?php echo $item; ?>')"><i class="fas fa-trash"></i> Remove</a></li>
                            </ul>
                        </div>
                      <img src='<?php echo $item; ?>' metadata='<?php echo $item; ?>' onError="this.onError=null;this.src='images/placeholder.jpg';" alt="Error" class="image img-thumbnail img-fluid" style="width:100%; height:138.75px;">
                    </div>
                    <div class="card-footer">
                      <div class="text-center text-xs">
                        <?php 
                        $entry = explode(".",$entry);
                        if(strlen($entry[0]) > 7 )
                          echo substr($entry[0],0,7)."...".$entry[1]; 
                        else
                          echo $entry[0].".".$entry[1];
                        ?>
                      </div>
                    </div>
                  </div>
              </div>
  <?php
  }
  foreach($media['UPLOADEDFROMURL'] as $item)
  {
  ?>
              <div class="col-md-2 mb-2 mt-2 X-element" uploadsource="url">
                  <div class="card elevation-2 element">
                    <div class="card-body p-0">
                        <div class="overlay actions" style="z-index:1053; display:none;">
                            <a href="javascript:void(0)" class="btn btn-primary btn-pill btn-sm" style="z-index:9999;opacity:1;" data-toggle="dropdown" aria-expanded="false">Action <i class="fas fa-angle-right"></i></a>
                            <ul class="dropdown-menu">
                            <li class="dropdown-item"><a href="javascript:void(0)" style="color:blue;" onClick="$('#mediamodal').modal('hide');selectMedia('<?php echo $item; ?>'<?php echo ",'".$object."'"; ?>)"><i class="fas fa-check"></i> Select</a></li>
                            <li class="dropdown-item"><a href="javascript:void(0)" style="color:red;" onClick="removeMedia('url','<?php echo $item; ?>')"><i class="fas fa-trash"></i> Remove</a></li>
                            </ul>
                        </div>
                      <img src='<?php echo $item; ?>' metadata='<?php echo $item; ?>' onError="this.onError=null;this.src='images/placeholder.jpg';" alt="Error" class="image img-thumbnail img-fluid" style="width:100%; height:138.75px;">
                    </div>
                  </div>
              </div>
              <?php } ?>
<script>
$(".element").hover
(
    function(){$(this).removeClass("elevation-2"); $(this).addClass("elevation-4");$(this).find(".actions").stop(!0,!0).fadeIn();},
    function(){$(this).removeClass("elevation-4"); $(this).addClass("elevation-2");$(this).find(".actions").stop(!0,!0).fadeOut();}
)
</script>