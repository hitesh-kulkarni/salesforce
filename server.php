<?php

require_once "class.feed.php";

$tf = new Twitter_feed();

echo json_encode($tf->get_feed());

?>