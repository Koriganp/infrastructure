import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

import {Status} from "../classes/status";
import {Profile} from "../classes/profile";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class ProfileService {

	constructor(protected http: HttpClient) {

	}

	//define the API endpoint
	private profileUrl = "api/profile/";

	//reach out to the profile  API and delete the profile in question
	deleteProfile(id: any): Observable<Status> {
		return (this.http.delete<Status>(this.profileUrl + id));
	}

	// call to the Profile API and edit the profile in question
	editProfile(profile: Profile): Observable<Status> {
		return (this.http.put<Status>(this.profileUrl, profile));
	}

	// call to the Profile API and get a Profile object by its id
	getProfile(id: any): Observable<Profile> {
		return (this.http.get<Profile>(this.profileUrl + id));

	}

	//call to the profile API and grab the corresponding profile by its email
	getProfileByProfileEmail(profileEmail: string): Observable<Profile[]> {
		return (this.http.get<Profile[]>(this.profileUrl + "?profileEmail=" + profileEmail));
	}

	// call to the API to grab an array of profiles based on the user input
	getProfileByProfileUsername(profileUsername: string): Observable<Profile[]> {
		return (this.http.get<Profile[]>(this.profileUrl + "?profileUsername=" + profileUsername));

	}

	// call to the API to grab an array of profiles based on the user input
	getProfileByActivationToken(profileActivationToken: string): Observable<Profile[]> {
		return (this.http.get<Profile[]>(this.profileUrl + "?profileActivationToken=" + profileActivationToken));
	}

}