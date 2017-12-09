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
import {Router} from "@angular/router";

declare let $: any;

@Component({
	templateUrl: "./templates/report-listed-by-category.html",
	selector: "report-listed-by-category"
})

export class ReportListedByCategoryComponent implements OnInit {

	reportListedByCategoryForm: FormGroup;

	status: Status = null;

	category: Category = new Category(null, null);

	report: Report = new Report(null, null, null, null, null, null, null);

	reports: Report[] = [];

	categories: Category[] = [];

	constructor(
		private authService : AuthService,
		private router: Router,
		private formBuilder : FormBuilder,
		private reportService : ReportService,
		private categoryService : CategoryService,
		private profileService : ProfileService) {}

	// life cycling before George's eyes
	ngOnInit() : void {

		this.listCategories();

		this.getReportByCategoryId();

		this.reportListedByCategoryForm = this.formBuilder.group({

		});
	}

	listCategories() : void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	// needs to be fixed
    getReportByCategoryId() : void {
		this.reportService.getReportByReportCategoryId(this.report.reportCategoryId)
			.subscribe((reports: any) => this.report = reports);
	}

	updateReport() : void {
		let report = new Report(null, this.reportListedByCategoryForm.value.reportCategoryId, this.reportListedByCategoryForm.value.reportContent, null, null, this.reportListedByCategoryForm.value.reportStatus, this.reportListedByCategoryForm.value.reportUrgency);

		this.reportService.updateReport(report)
			.subscribe(status => {
				this.status = status;
				console.log(this.status);
				if(status.status === 200) {
					alert("Edit Successful");
					this.reportListedByCategoryForm.reset();
					setTimeout(function() {
						$("#report-admin-view-modal").modal('hide');
					}, 500);
				} else {
					alert("Error, there was a problem with one of your entries. Please try again.");
				}
			});
	}
}