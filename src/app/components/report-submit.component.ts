import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import {Report} from "../classes/report";
import {ReportService} from "../services/report.service";
import {Category} from "../classes/category";
import {CategoryService} from "../services/category.service";
import {Image} from "../classes/image";
import {ImageService} from "../services/image.service";
import {Status} from "../classes/status";


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
		private formBuilder: FormBuilder,
		private reportService: ReportService,
		private categoryService: CategoryService,
		private imageService: ImageService

		) {}

	ngOnInit() : void {

		this.listCategories();

		this.reportSubmitForm = this.formBuilder.group({
			reportCategoryId: ["", [Validators.required]],
			reportContent: ["", [Validators.maxLength(3000), Validators.required]],
			reportImage: ["", ]
		});
	}

	listCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	createReport(): void {

		this.reportService.createReport(this.report)
			.subscribe(status => this.status = status);
	}

}