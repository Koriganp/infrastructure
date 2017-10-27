<!DOCTYPE html>
<html lang="en-US">
<head>
	<title>Conceptual Model</title>
	<meta charset="UTF-8">
	<link href="styles/persona.css" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"/>
</head>
<body>
	<header>
		<nav class="topnav">
			<a href="george.php">Persona 1</a>
			<a href="venus.php">Persona 2</a>
			<a href="use-cases.php">Use Cases</a>
			<a href="conceptual-model.php">Conceptual Model</a>
		</nav>
	</header>
	<main>
	<h2>Conceptual Model</h2>
		<h1>Purpose, Audience and Goal</h1>
		<div class="centerList">
			<p><strong>Purpose:</strong>This project aims to serve as a medium where Albuquerque residents can report infrastructure incidents
					to city officials and utility workers so that they can resolve them.</p>
			<p><strong>Audience:</strong>The audience of this project will be utility workers, city officials and Albuquerque residents.</p>
			<p><strong>Goal:</strong>This project will create a platform for Albuquerque residents to submit reports of incidents around Albuquerque to city officials and utility workers.</strong></p>
		</div>
	<h3>Profile</h3>
		<div class="centerList">
		<ul>
			<li>profileId(primary key)</li>
			<li>profileEmail</li>
			<li>profileHash(password)</li>
			<li>profileSalt(password)</li>
			<li>profilePhone</li>
			<li>profileAdmin tinyint()unsigned</li>
		</ul>
		</div>
	<h2>Report</h2>
		<div class="centerList">
		<ul>
			<li>reportId(primary key)</li>
			<li>reportId(foreign key)</li>
			<li>reportContent</li>
			<li>reportDate</li>
		</ul>
		</div>
	<h2>Comment</h2>
		<div class="centerList">
		<ul>
			<li>commentId(primary key</li>
			<li>commentProfileId(foreign key)</li>
			<li>commentReportId(foreign key)</li>
			<li>commentContent</li>
			<li>commentDate</li>
		</ul>
		</div>
	</main>
	</body>
</html>

