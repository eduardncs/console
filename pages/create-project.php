<?php
session_start();
if(!isset($_SESSION['loggedIN']) or (!isset($_COOKIE['NCS_USER'])))
{
	header("Location: registration");
}
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
?>
	<div class="card card-solid">
		<div class="card-header">
			<a href="javascript:void(0)" class="btn btn-default btn-sm btn-pill" id="fall">All</a>
			<a href="javascript:void(0)" class="btn btn-default btn-sm btn-pill" onClick="rfind('basic')">Personal Page</a>
			<a href="javascript:void(0)" class="btn btn-default btn-sm btn-pill" onClick="rfind('blog')">Blog</a>
			<a href="javascript:void(0)" class="btn btn-default btn-sm btn-pill" onClick="rfind('shop')">E-Commence</a>
			<div class="card-tools">
				<div class="input-group border pl-3 pr-3" style="border-radius:25px;">
					<div class="input-group-prepend">
						<div class="input-group-text bg-white border-0">
							<i class="fas fa-search"></i>
						</div>
					</div>
					<input type="text" id="search" name="search" class="form-control border-0" placeholder="Search for a template">
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row d-flex align-items-stretch" id="projectContainer">
				<div class="col-md-4 projects elevation-2 card mt-2 pl-2 basic">
					<div class="overlay" style="display:none;">
					<a href="https://preview.rosance.com/personal/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
					<a href="javascript:void(0)" onClick="createproject('personal','Basic')" class="btn btn-primary btn-pill">Install template</a>
					</div>
					<div class="position-relative text-center" align="center">
					<img src="images/templates/personal.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="text-muted">Personal</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 projects elevation-2 card mt-2 pl-2 blog">
					<div class="overlay" style="display:none;">
					<a href="https://preview.rosance.com/neuron/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
					<a href="javascript:void(0)" onClick="createproject('neuron','Blog')" class="btn btn-primary btn-pill">Install template</a>
					</div>
					<div class="position-relative text-center" align="center">
					<img src="images/templates/neuron.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="text-muted">Neuron</div>
							</div>
						</div>
					</div>
				</div>
			<div class="col-md-4 projects elevation-2 card mt-2 pl-2 blog">
				<div class="overlay" style="display:none;">
				<a href="https://preview.rosance.com/fashion/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
				<a href="javascript:void(0)" onClick="createproject('fashion','Blog')" class="btn btn-primary btn-pill">Install template</a>
				</div>
				<div class="position-relative text-center" align="center">
				<img src="images/templates/fashion.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="text-muted">Fashion</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 projects elevation-2 card mt-2 pl-2 shop">
				<div class="overlay" style="display:none;">
				<a href="https://preview.rosance.com/classic-shop/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
				<a href="javascript:void(0)" onClick="createproject('Classicshop','Shop')" class="btn btn-primary btn-pill">Install template</a>
				</div>
				<div class="position-relative text-center" align="center">
				<img src="images/templates/classicshop.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="text-muted">Classic Shop</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 projects elevation-2 card mt-2 pl-2 shop">
				<div class="overlay" style="display:none;">
				<a href="https://preview.rosance.com/grocery-shop/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
				<a href="javascript:void(0)" onClick="createproject('Grocery','Shop')" class="btn btn-primary btn-pill">Install template</a>
				</div>
				<div class="position-relative text-center" align="center">
				<img src="images/templates/grocery.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="text-muted">Grocery Shop</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 projects elevation-2 card mt-2 pl-2 basic">
				<div class="overlay" style="display:none;">
				<a href="https://preview.rosance.com/nowui/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
				<a href="javascript:void(0)" onClick="createproject('NowUI','Basic')" class="btn btn-primary btn-pill">Install template</a>
				</div>
				<div class="position-relative text-center" align="center">
				<img src="images/templates/nowui.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="text-muted">Now</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 projects elevation-2 card mt-2 pl-2 basic">
				<div class="overlay" style="display:none;">
					<a href="https://preview.rosance.com/nowui/" target="_blank" class="btn btn-warning btn-pill">Preview template</a>
					<a href="javascript:void(0)" onClick="createproject('Creative','Basic')" class="btn btn-primary btn-pill">Install template</a>
				</div>
				<div class="position-relative text-center" align="center">
					<img src="images/templates/creative.jpg" alt="Photo 1" style="height:250px; width:100%;" class="img-fluid">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="text-muted">Creative</div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
		
				</div>
			</div>
		</div>

<script type="text/javascript">
$(document).ready(function(){$(".projects").each(function(){$(this).hover(function(){$(this).removeClass("elevation-2"); $(this).addClass("elevation-4");$(this).find(".overlay").stop(!0,!0).fadeIn("slow")},function(){$(this).removeClass("elevation-4"); $(this).addClass("elevation-2");$(this).find(".overlay").stop(!0,!0).fadeOut("slow")})})});
$("#fall").on("click",function(){
	$(".projects").each(function(){$(this).fadeIn();})
});
var rfind = (w) =>{
	$("#projectContainer").find(".projects").each(function(){
		if($(this).hasClass(w))
			$(this).fadeIn();
		else
			$(this).fadeOut();
	})
}
$("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#projectContainer .projects").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
</script>
