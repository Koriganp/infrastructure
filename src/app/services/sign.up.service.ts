import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {SignUp} from "../classes/Sign.up";
import {Observable} from "rxjs/Observable";

@Injectable()
export class SignUpService {
	constructor(protected http: HttpClient) {

	}

	private signUpUrl = "api/sign-up/";

	// call to the API and get a Category by Category Name
	getProfileByProfileEmail (ProfileEmail: string) :Observable<SignUp[]> {
		return(this.http.get<SignUp[]>(this.signUpUrl + "?ProfileEmail=" + ProfileEmail));
	}
	createProfile(signUp: SignUp) : Observable<SignUp> {
		return(this.http.post<SignUp>(this.signUpUrl, signUp));

	}
}