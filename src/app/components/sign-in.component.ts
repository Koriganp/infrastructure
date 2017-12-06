//this component controls the sign-in modal when "sign-in" is clicked
import {Component, ViewChild} from "@angular/core";



import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignInService} from "../services/sign.in.service";
import {SignIn} from "../classes/sign.in";
import {CookieService} from "ng2-cookies";
declare let $: any;

@Component({
	templateUrl: "./templates/sign-in.html",
	selector: "sign-in"
})

export class SignInComponent {
	@ViewChild("signInForm") signInForm: any;

	signIn: SignIn = new SignIn(null, null);
	status: Status = null;
	//cookie: any = {};
	constructor(private signInService: SignInService, private router: Router, private cookieService : CookieService) {
	}



	createSignIn(): void {
		this.signInService.postSignIn(this.signIn).subscribe(status => {
			this.status = status;

			if(status.status === 200) {

				this.router.navigate([""]);
				//location.reload(true);
				this.signInForm.reset();
				setTimeout(function(){$("#signin-modal").modal('hide');},1000);
			} else {
				console.log("failed login")
			}
		});
	}
}