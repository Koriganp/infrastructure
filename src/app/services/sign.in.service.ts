import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {SignIn} from "../classes/sign.in";
import {Observable} from "rxjs/Observable";
import {Profile} from "../classes/profile";
import {Status} from "../classes/status";


@Injectable()
export class SignInService {

	constructor(
		protected http : HttpClient) {}

	private signInUrl = "api/sign-in/";
	private signOutUrl = "api/sign-out";


	getProfileByProfileEmail (ProfileEmail: string) :Observable<Profile> {
		return(this.http.get<Profile>(this.signInUrl + "?ProfileEmail=" + ProfileEmail));
	}

	// call to the API and get a Category by Category Name
	getProfileActivationToken (ProfileActivationToken: string) :Observable<Profile> {
		return(this.http.get<Profile>(this.signInUrl + "?ProfileActivationToken=" + ProfileActivationToken));
	}

	// call to the API and get a Category by Category Name
	getProfileByProfileId (ProfileId: string) :Observable<Profile> {
		return(this.http.get<Profile>(this.signInUrl + "?ProfileId=" + ProfileId));
	}

	//preform the post to initiate sign in
	postSignIn(signIn : SignIn) : Observable<Status> {
		return(this.http.post<Status>(this.signInUrl, signIn));
	}

	signOut() : Observable<Status> {
		return(this.http.get<Status>(this.signOutUrl));
	}

}