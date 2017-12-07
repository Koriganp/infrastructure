import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import {FileUploader} from "ng2-file-upload";
import {Cookie} from "ng2-cookies";
import {Report} from "../classes/report";
import {ReportService} from "../services/report.service";
import {Category} from "../classes/category";
import {CategoryService} from "../services/category.service";
import {Image} from "../classes/image";
import {ImageService} from "../services/image.service";
import {Status} from "../classes/status";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";


@Component({
	selector: "report-submit",
	templateUrl: "./templates/report-submit.html",
})

export class ReportSubmitComponent implements OnInit {

	public uploader: FileUploader = new FileUploader({
		itemAlias: "",
		url: "./api/image/",
		headers: [{name: "X-XSRF-TOKEN", value: Cookie.get("XSRF-TOKEN")}],
		additionalParameter: {}
	});

	protected cloudinaryPublicId : string = null;
	protected cloudinaryPublicIdObservable : Observable<string> = new Observable<string>();

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
		private categoryService: CategoryService
		) {
		this.createReport();
	}

	ngOnInit() : void {

		this.listCategories();

		this.reportSubmitForm = this.formBuilder.group({
			reportCategoryId: ["", [Validators.required]],
			reportStreetAddress: ["", [Validators.maxLength(200),Validators.required]],
			reportCity: ["",[Validators.maxLength(40), Validators.required]],
			reportState: ["",[Validators.maxLength(30), Validators.required]],
			reportZipCode: ["",[Validators.max(10), Validators.required]],
			reportContent: ["", [Validators.maxLength(3000), Validators.required]],
		});

		this.uploadImage();
	}

	listCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	createReport(): void {
		this.reportSubmitForm = this.formBuilder.group({
			reportStreetAddress: '',
			reportCity: '',
			reportState: '',
			reportZipCode: '',
			reportContent: ''

		});


		this.reportService.createReport(this.report)
			.subscribe(status => this.status = status);
	}

	uploadImage(): void {
		this.uploader.uploadAll();
	}

}