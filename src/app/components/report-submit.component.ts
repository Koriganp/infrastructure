import {Component} from "@angular/core";
import {ReportService} from "../services/report.service";
import {Status} from "../classes/status";
import {Report} from "../classes/report";

@Component({
	selector: "report-submit",
	templateUrl: "./templates/report-submit.html",
})

export class ReportSubmitComponent {

	//declare needed state variables for later use.
	status: Status = null;

	report: Report = new Report(null, null, null, null, null, null, null, null, null,);

	constructor(private reportService : ReportService) {}

	createReport(): void {
		this.reportService.createReport(this.report)
			.subscribe(status => this.status = this.status);
	}
}