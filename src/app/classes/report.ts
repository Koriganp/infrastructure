export class Report {
	constructor(
		public reportId: string,
		public reportCategoryId: string,
		public reportContent: string,
		public reportDateTime: string,
		public reportStreetAddress: string,
		public reportCity: string,
		public reportState: string,
		public reportZipCode: string,
		public reportStatus: string,
		public reportUrgency: number
	) {}
}