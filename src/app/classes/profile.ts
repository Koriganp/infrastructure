export class Profile {
	constructor(
		public profileId: string,
		public profileActivationToken: string,
		public profileUserName: string,
		public profileEmail: string,
		public profilePassword: string,
		public profilePasswordConfirm: string
	) {}
}