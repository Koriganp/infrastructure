//this component controls the sign-out modal when "sign-out" is clicked
import {Component} from "@angular/core";
import {Status} from "../classes/status";
import {SignOutService} from "../services/sign.out.service";
import {Router} from "@angular/router";

@Component({
	templateUrl: "./templates/sign-out.html",
	selector: "sign-out"
})

export class SignOutComponent {

	status: Status = null;

	constructor(
		private signOutService: SignOutService,
		private router: Router) {}

	signOut() : void {
		this.signOutService.getSignOut();
		window.location.reload();
		this.router.navigate(["home-view"]);
	}
}