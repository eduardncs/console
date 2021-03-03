import * as globals from "./modules/rosance.js";
import Utils from "./modules/utils.js";

$(document).ready((e) => {
    const $_GET = window.location.href;
    if($_GET.includes("projects/create")){
        globals.getPage("pages/create-project.php", "Create a new project").then(
            () => globals.getSubPage()
            );
    }else{
        globals.getPage("pages/projects/overview.php", "My Projects").then(
            () => globals.getSubPage()
            );
    }
})
const createproject = async (t,ty) =>{
	Swal.mixin(
	{
		input: 'text',
		confirmButtonText: 'Done',
		showCancelButton: true,
		progressSteps: ['1']
	}).queue([
	{
		icon: 'question',
		title: 'What should be your project called?',
		text: 'Project name will be actual url of your website: ex: {businessname}.rosance.com/{project name}',
		input: 'text',
		inputPlaceholder: 'Project X',
		inputAttributes:{
			autocapitalize:'off'
		},
		inputValidator: (value) => {
			if(!value) {
				return 'Project name cannot be empty'
			}
		}
	}
	]).then((result) =>
	{
		if (result.value) {
			const answers = {"Info":result.value,"Template":t,"Type":ty};
			$.ajax({
				url: 'system/requestprocessor.php',
				type: "POST",
				data: answers,
				beforeSend: function(){$("#poverlay").show();},
				success: function(data)
				{
					$("#poverlay").hide();
					$("#ajax").html(data);
				},
				error: function(){notoast("Something went wrong while creating your project");}
			})
		}
	})
}
window.createproject = createproject;