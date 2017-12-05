import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {SignUp} from "../classes/Sign.up";
import {Observable} from "rxjs/Observable";
import {Status} from "../classes/status";

@Injectable()
export class SignUpService {
	constructor(protected http: HttpClient) {

	}

	private signUpUrl = "api/sign-up/";

	createProfile(signUp: SignUp) : Observable<Status> {
		return(this.http.post<Status>(this.signUpUrl, signUp));

	}
}