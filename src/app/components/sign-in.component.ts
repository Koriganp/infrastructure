//this component controls the sign-in modal when "sign-in" is clicked
import {Component, OnInit} from "@angular/core";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignInService} from "../services/sign.in.service";
import {SignIn} from "../classes/sign.in";
import {CookieService} from "ng2-cookies";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SessionService} from "../services/session.service";
declare let $: any;

@Component({
	templateUrl: "./templates/sign-in.html",
	selector: "sign-in"
})

export class SignInComponent implements OnInit {

	signInForm: FormGroup;
	signin: SignIn = new SignIn(null, null);
	status: Status = null;
	//cookie: any = {};

	constructor(
		private signInService: SignInService,
		private sessionService: SessionService,
		private formBuilder: FormBuilder,
		private router: Router,
		private cookieService : CookieService) {}

	ngOnInit() : void {
		this.signInForm = this.formBuilder.group({
			profileEmail: ["", [Validators.maxLength(128), Validators.required]],
			profilePassword: ["", [Validators.maxLength(128), Validators.required]]
		});
		this.applyFormChanges();
	}

	applyFormChanges() : void {
		this.signInForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.signIn[field] = values[field];
			}
		});
	}

	signIn() : void {
		let signin = new SignIn(this.signInForm.value.profileEmail, this.signInForm.value.profilePassword);
		this.signInService.postSignIn(this.signin)
			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {
					this.sessionService.setSession();
					this.signInForm.reset();
					setTimeout(function() {
						$("#signin-modal").modal('hide');
					}, 500);
					this.router.navigate(["admin-dashboard"]);
					console.log("Sign in successful");
				} else {
					console.log("failed login");
				}
			});
	}
}