import {RouterModule, Routes} from "@angular/router";
import {APP_BASE_HREF} from "@angular/common";

// import all components
import {SplashComponent} from "./components/splash.component";
import {AdminDashboardComponent} from "./components/admin-dashboard.component";

// import services
import {UserService} from "./services/user.service";

export const allAppComponents = [
	SplashComponent,
	AdminDashboardComponent
];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "admin-dashboard", component: AdminDashboardComponent}
];

export const appRoutingProviders: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	UserService
];

export const routing = RouterModule.forRoot(routes);