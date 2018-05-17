<?php
$app = new Phar("bin/docblock.phar", 0, "docblock.phar");
$app->addFile('src/docblock.php');
$app->addFile('src/DocBlockGenerator.class.php');
$defaultStub = $app->createDefaultStub("src/docblock.php");
$stub = "#!/usr/bin/env php \n".$defaultStub;
$app->setStub($stub);
$app->stopBuffering();
?>

