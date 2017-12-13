import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
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
	templateUrl: "./templates/report-admin-view.html"
})

export class ReportAdminViewComponent implements OnInit {

	reportAdminViewForm: FormGroup;

	deleted: boolean = false;

	data: string;

	profile: Profile = new Profile(null, null, null, null, null, null);

	category: Category = new Category(null, null);

	categories: Category[] = [];

	images: Image[] = [];

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
		private route: ActivatedRoute,
		private imageService: ImageService) {}

	reportStatusSeed: string[] = ['Reported', 'Confirmed', 'Investigating Report', 'In Progress', 'Completed'];
	reportUrgencySeed: number[] = [1, 2, 3, 4, 5];

	ngOnInit() : void {

		this.listCategories();

		this.reportService.dataString$.subscribe(
			data => {
				this.data = data;
				console.log(data);
				this.report.reportId = data;
				this.reportService.getReportByReportId(this.report.reportId)
					.subscribe(report => this.report = report);
				console.log(this.report);
				this.reportService.getImageByImageReportId(this.report.reportId)
					.subscribe(images => this.images = images);
				console.log(this.images);
			});

		this.route.params.forEach((params : Params) => {
			let reportId = params["reportId"];
			this.reportService.getReport(reportId)
				.subscribe(report => {
					this.report = report;
					this.reportAdminViewForm.patchValue(report);
				})
		});

		this.reportAdminViewForm = this.formBuilder.group({
			reportDateTime: [this.report.reportDateTime],
			reportCategoryId: ["", [Validators.required]],
			reportStatus: [this.reportStatusSeed, [Validators.required]],
			reportUrgency: [this.reportUrgencySeed, [Validators.required]],
			commentContent: [this.comment.commentContent, [Validators.maxLength(500), Validators.required]]
		});

		this.applyFormChanges();
	}

	applyFormChanges() : void {
		this.reportAdminViewForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.report[field] = values[field];
			}
		})
	}

	listCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	getReportByReportId(reportId : string): void {
		this.reportService.getReportByReportId(reportId)
			.subscribe(report => {
				console.log(this.report = report);
				this.reportAdminViewForm.patchValue(report);
				console.log(this.reportAdminViewForm);
			});
	}

	updateReport() : void {
		let report = new Report(null, this.reportAdminViewForm.value.reportCategoryId, this.reportAdminViewForm.value.reportContent, null, null, this.reportAdminViewForm.value.reportStatus, this.reportAdminViewForm.value.reportUrgency);

		this.reportService.updateReport(this.report)
			.subscribe(status => this.status = status);

		this.reportService.updateReport(report)
			.subscribe(status => {
				this.status = status;
				console.log(this.status);
				if(status.status === 200) {
					 alert("Edit Successful");
					this.reportAdminViewForm.reset();
					setTimeout(function() {
						$("#report-admin-view-mod").modal('hide');
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