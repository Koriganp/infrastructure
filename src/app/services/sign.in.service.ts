import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {SignIn} from "../classes/sign.in";
import {Observable} from "rxjs/Observable";
import {Status} from "../classes/status";


@Injectable()
export class SignInService {

	constructor(
		protected http : HttpClient) {}

	private signInUrl = "api/sign-in/";

	//preform the post to initiate sign in
	postSignIn(signIn : SignIn) : Observable<Status> {
		return(this.http.post<Status>(this.signInUrl, signIn));
	}

}