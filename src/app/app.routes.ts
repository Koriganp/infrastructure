//import needed @angularDependencies
import {RouterModule, Routes} from "@angular/router";

//import all needed Interceptors
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
import {CategoryComponent} from "./components/category.component";


// import services
import {AuthService} from "./services/auth.service";
import {CookieService} from "ng2-cookies";
import {JwtHelperService} from "@auth0/angular-jwt";
import {UserService} from "./services/user.service";
import {ProfileService} from "./services/profile.service";
import {ReportService} from "./services/report.service";
import {CommentService} from "./services/comment.service";
import {SessionService} from "./services/session.service";
import {SignInService} from "./services/sign.in.service";
import {SignUpService} from "./services/sign.up.service";
import {CategoryComponent} from "./components/category.component";
import {CategoryService} from "./services/category.service";


//an array of the components that will be passed off to the module
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
	CategoryComponent,
];

//an array of routes that will be passed of to the module
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
	{path: "foot", component: FootComponent},
	{path: "category", component: CategoryComponent},
];

// an array of services that will be passed off to the module
const services : any[] = [
	AuthService,
	CookieService,
	JwtHelperService,
	ProfileService,
	CategoryService,
	ReportService,
	CommentService,
	SessionService,
	SignInService,
	SignUpService];

// an array of misc providers
export const Providers: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},
	UserService
];

export const appRoutingProviders: any[] = [providers, services ];

export const routing = RouterModule.forRoot(routes);