<?php

require 'core/ClassManager.php';

$classManager = new Autodesk\Forge\PHP\Sample\Core\ClassManager();
$classManager->registerClassDir(APP .'core');
$classManager->registerClass();
