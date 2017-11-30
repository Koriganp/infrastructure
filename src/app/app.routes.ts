import {RouterModule, Routes} from "@angular/router";
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./services/deep.dive.intercepters";

// import all components
import {SplashComponent} from "./components/splash.component";
import {AdminDashboardComponent} from "./components/admin-dashboard.component";
import {NavbarComponent} from "./components/navbar.component";

// import services
import {UserService} from "./services/user.service";


export const allAppComponents = [
	SplashComponent,
	AdminDashboardComponent,
	NavbarComponent
];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "admin-dashboard", component: AdminDashboardComponent}
];

export const appRoutingProviders: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},
	UserService
];

export const routing = RouterModule.forRoot(routes);