import {Component, OnInit} from "@angular/core";
import {CommentService} from "../services/comment.service";
import {Status} from "../classes/status";
import {Comment} from "../classes/comment";
import {FormBuilder, Validators} from "@angular/forms";

@Component({
    templateUrl: "./templates/comment.html",
    selector: "comment"
})

export class CommentComponent implements OnInit{

    commentForm: FormBuilder;

    status: Status = null;

    comment: Comment = new Comment(null, null, null, null, null);

    constructor (private commentService : CommentService, private formBuilder : FormBuilder) {}

    ngOnInit() : void {

        this.commentForm = this.formBuilder.group({
           commentContent: ["", [Validators.required]]
        });

    }

    createComment() : void {
        this.commentService.createComment(this.comment)
            .subscribe(status => this.status = status);
    }
}