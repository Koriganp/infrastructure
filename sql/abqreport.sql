DROP TABLE IF EXISTS `profile`;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS `comment`;

CREATE TABLE profile (
	profileId BINARY(16) NOT NULL ,
	profileActivationToken CHAR(32) ,
	profileUserName VARCHAR(32) NOT NULL ,
	profileEmail VARCHAR(128) NOT NULL ,
	profileHash CHAR(128) NOT NULL ,
	profileSalt CHAR(64) NOT NULL ,
	UNIQUE(profileEmail) ,
	UNIQUE(profileUserName) ,
	PRIMARY KEY(profileId)
);

CREATE TABLE category (
	categoryId BINARY(16) NOT NULL ,
	categoryName VARCHAR(32) NOT NULL,
	PRIMARY KEY (categoryId)
);

CREATE TABLE report (
	reportId BINARY(16) NOT NULL ,
	reportCategoryId BINARY(16) NOT NULL ,
	reportContent VARCHAR(3000) NOT NULL ,
	reportDateTime DATETIME(6) NOT NULL ,
	reportIpAddress VARBINARY(16) NOT NULL ,
	reportLat DECIMAL(12) NOT NULL ,
	reportLong DECIMAL(12) NOT NULL ,
	reportStatus VARCHAR(15) NOT NULL ,
	reportUrgency TINYINT(5) ,
	reportUserAgent TINYINT UNSIGNED NOT NULL ,
	INDEX (reportCategoryId) ,
	FOREIGN KEY (reportCategoryId) REFERENCES category(categoryId) ,
	PRIMARY KEY (reportId)
);

CREATE TABLE image (
	imageId BINARY(16) NOT NULL ,
	imageReportId BINARY(16) NOT NULL ,
	imageCloudinary VARCHAR(64) NOT NULL ,
	imageLat DECIMAL(12) NOT NULL ,
	imageLong DECIMAL(12) NOT NULL ,
	INDEX (imageReportId) ,
	FOREIGN KEY (imageReportId) REFERENCES report(reportId) ,
	PRIMARY KEY (imageId)
);

CREATE TABLE `comment` (
	commentId BINARY(16) NOT NULL ,
	commentProfileId BINARY(16) NOT NULL ,
	commentReportId BINARY(16) NOT NULL ,
	commentContent VARCHAR(500) ,
	commentDateTime DATETIME(6) NOT NULL ,
	INDEX (commentProfileId) ,
	INDEX (commentReportId) ,
	FOREIGN KEY (commentProfileId) REFERENCES profile(profileId) ,
	FOREIGN KEY (commentReportId) REFERENCES report(reportId) ,
	PRIMARY KEY (commentId)
);
