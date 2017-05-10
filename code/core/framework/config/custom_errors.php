<div style="height: 400px;">
<?php
	if (isset($msg404) && $msg404 != "") {
		echo '<div align="center" style="padding: 15px 0 0 0; background-color:white; color:red; ">' . $msg404 . '</div>';
	} else {
		echo '<div align="center" style="padding: 15px 0 0 0; background-color:white; color:red; ">Error Detected!</div>';
	}
?>
</div>