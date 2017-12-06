export class Profile {
	constructor(
		public profileId: string,
		public profileActivationToken: string,
		public profileUsername: string,
		public profileEmail: string,
		public profilePassword: string,
		public profilePasswordConfirm: string
	) {}
}