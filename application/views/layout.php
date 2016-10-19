<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if (isset($title)): echo $this->escape($title) . ' - '; endif; ?>Autodesk Forge PHP Basic Sample</title>
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- App -->
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
    <script src="/js/model.js"></script>
    <script src="/js/view.js"></script>
    <script src="/js/controller.js"></script>
    
    <!-- Viewer -->
    <link rel="stylesheet" href="https://developer.api.autodesk.com/viewingservice/v1/viewers/style.min.css?v=2.10.*" type="text/css">
    <link rel="stylesheet" href="https://developer.api.autodesk.com/viewingservice/v1/viewers/A360.css?v=2.10.*" type="text/css">
    <script src="https://developer.api.autodesk.com/viewingservice/v1/viewers/three.min.js?v=2.10.*"></script>
    <script src="https://developer.api.autodesk.com/viewingservice/v1/viewers/viewer3D.min.js?v=2.10.*"></script>
    <script src="https://developer.api.autodesk.com/viewingservice/v1/viewers/Autodesk360App.js?v=2.10.*"></script>
    
</head>
<body>
<div id="header">
    <h1><a href="<?php echo $base_url; ?>/">Autodesk Forge PHP Basic Sample</a></h1>
</div>

<div id="main">
    <?php echo $_content; ?>
</div>
</body>
</html>
