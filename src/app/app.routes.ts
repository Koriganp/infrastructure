import {RouterModule, Routes} from "@angular/router";
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./services/deep.dive.intercepters";

// import all components
import {SplashComponent} from "./components/splash.component";
import {AdminDashboardComponent} from "./components/admin-dashboard.component";
import {NavbarComponent} from "./components/navbar.component";
import {FootComponent} from "./components/foot.component";
import {ReportAdminViewComponent} from "./components/report-admin-view.component";
import {ReportPublicViewComponent} from "./components/report-public-view.component";
import {ReportsMadeComponent} from "./components/reports-made.component";
import {ReportSubmitComponent} from "./components/report-submit.component";
import {SignInComponent} from "./components/sign-in.component";
import {SignUpComponent} from "./components/sign-up.component";
import {SignOutComponent} from "./components/sign-out.component";
import {ReportListedByCategoryComponent} from "./components/report-listed-by-category.component";
import {ReportCategoryDropdownComponent} from "./components/report-category-dropdown.component";
import {HomeViewComponent} from "./components/home-view.component";


// import services
import {UserService} from "./services/user.service";


export const allAppComponents = [
	SplashComponent,
	AdminDashboardComponent,
	NavbarComponent,
	ReportAdminViewComponent,
	ReportPublicViewComponent,
	ReportsMadeComponent,
	ReportSubmitComponent,
	HomeViewComponent,
	FootComponent,
	SignInComponent,
	SignUpComponent,
	SignOutComponent,
	ReportListedByCategoryComponent,
	ReportCategoryDropdownComponent,
];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "admin-dashboard", component: AdminDashboardComponent},
	{path: "report-admin-view", component: ReportAdminViewComponent},
	{path: "report-public-view", component: ReportPublicViewComponent},
	{path: "reports-made", component: ReportsMadeComponent},
	{path: "sign-in", component: SignInComponent},
	{path: "sign-up", component: SignUpComponent},
	{path: "sign-out", component: SignOutComponent},
	{path: "home-view", component: HomeViewComponent},
	{path: "report-listed-by-category", component: ReportListedByCategoryComponent},
	{path: "report-category-dropdown", component: ReportCategoryDropdownComponent},
	{path: "report-submit", component: ReportSubmitComponent},
	{path: "foot", component: FootComponent}
];

//an array of the components that will be passed off to the module
export const allAppComponents = [
	AdminDashboardComponent,
	NavbarComponent,
	ReportAdminViewComponent,
	ReportPublicViewComponent,
	ReportsMadeComponent,
	ReportSubmitComponent,
	HomeViewComponent,
	FootComponent,
	SignInComponent,
	SignUpComponent,
	SignOutComponent,
	ReportListedByCategoryComponent,
	ReportCategoryDropdownComponent];

//an array of routes that will be passed of to the module
export const routes: Routes = [
	{path: "", component: HomeViewComponent}
];

export const appRoutingProviders: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},
	UserService
];

export const routing = RouterModule.forRoot(routes);