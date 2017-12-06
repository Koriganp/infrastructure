//this component controls the sign-in modal when "sign-in" is clicked
import {Component, OnInit, ViewChild} from "@angular/core";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignInService} from "../services/sign.in.service";
import {SignIn} from "../classes/sign.in";
import {CookieService} from "ng2-cookies";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SessionService} from "../services/session.service";
declare const $: any;

@Component({
	templateUrl: "./templates/sign-in.html",
	selector: "sign-in"
})

export class SignInComponent implements OnInit {
	@ViewChild("signInForm")

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
				this.signin[field] = values[field];
			}
		});
	}

	signIn() : void {
		this.signInService.postSignIn(this.signin)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.sessionService.setSession();
					this.signInForm.reset();
					this.router.navigate(["admin-dashboard"]);
					console.log("signin successful");
				} else {
					console.log("failed login");
				}
			});
	}
}