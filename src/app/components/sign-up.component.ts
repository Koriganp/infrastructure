
import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignUpService} from "../services/sign.up.service";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {Profile} from "../classes/profile";

declare const $: any;

@Component({
	templateUrl: "./templates/sign-up.html",
	selector: "sign-up"
})

export class SignUpComponent implements OnInit {

	signUpForm : FormGroup;
	profile: Profile = new Profile(null, null, null, null, null, null);
	status: Status = null;


	constructor(
		private formBuilder : FormBuilder,
		private router: Router,
		private signUpService: SignUpService) {}

	ngOnInit()  : void {
		this.signUpForm = this.formBuilder.group({
			profileUserName: ["", [Validators.maxLength(32), Validators.required]],
			profileEmail: ["", [Validators.maxLength(128), Validators.required]],
			profilePassword:["", [Validators.maxLength(128), Validators.required]],
			profilePasswordConfirm:["", [Validators.maxLength(128), Validators.required]]
		});
		this.applyFormChanges();
	}

	applyFormChanges() : void {
		this.signUpForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.profile[field] = values[field];
			}
		});
	}

	signUp() : void {
		this.signUpService.createProfile(this.profile)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.signUpService.createProfile(this.profile);
					this.signUpForm.reset();
					console.log("signup successful");
				} else {
					console.log("signup fail");
				}
			});
	}
}
