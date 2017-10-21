<?php
if(isset($_POST['check'])){
	file_put_contents("requires", "");
	header("Refresh:0");
}