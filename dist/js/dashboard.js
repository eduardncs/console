import * as globals from "./modules/rosance.js";
$(document).ready( function()
{
	globals.getPage("pages/overview.php", "Dashboard :: Overview").then(()=>
    globals.getSubPage()
    );
})