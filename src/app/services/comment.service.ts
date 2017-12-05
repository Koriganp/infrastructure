import {Injectable} from "@angular/core";

import {Status} from "../classes/status";
import {Comment} from "../classes/comment";
import {Observable} from "rxjs/Observable";
import {HttpClient} from "@angular/common/http";

@Injectable ()
export class CommentService {

    constructor(protected http : HttpClient ) {}

    // define the API endpoint
    private commentUrl = "api/comment/";


    // call to API to delete a comment
    deleteComment(commentId: number) : Observable<Status> {
        return(this.http.delete<Status>(this.commentUrl + commentId));
    }

    // call to API to update a comment
    editComment(comment : Comment) : Observable<Status> {
        return(this.http.put<Status>(this.commentUrl + comment.commentId, comment));
    }

    // call to API to create a comment
    createComment(comment : Comment) : Observable<Status> {
        return(this.http.post<Status>(this.commentUrl, comment));
    }

    // call to API to get comment by comment ID
    getComment(commentId: string) : Observable<Status> {
        return(this.http.get<Status>(this.commentUrl + commentId));
    }

    // call to API to get comment by comment profile ID
    getCommentByCommentProfileId(commentId: string) : Observable<Status> {
        return(this.http.get<Status>(this.commentUrl + commentId));
    }

    // call to API to get comment by comment report ID
    getCommentByCommentReportId(commentId: string) : Observable<Status> {
        return(this.http.get<Status>(this.commentUrl + commentId));
    }

    // call to API to get comment by comment content
    getCommentByCommentContent(commentId: string) : Observable<Status> {
        return(this.http.get<Status>(this.commentUrl + commentId));
    }

}