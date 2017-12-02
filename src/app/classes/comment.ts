export class Comment {
    constructor(
        public commentId: number,
        public commentProfileId: number,
        public commentReportId: number,
        public commentContent: string,
        public commentDateTime: string,
    ) {}
}