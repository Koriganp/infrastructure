<!DOCTYPE html>
<html lang="en-US">
<head>
	<title>Conceptual Model</title>
	<meta charset="UTF-8">
	<link href="styles/george.css" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"/>
</head>
<body>
	<main>
	<h2>Conceptual Model</h2>
	<h3>Profile</h3>
		<ul>
			<li>profileId(primary key)</li>
			<li>profileEmail</li>
			<li>profileHash(password)</li>
			<li>profileSalt(password)</li>
			<li>profilePhone</li>
			<li>profileAdmin tinyint()unsigned</li>
		</ul>
	<h2>Report</h2>
		<ul>
			<li>reportId(primary key)</li>
			<li>reportId(foreign key)</li>
			<li>reportContent</li>
			<li>reportDate</li>
		</ul>
	<h2>Comment</h2>
		<ul>
			<li>commentId(primary key</li>
			<li>commentProfileId(foreign key)</li>
			<li>commentReportId(foreign key)</li>
			<li>commentContent</li>
			<li>commentDate</li>
		</ul>
	<h3>ERD</h3>
	</main>
	</body>
</html>

