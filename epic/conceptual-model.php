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
			<h2>Profile</h2>
			<div class="centerList">
				<ul>
					<li>profileId(primary key)</li>
					<li>profileActivationToken</li>
					<li>profileEmail</li>
					<li>profileHash</li>
					<li>profileSalt</li>
					<li>profileUsername</li>
				</ul>
			</div>
			<h2>Category</h2>
			<div class="centerList">
				<ul>
					<li>categoryId (primary key)</li>
					<li>categoryName</li>
				</ul>
			</div>
			<h2>Report</h2>
			<div class="centerList">
				<ul>
					<li>reportId(primary key)</li>
					<li>reportCategoryId (foreign key)</li>
					<li>reportContent</li>
					<li>reportDateTime</li>
					<li>reportIpAddress</li>
					<li>reportLat</li>
					<li>reportLong</li>
					<li>reportStatus</li>
					<li>reportUrgency</li>
					<li>reportUserAgent</li>
				</ul>
			</div>
			<h2>Image</h2>
			<div class="centerList">
				<ul>
					<li>imageId(primary key)</li>
					<li>imageReportId(foreign key)</li>
					<li>imageCloudinaryId</li>
					<li>imageLat</li>
					<li>imageLong</li>
				</ul>
			</div>
			<h2>Comment</h2>
				<div class="centerList">
					<ul>
						<li>commentId(primary key)</li>
						<li>commentProfileId(foreign key)</li>
						<li>commentReportId(foreign key)</li>
						<li>commentContent</li>
						<li>commentDateTime</li>
					</ul>
				</div>
			<img class="infrastructure" src="images/infrastructure-erd.svg" alt="Infrastructure ERD"/>
		</main>
	</body>
</html>

