<?php 

$feed->updateFeedFromFile();

?>
<div class="wrap">
    <h2>WP Feed Update from file</h2>
	<form action="" method="post" enctype="multipart/form-data">
	    Select image to upload:
	    <input type="file" name="updatefile" id="updatefile">
	    <input type="submit" value="Upload file" name="submit">
	    <br/>
	    <em>Fisierul trebuie sa fie .csv</em><br>
	</form>
</div>