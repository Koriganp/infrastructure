/*
this is used to list all reports based on category or something
*/

import {Component, OnInit} from "@angular/core";
import {AuthService} from "../services/auth.service";
import {ReportService} from "../services/report.service";
import {CategoryService} from "../services/category.service";
import {ProfileService} from "../services/profile.services";
import {Report} from "../classes/report";
import {Category} from "../classes/category";
import {Status} from "../classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Observable} from "rxjs/Observable";

@Component({
	templateUrl: "./templates/report-listed-by-category.html",
	selector: "report-listed-by-category"
})

export class ReportListedByCategoryComponent implements OnInit {

	reportListedByCategoryForm: FormGroup;

	status : Status = null;

	category : Category = new Category(null, null);

	report : Report = new Report(null, null, null, null, null, null, null);

	reports: Report[] = [];

	categories: Category[] = [];

	constructor(
		private authService : AuthService,
		private formBuilder : FormBuilder,
		private reportService : ReportService,
		private categoryService : CategoryService,
		private profileService : ProfileService) {}

	// life cycling before George's eyes
	ngOnInit() : void {

		this.listCategories();

		this.getReportsByCategoryId();

		this.reportListedByCategoryForm = this.formBuilder.group({

		});
	}

	listCategories() : void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	// needs to be fixed
	getReportsByCategoryId() : void {
		this.reportService.getAllReports()
			.subscribe((reports: any) => this.reports = reports);
	}

}