
import {Component, OnInit} from "@angular/core";
import {SignUpService} from "../services/sign.up.service";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignUp} from "../classes/sign.up";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

declare let $: any;

@Component({
	templateUrl: "./templates/sign-up.html",
	selector: "sign-up"
})

export class SignUpComponent implements OnInit {

	signUpForm: FormGroup;
	signItUp: SignUp = new SignUp (null, null, null, null);
	status: Status = null;


	constructor(
		private formBuilder: FormBuilder,
		private router: Router,
		private signUpService: SignUpService
	) {}

	ngOnInit() : void {
		this.signUpForm = this.formBuilder.group({
			profileUserName: ["", [Validators.maxLength(32), Validators.required]],
			profileEmail: ["", [Validators.maxLength(128), Validators.required]],
			profilePassword: ["", [Validators.maxLength(128), Validators.required]],
			profilePasswordConfirm: ["", [Validators.maxLength(128), Validators.required]]
		});
		this.applyFormChanges();
	}

	applyFormChanges() : void {
		this.signUpForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.signUp[field] = values[field];
			}
		});
	}

	signUp(): void {
		let signItUp = new SignUp(this.signUpForm.value.profileUserName, this.signUpForm.value.profileEmail, this.signUpForm.value.profilePassword, this.signUpForm.value.profilePasswordConfirm);
		this.signUpService.createSignUp(signItUp)
			.subscribe(status => {
				this.status = status;
				console.log(this.status);
				if(status.status === 200) {
					alert("Please check with the admin to confirm your account.");
					this.signUpForm.reset();
					setTimeout(function() {
						$("#signup-modal").modal('hide');
					}, 500);
					this.router.navigate(["home-view"]);
				} else {
					alert("Error, there was a problem with one of your entries. Please try again.");
				}
			});
	}
}
