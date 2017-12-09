import {Component, OnInit} from "@angular/core";
import {CommentService} from "../services/comment.service";
import {Status} from "../classes/status";
import {Comment} from "../classes/comment";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
	templateUrl: "./templates/comment.html",
	selector: "comment"
})

export class CommentComponent implements OnInit {

	commentForm: FormGroup;

	deleted: boolean = false;

	reportCommentForm: FormGroup;

	status: Status = null;

	comment: Comment = new Comment(null, null, null, null, null);

	constructor(private commentService: CommentService,
					private formBuilder: FormBuilder) {
	}

	ngOnInit(): void {

		this.commentForm = this.formBuilder.group({
			commentContent: ["", [Validators.required]]
		});

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

	editComment(): void {
		this.commentService.editComment(this.comment)
			.subscribe(status => this.status = status);
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