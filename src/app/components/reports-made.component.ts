/*
this lists all reports
 */
import {Component, OnInit} from "@angular/core";
import {Report} from "../classes/report";
import {ReportService} from "../services/report.service";
import {Category} from "../classes/category";
import {CategoryService} from "../services/category.service";
import {Status} from "../classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
	templateUrl: "./templates/reports-made.html",
	selector: "reports-made"
})

export class ReportsMadeComponent implements OnInit{

	reportsMadeForm: FormGroup;

	status : Status = null;

	category : Category = new Category(null, null);

	report : Report = new Report(null, null, null, null, null, null, null);

	reports: Report[] = [];

	 constructor(
		private formBuilder : FormBuilder,
		private reportService : ReportService,
		private categoryService : CategoryService) {}

	 ngOnInit() : void {
	 	this.listAllReports();
	 }

	 listAllReports() : void {
		this.reportService.getAllReports()
			.subscribe(reports => this.reports = reports);
	 }





}