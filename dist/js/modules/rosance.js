const toast=(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"success",title:t})};
const etoast=(o)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"error",title:o})};
const wtoast=(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"warning",title:t})};
const itoast=(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"info",title:t})};
const showerror=(r) => Swal.fire({ icon: "error", title: "Oops...", text: r, scrollbarPadding: !1, allowOutsideClick: !1 });
const showsuccess=(c, s = !0, t) => Swal.fire({ icon: "success", title: "Success! :)", html: c, scrollbarPadding: !1, confirmButtonText: "Great!", allowOutsideClick: !1 }).then(() => { s || (window.location.href = t) });
const showsuccessprojectselection=o=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:1e3}).fire({icon:"success",title:o}).then(()=>{window.location.href="dashboard/overview"})};
window.showsuccess =showsuccess;window.showerror = showerror;
window.toast = toast;window.etoast = etoast;
window.itoast =itoast;window.wtoast = wtoast;
window.showsuccessprojectselection = showsuccessprojectselection;
const truncate = (str, n) => {return (str.length > n) ? str.substr(0, n-1) + '...' : str;};window.truncate = truncate;
const getPage = async (url,header) =>{
	return $.ajax({
		url: url,
		beforeSend: () => { $("#overlay").show();},
		success: (data) => { 
			$("#overlay").hide();
			$("#content").html(data);
			$("#Header").html(header);
		},
		error: (error) =>{
			$("#content").html("<h2 class='text-center'>Sorry, the page was unreachable :( </h2>");
		}
	})
}
window.getPage = getPage;
const getwidget = (url, object=null) => {
    return $.ajax({
        url: url,
        type: "post",
        dataType:'html',
        data:{"text":object},
        success: function(data){
        $("#requests").html(data);
        }
       })
}
const getSubPage = async () =>{
	return $.ajax({
		url: "system/UserMenu.php",
		success: (data) =>{ $("#userArea").html(data);},
        error: (error) =>{
            console.error(error);
        }
   })
}

const logout = () =>{
	return Swal.fire
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
window.logout = logout;

export {
    getPage,
    getSubPage,
    logout,
    toast,
    etoast,
    itoast,
    wtoast,
    showsuccess,
    showerror,
    getwidget
}