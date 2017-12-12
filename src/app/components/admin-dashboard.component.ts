/*
this is used to list all reports based on category or something
*/

import {Component, OnInit} from "@angular/core";
import {JwtHelperService} from '@auth0/angular-jwt';
import {ReportService} from "../services/report.service";
import {CategoryService} from "../services/category.service";
import {Report} from "../classes/report";
import {Category} from "../classes/category";
import {Status} from "../classes/status";
import {FormBuilder, FormGroup} from "@angular/forms";
import {Router} from "@angular/router";

declare let $: any;

@Component({
	templateUrl: "./templates/admin-dashboard.html",
})

export class AdminDashboardComponent implements OnInit {

	// Not being called anywhere in the app
	// reportListedByCategoryForm: FormGroup;

	status: Status = null;

	reports: Report[] = [];

	report: Report = new Report(null, null, null, null, null, null, null);

	categories: Category[] = [];

	category: Category = new Category(null, null);


	constructor(
		private router: Router,
		private formBuilder : FormBuilder,
		private reportService : ReportService,
		private categoryService : CategoryService,
		private jwtHelperService : JwtHelperService) {}

	// life cycling before George's eyes

	ngOnInit() : void {
		this.listCategories();
		this.listAllReports();
	}

	listAllReports() : void {
		this.reportService.getAllReports()
			.subscribe(reports => this.reports = reports);
	}

	goToReport(report : Report) : void {
		console.log(report.reportId);
		this.router.navigate(["/report-admin-view/", report.reportId]);
	}

	listCategories() : void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	getCategoryByCategoryId(categoryId : string) : Category {
		return (this.categories.find(searchCategory => searchCategory.categoryId === categoryId));
	}

	getReportByReportId(): void {
		this.reportService.getReportByReportId(this.report.reportId)
			.subscribe(report => this.report = report);
	}

	// needs to be fixed
	getReportByCategoryId() : void {
		this.reportService.getReportByReportCategoryId(this.report.reportCategoryId)
			.subscribe((reports: any) => this.report = reports);
	}

}