import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ActivatedRoute, Params} from "@angular/router";

import {Status} from "../classes/status";
import {Report} from "../classes/report";
import {ReportService} from "../services/report.service";
import {Category} from "../classes/category";
import {CategoryService} from "../services/category.service";
import {Image} from "../classes/image";
import {ImageService} from "../services/image.service";


@Component({
	selector: "report-submit",
	templateUrl: "./templates/report-submit.html",
})

export class ReportSubmitComponent implements OnInit {

	//declare needed state variables for later use.
	reportSubmitForm: FormGroup;

	category: Category = new Category(null, null);
	report: Report = new Report(null, null, null, null, null, null, null);
	image: Image = new Image(null, null, null, null, null);


	status: Status = null;

	categories: Category[] = [];

	constructor(
		private reportService: ReportService,
		private categoryService: CategoryService,
		private imageService: ImageService,
		private formBuilder: FormBuilder,
		) {}

	ngOnInit() : void {

		this.listCategories();

		this.reportSubmitForm = this.formBuilder.group({
			reportCategoryId: ["", [Validators.required]],
			reportContent: ["", [Validators.maxLength(3000), Validators.required]]
		});
	}

	listCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	createReport(): void {

		let category = new Category(null, null);

		let report = new Report(null, null, this.reportSubmitForm.value.reportContent, null, null,null,null);

		let image = new Image(null, null, null, null, null);

		this.reportService.createReport(this.report)
			.subscribe(status => this.status = status);
	}
}