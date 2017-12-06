
import {Component} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignUp} from "../classes/sign.up";
import {SignUpService} from "../services/sign.up.service";
import {Router} from "@angular/router";
import {Status} from "../classes/status";

@Component({
	templateUrl: "./templates/sign-up.html",
	selector: "sign-up"
})

export class SignUpComponent {
	signUpForm : FormGroup;

	status: Status = null;


	constructor(private formBuilder : FormBuilder, private router: Router, private signUpService: SignUpService) {
		console.log("")
	}

	ngOnInit()  : void {
		this.signUpForm = this.formBuilder.group({
			userName: ["", [Validators.maxLength(32), Validators.required]],
			email: ["", [Validators.maxLength(128), Validators.required]],
			password:["", [Validators.maxLength(128), Validators.required]],
			passwordConfirm:["", [Validators.maxLength(128), Validators.required]]
		});

	}

	createSignUp(): void {

		let signUp =  new SignUp(this.signUpForm.value.userName, this.signUpForm.value.email, this.signUpForm.value.password, this.signUpForm.value.passwordConfirm);
		console.log(this.signUpService);
		this.signUpService.createProfile(signUp)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					alert(status.message);
					this.signUpForm.reset();
					this.router.navigate([""]);
				}
			});
	}
}