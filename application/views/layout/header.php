<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo isset($title) ? $title : ''; ?></title>
		<link rel="stylesheet" href="/media/css/lib/pure-min.css">
		<link rel="stylesheet" href="/media/css/lib/normalize.css">
		<link rel="stylesheet" href="/media/css/site.css">
		<script src="/media/js/lib/jquery.min.js" type="text/javascript"></script>
		<script src="/media/js/lib/remote-list.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="body">
			<div class="header">
				<div class="pure-menu pure-menu-horizontal pure-menu-fixed">
					<a class="pure-menu-heading" href="/">Moneyzaurus</a>

					<ul class="pure-menu-list">
						<li class="pure-menu-item"><a href="/transaction" class="pure-menu-link">New</a></li>
						<li class="pure-menu-item"><a href="/data" class="pure-menu-link">Data</a></li>
						<li class="pure-menu-item"><a href="/chart" class="pure-menu-link">Chart</a></li>
						<li class="pure-menu-item"><a href="/profile" class="pure-menu-link">Profile</a></li>
					</ul>
				</div>
			</div>
