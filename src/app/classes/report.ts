export class Report {
	constructor(
		public reportId: number,
		public reportCategoryId: number,
		public reportContent: string,
		public reportDateTime: string,
		public reportIpAddress: number,
		public reportLat: number,
		public reportLong: number,
		public reportStatus: string,
		public reportUrgency: number,
		public reportUserAgency: number
	) {}
}