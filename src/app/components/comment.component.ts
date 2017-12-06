import {Component} from "@angular/core";
import {CommentService} from "../services/comment.service";
import {Status} from "../classes/status";
import{Comment} from "../classes/comment";

@Component({
    templateUrl: "./templates/comment.html",
    selector: "comment"
})

export class CommentComponent {

    status: Status = null;

    comment: Comment = new Comment(null, null, null, null, null);

    constructor (private commentService : CommentService) {}

    createComment() : void {
        this.commentService.createComment(this.comment).subscribe(status => this.status = status);
    }
}