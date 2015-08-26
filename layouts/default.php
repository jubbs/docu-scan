<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$pageTitle?></title>

    <!-- Styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="assets/css/default.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="row">
	    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
		  <div class="header row">
		    <h2 class="col-xs-6"><?= $pageTitle ?></h2>
			<div class="logo col-xs-6">
			  <a href="http://www.fuelrefunds.co.nz/">
			    <img src="assets/img/catalyst_logo_white.gif" alt="Catalyst">
			  </a>
			</div>
		  </div>
		  <div class="page row">
		    <?php include $pageContent ?>
          </div>
		  <div class="footer row">
			<span><a href="http://www.fuelrefunds.co.nz/privacy-policy/" target="_blank">Privacy Policy</a> | Catalyst &copy;2015</span>
		  </div>
	    </div>
	  </div>
    </div>
    
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <?php if (isset($inlineScript)) {echo($inlineScript);}?>
    <script src="assets/js/index.js"></script>
  </body>
</html>