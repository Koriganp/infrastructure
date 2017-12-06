import{Component} from "@angular/core";
import{Image} from "../classes/image";
import{ImageService} from "../services/image.service";
import {FileUploader} from "ng2-file-upload";
import {Cookie} from "ng2-cookies";
import{Status} from "../classes/status";

@Component({
	selector: "image",
	templateUrl: "./templates/report-submit.html"

})

export class ImageComponent {

	public uploader: FileUploader = new FileUploader({
		itemAlias: "",
		url: "./api/image/",
		headers: [{name: "X-XSRF-TOKEN", value: Cookie.get("XSRF-TOKEN")}],
		additionalParameter: {}
	});

	//declare needed state variables for later use.
	status: Status = null;

	image: Image = new Image(null, null, null,null, null)

	constructor(private imageService: ImageService) {}

	uploadImage(): void {
		this.uploader.uploadAll();

		// let image = new Image(null, null, null, null, null);
		//
		// this.imageService.uploadImage(this.image)
		// 	.subscribe(status => this.status = status);
	}
}