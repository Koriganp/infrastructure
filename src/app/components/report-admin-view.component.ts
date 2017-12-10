import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import {AuthService} from "../services/auth.service";
import {Profile} from "../classes/profile";
import {ProfileService} from "../services/profile.services";
import {Report} from "../classes/report";
import {ReportService} from "../services/report.service";
import {Comment} from "../classes/comment";
import {CommentService} from "../services/comment.service";
import {Category} from "../classes/category";
import {CategoryService} from "../services/category.service";
import {Image} from "../classes/image";
import {ImageService} from "../services/image.service";
import {Status} from "../classes/status";

declare let $: any;

@Component({
	selector: "report-admin-view",
	templateUrl: "./templates/report-admin-view.html"
})

export class ReportAdminViewComponent implements OnInit {

	reportAdminViewForm: FormGroup;

	deleted: boolean = false;

	profile: Profile = new Profile(null, null, null, null, null, null);

	category: Category = new Category(null, null);

	categories: Category[] = [];

	report: Report = new Report(null, null, null, null, null, null, null);

	image: Image = new Image(null, null, null, null, null);

	comment: Comment = new Comment(null, null, null, null, null);

	//declare needed state variables for later use
	status: Status = null;

	constructor(
		private authService: AuthService,
		private formBuilder: FormBuilder,
		private profileService: ProfileService,
		private reportService: ReportService,
		private commentService: CommentService,
		private categoryService: CategoryService,
		private imageService: ImageService) {}

	ngOnInit() : void {

		this.listCategories();
		// this.getReportByReportId();

		this.reportAdminViewForm = this.formBuilder.group({
			reportCategoryId: ["", [Validators.required]],
			reportStatus: ["", [Validators.required]],
			reportUrgency: ["", [Validators.required]],
			commentContent: ["", [Validators.maxLength(500), Validators.required]]
		});

	}

	getReportByReportId(): void {
		this.reportService.getReportByReportId(this.report.reportId)
			.subscribe(report => this.report = report);
	}

	listCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	updateReport() : void {
		let report = new Report(null, this.reportAdminViewForm.value.reportCategoryId, this.reportAdminViewForm.value.reportContent, null, null, this.reportAdminViewForm.value.reportStatus, this.reportAdminViewForm.value.reportUrgency);

		this.reportService.updateReport(report)
			.subscribe(status => {
				this.status = status;
				console.log(this.status);
				if(status.status === 200) {
					alert("Edit Successful");
					this.reportAdminViewForm.reset();
					setTimeout(function() {
						$("#report-admin-view-modal").modal('hide');
					}, 500);
				} else {
					alert("Error, there was a problem with one of your entries. Please try again.");
				}
			});
	}

	deleteReport(): void {
		this.reportService.deleteReport(this.report.reportId)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.deleted = true;
					this.report = new Report(null, null, null, null, null, null, null);
				}
			})
	}

	getCommentByProfileId(): void {
		this.commentService.getCommentByCommentProfileId(this.comment.commentProfileId)
			.subscribe(comment => this.status = comment);
	}

	getCommentByReportId(): void {
		this.commentService.getCommentByCommentReportId(this.comment.commentReportId)
			.subscribe(comment => this.status = comment);
	}

	createComment(): void {
		let comment = new Comment(null, null, null, this.reportAdminViewForm.value.commentContent, null);

		this.commentService.createComment(comment)
			.subscribe(status =>{
				this.status = status;
				if(this.status.status === 200) {
					this.reportAdminViewForm.reset();
					alert(this.status.message);
				}
			})
	}

	editComment() : void {
		this.commentService.editComment(this.comment)
			.subscribe(status => this.status = status);
	}

	deleteComment() : void {
		this.commentService.deleteComment(this.comment.commentId)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.deleted = true;
					this.comment = new Comment(null, null, null, null, null);
				}
			})
	}

}