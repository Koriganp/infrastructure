import{Injectable} from "@angular/core";
import{Status} from "../classes/status";
import{Image} from "../classes/image";
import{Observable} from "rxjs/Observable";
import {HttpClient} from "@angular/common/http";

@Injectable ()
export class ImageService{

	constructor(protected http : HttpClient ) {}

}