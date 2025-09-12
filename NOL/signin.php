<?php
// Include the ClassAutoLoad Method
require_once 'ClassAutoLoad.php';
$layout->header($conf);
print $hello->today();
$form->login();
$layout->footer($conf);
?>