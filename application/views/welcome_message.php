<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<body>

<form action="zt/upload" method="post"
	  enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file" />
	<br />
	<input type="submit" name="submit" value="Submit" />
</form>

</body>
</html>