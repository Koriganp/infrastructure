export class Report {
	constructor(
		public reportId: string,
		public reportCategoryId: string,
		public reportContent: string,
		public reportDateTime: string,
		public reportAddress: string,
		public reportStatus: string,
		public reportUrgency: number
	) {}
}