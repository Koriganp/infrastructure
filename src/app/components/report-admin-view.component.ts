import {Component, OnInit} from "@angular/core";
import {AuthService} from "../services/auth.service";
import {ReportService} from "../services/report.service";
import {Report} from "../classes/report";
import {Image} from "../classes/image";
import {Comment} from "../classes/comment";
import {CommentService} from "../services/comment.service";
import {Status} from "../classes/status";
import {Profile} from "../classes/profile";
import {ProfileService} from "../services/profile.services";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Category} from "../classes/category";
import {ImageService} from "../services/image.service";

@Component({
	selector: "report-admin-view",
	templateUrl: "./templates/report-admin-view.html",
})

export class ReportAdminViewComponent {
	createReportAdminForm: FormGroup;

	category: Category = new Category(null, null);

	report: Report = new Report(null, null, null,null, null, null,null, null, null);

	image: Image = new Image(null, null, null, null, null);

	comment: Comment = new Comment(null, null, null, null, null);

	//declare needed state variables for later use
	status: Status = null;

	constructor(private authService: AuthService, private formBuilder: FormBuilder, private profileService: ProfileService, private reportService: ReportService, private imageService: ImageService, private commentService: CommentService) {}


}
