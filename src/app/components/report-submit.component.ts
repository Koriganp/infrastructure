import {Component, OnInit} from "@angular/core";
import {ReportService} from "../services/report.service";
import {Status} from "../classes/status";
import {Report} from "../classes/report";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ActivatedRoute, Params} from "@angular/router";
import {CategoryService} from "../services/category.service";
import {Category} from "../classes/category";

@Component({
	selector: "report-submit",
	templateUrl: "./templates/report-submit.html",
})

export class ReportSubmitComponent implements OnInit {

	//declare needed state variables for later use.
	categories: Category[] = [];
	reportForm: FormGroup;
	status: Status = null;
	report: Report = new Report(null, null, null, null, null, null, null, null, null,);

	constructor(
		private reportService : ReportService,
		private categoryService: CategoryService,
		private formBuilder: FormBuilder,
		private route: ActivatedRoute) {}

	ngOnInit() : void {
		this.route.params.forEach((params : Params) => {
			let reportId = params["reportId"];
			this.reportService.getReport(reportId)
				.subscribe(report => {
					this.report = report;
					this.reportForm.patchValue(report);
				});
		});
		this.reportForm = this.formBuilder.group({
			reportCategoryId: ["", []],
			reportContent: ["", [Validators.maxLength(3000), Validators.required]],
			reportStatus: ["", []],
			reportUrgency: ["", []]
		});
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	createReport(): void {
		this.reportService.createReport(this.report)
			.subscribe(status => this.status = status);
	}
}