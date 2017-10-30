<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<link href="styles/persona.css" rel="stylesheet" type="text/css"/>
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"/>
		<title>Conceptual Model</title>
	</head>
	<body>
		<header>
			<nav class="topnav">
				<a href="index.php">Home</a>
				<a href="george.php">Persona 1</a>
				<a href="venus.php">Persona 2</a>
				<a href="use-cases.php">Use Cases</a>
				<a href="conceptual-model.php">Conceptual Model</a>
			</nav>
		</header>
		<main>
		<h1>Conceptual Model</h1>
			<h2>Purpose, Audience and Goal</h2>
				<p><strong>Purpose: </strong>This project aims to serve as a medium where Albuquerque residents can report infrastructure incidents to city officials and utility workers so that they can resolve them.</p>
				<p><strong>Audience: </strong>The audience of this project will be utility workers, city officials and Albuquerque residents.</p>
				<p><strong>Goal: </strong>This project will create a platform for Albuquerque residents to submit reports of incidents around Albuquerque to city officials and utility workers.</strong></p>
			<h2>Profile</h2>
			<div class="centerList">
				<ul>
					<li>profileId(primary key)</li>
					<li>profileEmail</li>
					<li>profileHash</li>
					<li>profileSalt</li>
					<li>profilePhone</li>
					<li>profileAdmin</li>
				</ul>
			</div>
			<h2>Report</h2>
			<div class="centerList">
				<ul>
					<li>reportId(primary key)</li>
					<li>reportProfileId(foreign key)</li>
					<li>reportContent</li>
					<li>reportDate</li>
					<li>reportStatus</li>
					<li>reportUrgency</li>
					<li>reportLocation</li>
				</ul>
			</div>
			<h2>Comments</h2>
				<div class="centerList">
				<ul>
					<li>commentsId(primary key)</li>
					<li>commentsProfileId(foreign key)</li>
					<li>commentsReportId(foreign key)</li>
					<li>commentsContent</li>
					<li>commentsDate</li>
				</ul>
			</div>
		</main>
	</body>
</html>

