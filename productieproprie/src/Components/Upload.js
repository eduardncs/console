import $ from 'jquery';
import { domain } from "./Utils";

export default class Upload {
	constructor(file) {
		this.file = file;
	}
	getType() {
		return this.file.type;
	}
	getSize() {
		return this.file.size;
	}
	getName() {
		return this.file.name;
	}
	doUpload() {
		const formData = new FormData();

		// add assoc key values, this will be posts values
		formData.append("action", "uploadNewFile");
		formData.append("file", this.file, this.getName());

		$.ajax({
			type: "POST",
			url: domain+"api/data.req.php",
			success: (data) => {
				return data
			},
			error: (error) => {
				console.log(formData.error + " #error");
			},
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 60000
		});
	}
	progressHandling(event) {
		let percent = 0;
		const position = event.loaded || event.position;
		const total = event.total;
		const progress_bar_id = "#progress-wrp";
		if (event.lengthComputable)
			percent = Math.ceil(position / total * 100);
		
		$(progress_bar_id + " .progress-bar").css("width", +percent + "%");
		$(progress_bar_id + " .status").text(percent + "%");
	}
}