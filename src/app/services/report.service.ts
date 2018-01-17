import {Injectable}  from "@angular/core";
import {Subject} from 'rxjs/Subject';

import {Status} from "../classes/status";
import {Report} from "../classes/report";
import {Image} from "../classes/image";
import {Observable} from "rxjs/Observable";
import {HttpClient} from "@angular/common/http";


@Injectable ()
export class ReportService {

	constructor(
		protected http : HttpClient
	) {}

	// define the API endpoint
	private reportUrl = "api/report/";
	private imageUrl = "api/image/";

	// Observable string source
	private dataStringSource = new Subject<string>();

	// Observable string stream
	dataString$ = this.dataStringSource.asObservable();

	// Service message commands
	insertData(data: string) {
		console.log(data);

		this.dataStringSource.next(data)
	}


	// call to the report API and delete the report in question
	deleteReport(reportId : string) : Observable<Status> {
		return(this.http.delete<Status>(this.reportUrl + reportId));
	}

	// call to the report API and create the report in question
	createReport(report : Report) : Observable<any> {
		return(this.http.post<any>(this.reportUrl, report));
	}

	// call to the report API and create the report in question
	updateReport(report : Report) : Observable<any> {
		return(this.http.put<any>(this.reportUrl + report.reportId, report));
	}

	getReport(reportId : string) : Observable<Report> {
		return(this.http.get<Report>(this.reportUrl + reportId));
	}

	// call to the report API and get a report object based on its ID
	getReportByReportId(reportId : string) : Observable<Report> {
		return(this.http.get<Report>(this.reportUrl + reportId));
	}

	// call to the API and get an array of reports based off the categoryId
	getReportByReportCategoryId(reportCategoryId : string) : Observable<Report[]> {
		return(this.http.get<Report[]>(this.reportUrl + "?reportCategoryId="+ reportCategoryId));
	}

	//call to the API and get an array of all the reports in the database
	getAllReports() : Observable<Report[]> {
		return(this.http.get<Report[]>(this.reportUrl));
	}

	getImageByImageReportId(imageReportId : string) : Observable<Image[]> {
		return(this.http.get<Image[]>(this.imageUrl + "?imageReportId="+ imageReportId));
	}

}
