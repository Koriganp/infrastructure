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


    // call to API to delete comment
    deleteComment(commentId: number) : Observable<Status> {
        return(this.http.delete<Status>(this.commentUrl + commentId));
    }


}