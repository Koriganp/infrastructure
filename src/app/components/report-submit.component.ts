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
import {Data, Router} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import {temporaryDeclaration} from "@angular/compiler/src/compiler_util/expression_converter";

declare let $: any;

@Component({
	selector: "report-submit",
	templateUrl: "./templates/report-submit.html",
})

export class ReportSubmitComponent implements OnInit {

	public uploader: FileUploader = new FileUploader({
		itemAlias: "reportImage",
		url: "./api/image/",
		headers: [{name: "X-XSRF-TOKEN", value: Cookie.get("XSRF-TOKEN")}],
		additionalParameter: {}
	});

	protected cloudinaryPublicId: string = null;

	protected cloudinaryPublicIdObservable: Observable<string> = new Observable<string>();

	//declare needed state variables for later use.
	reportSubmitForm: FormGroup;

	category: Category = new Category(null, null);

	categories: Category[] = [];

	report: Report = new Report(null, null, null, null, null, null, null);

	image: Image = new Image(null, null, null, null, null);

	images: Image[] = [];

	status: Status = null;

	constructor(private formBuilder: FormBuilder,
					private reportService: ReportService,
					private categoryService: CategoryService,
					private imageService: ImageService,
					private router: Router) {
	}

	ngOnInit(): void {

		this.uploader.onSuccessItem = (item: any, response: string, status: number, headers: any) => {
			// let reply = JSON.parse(response);
			// this.cloudinaryPublicId = reply.data;
			// this.cloudinaryPublicIdObservable = Observable.from(this.cloudinaryPublicId);
			console.log(response);
		};

		this.listCategories();

		this.reportSubmitForm = this.formBuilder.group({
			reportCategoryId: ["", [Validators.required]],
			reportStreetAddress: ["", [Validators.maxLength(200), Validators.required]],
			reportCity: ["", [Validators.maxLength(40), Validators.required]],
			reportState: ["", [Validators.maxLength(30), Validators.required]],
			reportZipCode: ["", [Validators.max(10), Validators.required]],
			reportContent: ["", [Validators.maxLength(3000), Validators.required]],
			reportImage: ["", [Validators.required]]
		});

		this.applyFormChanges();
	}

	applyFormChanges(): void {
		this.reportSubmitForm.valueChanges.subscribe(values => {
			for(let field in values) {
				this.report[field] = values[field];
			}
		});
	}

	listCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	uploadImage(): void {
		this.uploader.uploadAll();
	}

	createReport(): void {

		let reportContentAddress = this.reportSubmitForm.value.reportStreetAddress + " " + this.reportSubmitForm.value.reportCity + " " + this.reportSubmitForm.value.reportState + " " + this.reportSubmitForm.value.reportZipCode;

		let report = new Report(null, this.reportSubmitForm.value.reportCategoryId, this.reportSubmitForm.value.reportContent, null, reportContentAddress, this.reportSubmitForm.value.reportStatus, this.reportSubmitForm.value.reportUrgency);

		this.reportService.createReport(report)
			.subscribe(status => {
				this.status = status;
				console.log(this.status);
				if(this.status.status === 200) {
					alert("Admin will confirm your report shortly");
				}
				this.reportSubmitForm.reset();

				let additionalParameter = {
					imageReportId: this.status.data
				};
				console.log(additionalParameter);

				this.uploader.options.additionalParameter = additionalParameter;
				this.uploader.uploadAll();
				console.log(this.uploader);


				// let image = new Image(null, this.status.data, null, null, null);
				//
				// this.imageService.uploadImage(image)
				// 	.subscribe(status => {
				// 		this.status = status;
				// 		console.log(this.status);
				// 		if(status.status === 200) {
				// 			alert("Images uploaded an will be confirmed by admin shortly.")
				// 		}
				// 	})
			});

	}

}