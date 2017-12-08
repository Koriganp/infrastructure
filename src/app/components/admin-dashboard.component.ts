import {Component} from "@angular/core";
import {Comment} from "../classes/comment";
import {CommentService} from "../services/comment.service";
import {ImageService} from "../services/image.service";
import {ReportService} from "../services/report.service";
import {Profile} from "../classes/profile";
import {SignUp} from "../classes/sign.up";
import {CommentComponent} from "./comment.component";
import {ReportAdminViewComponent} from "./report-admin-view.component";

@Component({
	templateUrl: "./templates/admin-dashboard.html",
	//template: '<report-admin-view></report-admin-view>',
	selector: "admin-dashboard"
})
export class AdminDashboardComponent {


}