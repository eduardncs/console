import Upload from "./upload.js";

const show = (props = null) => {
    $.ajax({
        url: "widgets/media.php",
        type: "post",
        data: props,
        success: (data) => {
            $("#media-master-container").html(data);
        },
        error: (error) => {
            notoast("Sorry, the page was unreachable :( ");
        }
    })
}

const loadMedia = (object = "media") => {
    $.ajax({
        url: "widgets/media-contents.php",
        type:"post",
        cache: "false",
        data: {"object": object},
        beforeSend: () => {
            $("#media_overlay").show();
        },
        success: (data) => {
            $("#media-contents").html(data);
            $("#media_overlay").hide();
        },
        error: (error) => {
            $("#media_overlay").hide();
        }
    })
}
const uploadFile = ($this) => {
    const file = $this[0].files[0];
    const upload = new Upload(file);
    if(typeof file === "undefined")
        return;
    const size = upload.getSize();
    if(size > 5000000)
        return showerror("File too big make sure your file is not larger than 5MB")
    upload.doUpload();
}

const uploadFileFromURL = async () => {
    const values = { "action":"uploadFileFromURL","url":$("#url-link").val()};
    $.ajax({
        url: "processors/editor.req.php",
        type: "post",
        data: values,
        success: (data) => { $('#ajax').html(data); $("#media-master-container").empty(); },
        error: (error) => { console.error(error);}
    });
}

const remove = async (context, key) => {
    var values = {"action":"removeMedia","Source": context, "Source-id": key };
	$.ajax({
		url: "processors/editor.req.php",
		type: "post",
		data: values,
		success: (data) =>{$("#ajax").html(data);$("#media-master-container").empty();},
        error: (error) => {console.error(error);}
	})
}

export {
    loadMedia,
    show,
    uploadFile,
    uploadFileFromURL,
    remove
}