
import {Component, ViewChild} from "@angular/core";
import {SignUpService} from "../services/sign.up.service";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignUp} from "../classes/sign.up";

declare let $: any;

@Component({
	templateUrl: "./templates/sign-up.html",
	selector: "sign-up"
})

export class SignUpComponent {

	@ViewChild("signUpForm") signUpForm: any;
	signItUp: SignUp = new SignUp (null, null, null, null);
	status: Status = null;


	constructor(

		private router: Router,
		private signUpService: SignUpService) {}


	signUp(): void {
		this.signUpService.createSignUp(this.signItUp)
			.subscribe(status => {
				this.status = status;
				console.log(this.status);
				if(status.status === 200) {
					alert("Please check with the admin to confirm your account.");
					this.signUpForm.reset();
					setTimeout(function() {
						$("signup-modal").modal('hide');
					}, 500);
					this.router.navigate([""]);
				} else {
					alert("Error, there was a problem with one of your entries. Please try again.");
				}
			});
	}
}
