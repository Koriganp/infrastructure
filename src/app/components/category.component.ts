import {Component} from "@angular/core";
import {CategoryService} from "../services/category.service";
import {Status} from "../classes/status";
import {Category} from "../classes/category";

@Component({
	templateUrl: "./templates/category.html",
	selector: "category"
})

export class CategoryComponent {

	category: Category = new Category(null, null);

	//declare needed state variables for later use.
	status: Status = null;

	categories: Category[] = [];

	constructor(private categoryService : CategoryService) {}

	getCategories(): void {
		this.categoryService.getAllCategories()
			.subscribe(categories => this.categories = categories);
	}

}