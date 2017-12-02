import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

import {Status} from "./classes/status";
import {Category} from "./classes/category";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class CategoryService  {

	constructor(protected http : HttpClient) {

	}
	//define the API endpoint
	private categoryUrl = "api/category/";

	// call to the Profile API and get a Profile object by its id
	getCategory(id: number) : Observable<Category> {
		return(this.http.get<Category>(this.categoryUrl + id));
	}

	// call to the API to grab an array of profiles based on the user input
	getCategoryByCategoryName (categoryName: string) :Observable<Category[]> {
		return(this.http.get<Category[]>(this.categoryUrl + "?categoryName=" + categoryName));
	}
}