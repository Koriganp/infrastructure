export class Comment {
    constructor(
        public commentId: string,
        public commentProfileId: string,
        public commentReportId: string,
        public commentContent: string,
        public commentDateTime: string,
    ) {}
}