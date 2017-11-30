<!-- Public/Home Page -->
<!DOCTYPE html>
<html lang="en-US">
	<!-- inject head-utils -->
	<?php require_once("head-utils.php");?>
	<body>
		<!-- inject navbar -->
		<?php require_once("header.php");?>

		<!-- begin Home Page layout -->
		<main>
			<div class="container-fluid">
				<?php require_once("report-public-view.php");?>
			</div>
			<div class="container-fluid">
				<?php require_once("report-submit.php");?>
			</div>
			<div class="container-fluid">
				<?php require_once("reports-made.php");?>
			</div>
		</main>
		<!-- inject footer -->
		<?php require_once("footer.php");?>
	</body>

</html>