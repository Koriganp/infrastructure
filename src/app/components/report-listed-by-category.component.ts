import {Component, OnInit} from "@angular/core";

import {AuthService} from "../services/auth.service";
import {ReportService} from "../services/report.service";
import {CategoryService} from "../services/category.service";

import {Report} from "../classes/report";
import {Category} from "../classes/category";
import {Status} from "../classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
    templateUrl: "./templates/report-listed-by-category.html",
    selector: "report-listed-by-category"
})

export class ReportListedByCategoryComponent implements onInit {

    createCommentForm: FormGroup;

    status : Status = new Status(null, null, null);

    category : Category = new Category(null, null);

    report : Report = new Report(null, null, null, null, null, null, null);

    status: Status = null;

    comments: Comments [] = [];

    constructor(private authService : AuthService, private formBuilder : FormBuilder, private reportService : ReportService, private categoryService : CategoryService);

    // life cycling before george's eyes
    ngOnInit() : void {
        this.listComments();

        this.createCommentForm = this.formBuilder.group({
            commentContent: ["", [Validators.maxLength(500), Validators.minLength(1)]]
        });
    }

}