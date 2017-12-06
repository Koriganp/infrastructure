import{Component} from "@angular/core";
import{Image} from "../classes/image";
import{ImageService} from "../services/image.service";
import{Status} from "../classes/status";

@Component({
	selector: "image",
	templateUrl: "./templates/report-submit.html"

})

export class ImageComponent {

	//declare needed state variables for later use.
	status: Status = null;

	image: Image = new Image(null, null, null,null, null)

	constructor(private imageService: ImageService) {}

	uploadImage(): void {
		let image = new Image(null, null, null, null, null);

		this.imageService.uploadImage(this.image)
			.subscribe(status => this.status = status);
	}
}