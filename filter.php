<div id = "filterbar">
	<div id = "conditions">
	</div>
	<button class = "addcondition btn btn-primary">add condition</button>
	<figure class = "highlight">
		<pre><code id="searchquery">
		</code></pre>
	</figure>
	<form method="post" id = "filterform">
		<input type="hidden" name="searchquery" id="searchqueryinput">
		<input type="button" value="clear" name="clear" id="clearsearchform" class = "btn btn-primary">
		<input type="submit" value="search" class = "btn btn-primary">
	</form>

</div>
<div id = "filterbuttonwrap">
	<div id = "filterbuttonline"></div>
	<h2><div id = "filterbutton" class = "arrowdown">filter</div></h2>
</div>