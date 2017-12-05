import {Component, OnInit} from "@angular/core";
import {CategoryService} from "../services/category.service";
import {Category} from "../classes/category";

@Component({
	templateUrl: "./templates/report-category-dropdown.html",
	selector: "report-category-dropdown"
})

export class ReportCategoryDropdownComponent implements OnInit {
	categories: Category[] = [];
	constructor(
		private categoryService: CategoryService,
	) {}

	ngOnInit() : void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}


}