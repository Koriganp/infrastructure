import {Component, OnInit} from "@angular/core";

import {AuthService} from "../services/auth.service";
import {ReportService} from "../services/report.service";
import {CategoryService} from "../services/category.service";
import {Status} from "../classes/status";
import {Report} from "../classes/report";
import {Category} from "../classes/category";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
	selector: "list-reports",
	templateUrl: "./templates/list-reports.html"
})

export class ListReportsComponent implements OnInit{

	createReportForm: FormGroup;

	report : Report = new Report (null, null, null, null);

	//declare needed state variables for latter use
	status: Status = null;

	reports: Report[] = [];


	constructor(  private authService : AuthService , private formBuilder: FormBuilder, private categoryService: CategoryService, private reportService: ReportService ) {}

	//life cycling before my eyes
	ngOnInit() : void {
		this.listTweets();

		this.createReportForm = this.formBuilder.group({
			ReportContent: ["",[Validators.maxLength(3000), Validators.minLength(1), Validators.required]]
		});
	}

	getReportCategory (): void {
		this.categoryService.getCategory(this.report.reportCategoryId)
	}


	listReports(): void {
		this.reportService.getAllReports()
			.subscribe(reports => this.reports = reports);
	}
	createReport(): void  {

		let report = new Report(null, null, this.createReportForm.value.reportContent, null);

		this.reportService.createReport(report)
			.subscribe(status =>{
				this.status = status;
				if(this.status.status ===200) {
					this.createReportForm.reset();
					alert(this.status.message);
					this.listReports();
				}
			});
	}

}