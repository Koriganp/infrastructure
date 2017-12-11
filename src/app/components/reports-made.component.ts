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

	categories : Category[] = [];

	report : Report = new Report(null, null, null, null, null, null, null);

	reports: Report[] = [];

	 constructor(
		private formBuilder : FormBuilder,
		private reportService : ReportService,
		private categoryService : CategoryService) {}

	 ngOnInit() : void {

	 	this.listCategories();

	 	this.listAllReports();
	 }

	 listAllReports() : void {
		this.reportService.getAllReports()
			.subscribe(reports => this.reports = reports);
	 }
	listCategories() : void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

	getCategorybyCategoryId(categoryId : string) : Category {
		return (this.categories.find(searchCategory => searchCategory.categoryId === categoryId));
	}






}