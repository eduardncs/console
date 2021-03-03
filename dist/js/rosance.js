/*!
 * Rosance V 0.1 Beta(https://rosance.com)
 * Author : Eduard Neacsu
 * Copyright © 2020
 */
import * as globals from "./modules/rosance.js";
import Utils from "./modules/utils.js";

window.toast = globals.toast;
window.etoast = globals.etoast;
window.itoast = globals.itoast;
window.wtoast = globals.wtoast;

const getwidget = (url, object=null) => {
		$.ajax({
			url: url,
			type: "post",
			dataType:'html',
			data:{"text":object},
			success: function(data){
			$("#requests").html(data);
			}
		   })
	}
const loadMediaContents = (url) =>{
	$.ajax({
		url: url,
		beforeSend: function(){ $("#overlay").show(); },
		success: function(data){
		$("#media-contents").html(data);
		$("#overlay").hide();
		}
})
}

const deleteproject = (projectname) =>{
		return Swal.fire({
		icon: 'question',
		title: 'Are you sure you want to delete this project ?',
		scrollbarPadding: false,
		confirmButtonText: 'Yes!',
		showCancelButton: true,
		cancelButtonColor: 'red',
		cancelButtonText: 'NO',
		allowOutsideClick: false
 }).then((result) => {
   if (result.value) {
     	$.ajax({
			url: 'pages/overview.php?projecttodelete='+projectname,
			type: "GET",
			beforeSend: function(){$("#overlayy").show();},
			success: function(data){
			$("#content").empty();
			setInterval(function() {$("#overlayy").hide(); },250);
			$("#content").html(data);
			$("projectsdropdown").empty();
			getsubpage();
			}
   })}
	});
}
const removeMedia = (source,id) =>{
	var values = {"Source": source, "Source-id": id };
	$.ajax({
		url: "system/requestprocessor.php",
		type: "POST",
		data: values,
		success: function(data){
		$("#requests").html(data);
		}
	})
}
const loadInbox = (context) =>{
	$.ajax({
		url: context+'.php',
		dataType: 'html',
		cache:false,
		beforeSend: function(){ $("#mailboxLoader").show(); },
		success: function(data){ $("#mailboxLoader").hide(); $("#mailboxContainer").html(data); }
	})
}

const Logout = () =>{
	return	Swal.fire
	({
		title: 'Are you sure do you want to logout?',
		icon: 'question',
		cancelButtonText: 'No',
		cancelButtonColor: 'red',
		showConfirmButton: true,
		confirmButtonText: 'Yes!',
		showCancelButton: true
	}).then((result) => {
   		if (result.value) 
     		window.location.href = `registration?action=logout`
	});
}

(function($) {
	$.fn.inputFilter = function(inputFilter) {
	  return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
		if (inputFilter(this.value)) {
		  this.oldValue = this.value;
		  this.oldSelectionStart = this.selectionStart;
		  this.oldSelectionEnd = this.selectionEnd;
		} else if (this.hasOwnProperty("oldValue")) {
		  this.value = this.oldValue;
		  this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
		} else {
		  this.value = "";
		}
	  });
	};
  }(jQuery));