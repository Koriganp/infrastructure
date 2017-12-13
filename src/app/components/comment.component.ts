import {Component, OnInit} from "@angular/core";
import {CommentService} from "../services/comment.service";
import {Status} from "../classes/status";
import {Comment} from "../classes/comment";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Report} from "../classes/report";
import {Profile} from "../classes/profile";
import {ProfileService} from "../services/profile.services";


@Component({
	templateUrl: "./templates/comment.html",
	selector: "comment"
})

export class CommentComponent implements OnInit {

	commentForm: FormGroup;

	deleted: boolean = false;

	reportCommentForm: FormGroup;

	status: Status = null;

	profile: Profile = new Profile(null, null, null, null, null, null);

	report: Report = new Report(null, null, null, null, null, null, null);

	comment: Comment = new Comment(null, null, null, null, null);

	constructor(
		private commentService: CommentService,
		private formBuilder: FormBuilder,
		private profileService: ProfileService) {
	}

	ngOnInit(): void {

		this.commentForm = this.formBuilder.group({
			commentContent: ["", [Validators.required]]
		});

		this.applyFormChanges();

		this.profileService.getProfile(this.comment.commentProfileId)
			.subscribe(profile => this.profile = profile);

		this.getCommentByReportId();

	}

	applyFormChanges(): void {
		this.commentForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.comment[field] = values[field];
			}
		});
	}

	getCommentByReportId(): void {
		this.commentService.getCommentByCommentReportId(this.comment.commentReportId)
			.subscribe(comment => this.status = comment);
	}

	createComment(): void {

		let comment = new Comment(null, null, null, this.reportCommentForm.value.commentContent, null);

		this.commentService.createComment(comment)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.reportCommentForm.reset();
					alert(this.status.message);
				}
			})
	}

	deleteComment(): void {
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