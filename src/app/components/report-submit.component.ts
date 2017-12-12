import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import {FileUploader} from "ng2-file-upload";
import {DomSanitizer, SafeUrl} from '@angular/platform-browser';
import {Cookie} from "ng2-cookies";
import {Report} from "../classes/report";
import {ReportService} from "../services/report.service";
import {Category} from "../classes/category";
import {CategoryService} from "../services/category.service";
import {Image} from "../classes/image";
import {ImageService} from "../services/image.service";
import {Status} from "../classes/status";
import {Router} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";

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

	public filePreviewPath: SafeUrl;

	protected imageCloudinary: string = null;

	protected imageCloudinaryObservable: Observable<string> = new Observable<string>();

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
					private sanitizer: DomSanitizer,
					private router: Router) {

		this.uploader.onAfterAddingFile = (fileItem) => {
			this.filePreviewPath = this.sanitizer.bypassSecurityTrustUrl((window.URL.createObjectURL(fileItem._file)));
		}
	}

	ngOnInit(): void {

		this.uploader.onSuccessItem = (item: any, response: string, status: number, headers: any) => {
			let reply = JSON.parse(response);
			this.imageCloudinary = reply.data;
			this.imageCloudinaryObservable = Observable.from(this.imageCloudinary);
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
				this.filePreviewPath = null;
			});

	}

}