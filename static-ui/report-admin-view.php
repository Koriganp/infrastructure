<!-- Report Admin View (Pop-Up) -->
<div class="card" style="width: 50em; height: 50em">

	<h1>Submitted Report</h1>

	<div>
		<h3>Category Title</h3>
	</div>

	<form>
		<div class="form-group">
			<label for="staticDateReported" class="col-form-label">Date Reported</label>
			<div>
				<input type="text" readonly class="form-control-plaintext" id="staticDateReported" value="11/28/2017">
			</div>
		</div>
	</form>
	<div class="btn-toolbar" role="toolbar">
		<div class="btn-group">
			<button class="btn btn-secondary btn-sm" type="button">
				Select Status
			</button>
			<button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="sr-only">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu">
				...
			</div>
		</div>
		<div class="btn-group">
			<button class="btn btn-secondary btn-sm" type="button">
				Select Urgency
			</button>
			<button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="sr-only">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu">
				...
			</div>
		</div>
	</div>

	<div>
		<h3>Report Content</h3>
		<form>
			<div class="form-group">
				<label for="content">Example textarea</label>
				<textarea class="form-control" id="content" rows="10"></textarea>
			</div>
		</form>
	</div>

	<div>
		<h3>Images</h3>
		<img src="..." alt="..." class="img-thumbnail">
		<img src="..." alt="..." class="img-thumbnail">
	</div>

	<div>
		<h3>Comment</h3>
		<form>
			<div class="form-group">
				<label for="comment">Example textarea</label>
				<textarea class="form-control" id="comment" rows="10"></textarea>
			</div>
		</form>
	</div>

</div>