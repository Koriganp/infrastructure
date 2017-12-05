import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Category} from "../classes/category";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class CategoryService  {

	constructor(protected http : HttpClient) {

	}
	//define the API endpoint
	private categoryUrl = "api/category/";

	// call to the Category API and get a Category object by its id
	getCategory(id: string) : Observable<Category> {
		return(this.http.get<Category>(this.categoryUrl + id));
	}

	// call to the API and get a Category by Category Name
	getCategoryByCategoryName (categoryName: string) :Observable<Category[]> {
		return(this.http.get<Category[]>(this.categoryUrl + "?categoryName=" + categoryName));
	}

	// call to the API to grab an array of categories
	getAllCategories() :Observable<Category[]> {
		return(this.http.get<Category[]>(this.categoryUrl));
	}
}