<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $setting['title']; ?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="<?php echo $setting['meta_keyword']; ?>">
		<meta name="description" content="<?php echo $setting['meta_description']; ?>">
		<link rel="icon" href="<?php echo HTTP_MEDIA;?>/site-image/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_PUBLIC_THEME; ?>/font/font.css?ver=<?php echo HTTP_VERSION; ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/oz-noty-vjob/animate.css?ver=<?php echo HTTP_VERSION; ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_CDN_PLUGIN; ?>/bootstrap-3.3.6/css/bootstrap-custom.css?ver=<?php echo HTTP_VERSION; ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_ACTIVE_PUBLIC_THEME; ?>/style.css?ver=<?php echo HTTP_VERSION; ?>" />
		<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/jquery-1.10.1-min.js?ver=<?php echo HTTP_VERSION; ?>"></script>
		<script type="text/javascript" src='<?php echo HTTP_CDN_PLUGIN; ?>/jQuery/thirdparty/oz-noty-vjob/jquery.noty.packaged.min.js?ver=<?php echo HTTP_VERSION; ?>'></script>
		<script type="text/javascript" src="<?php echo HTTP_CDN_PLUGIN; ?>/bootstrap-3.3.6/js/bootstrap.js?ver=<?php echo HTTP_VERSION; ?>"></script>
	</head>
	<body>
		<script type="text/javascript">

			var SITE_NAME = <?php echo json_encode(SITE_NAME); ?>;
			var HTTP_MEDIA = <?php echo json_encode(HTTP_MEDIA); ?>;
			var HTTP_AJAX = <?php echo json_encode($HTTP_AJAX) ?>;
			var JS_SERVER = <?php echo json_encode(HTTP_SERVER); ?>;
			var JS_ROOT = <?php echo json_encode(HTTP_ROOT); ?>;

			$(document).ready(function(){
				<?php echo onReadyMessageVJOB($message, $message2, $error, $warning); ?>
			});
		</script>
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="https://www.ifesworld.org/en">Home</a></li>
						<li class="dropdown">
							<a href="https://www.ifesworld.org/en/about" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">About <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="https://www.ifesworld.org/en/our-people">Our people</a></li>
								<li><a href="https://www.ifesworld.org/en/our-vision">Our vision</a></li>
								<li><a href="https://www.ifesworld.org/en/our-beliefs">Our beliefs</a></li>
								<li><a href="https://www.ifesworld.org/en/our-history">Our history</a></li>
								<li><a href="https://www.ifesworld.org/en/our-work">Our work</a></li>
								<li><a href="https://www.ifesworld.org/en/our-governance">Our governance</a></li>
							</ul>
						</li>
						<li><a href="#cont">Regions</a></li>
						<li class="active"><a href="#co">Get involved</a></li>
						<li class="dropdown">
							<a href="https://www.ifesworld.org/en/events" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">Events <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="https://www.ifesworld.org/en/world-assembly">World Assembly</a></li>
								<li><a href="https://www.ifesworld.org/en/world-student-day">World Student Day</a></li>
								<li><a href="https://www.ifesworld.org/en/regional-events">Publications</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="https://www.ifesworld.org/en/en/resources" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">Resources <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="https://www.ifesworld.org/en/books">Books</a></li>
								<li><a href="https://www.ifesworld.org/en/brand-resources">Brand Resources</a></li>
								<li><a href="https://www.ifesworld.org/en/publications">Publications</a></li>
								<li><a href="https://www.ifesworld.org/en/reports">Reports</a></li>
								<li><a href="https://www.ifesworld.org/en/word-and-world">Word &amp; World</a></li>
							</ul>
						</li>
						<li><a href="https://www.ifesworld.org/en/form/contact-us">Contact</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../navbar/">Log in</a></li>
						<li><a href="../navbar/">Language</a></li>
						<li><a href="../navbar/">Search</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<?php require $setting['center_dir']; ?>
		<footer class="footer">
			<div class="container">
				<p class="title">International Fellowship of Evangelical Students</p>
			</div>
			<div class="container">
				<p class="content">International Fellowship of Evangelical Students/USA, Inc. (IFES/USA) is a &sect;501(c)(3)<br>organization, gifts to which are deductible as charitable contributions for Federal income tax purposes.</p>
			</div>
		</footer>
	</body>
</html>