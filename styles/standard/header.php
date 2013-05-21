<!DOCTYPE html>
<html>
	<head>
		<title><?=template::getVar('TITLE_TAG'); ?><?=template::getVar('PAGE_TITLE'); ?></title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="styles/standard/style.css" />
		<link rel="stylesheet" href="js/dropdown/jquery.dropdown.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/dropdown/jquery.dropdown.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		
		<script type="text/javascript">
			token = '<?=$_SESSION['forum_token']; ?>';
		</script>
		
		
		<!--[if gte IE 9]>
			<style type="text/css">
				.gradient {
				filter: none;
				}
			</style>
		<![endif]-->

		<?php if ($user->row) { ?>
			<style>
				#user_wrap ul li.user a {
					background-repeat: no-repeat;
					background-size: 25px;
					background-position: 10px 7.5px;
					padding-left: 45px;
					background-image: url("./images/avatar/<?php if ($user->row['user_avatar']) { echo $user->row['user_avatar']; } else { ?><?=$config['default_avatar']; ?><?php } ?>");
				}
			</style>
		<?php } ?>
	</head>
	<body>
		<div id="user_wrap">
			<ul class="user">
				<?php if ($user->row) { ?>
					<li class="user">
						<a href="./user.php?id=<?=$user->row['user_id']; ?>" <?php if (template::getPage() == 'user' && $_GET['id'] == $user->row['user_id']): ?>class="active"<?php endif; ?>><?=template::getLanguage($config['selected_language'],'hello');?>, <b><?=$user->row['username']; ?></b>.</a>
					</li>

					<li class="settings">
						<a href="./profile.php" <?php if (template::getPage() == 'profile'): ?>class="active"<?php endif; ?>> <?=template::getLanguage($config['selected_language'],'config');?></a>
					</li>

					<li class="mail">
						<a href="./mail.php" <?php if (template::getPage() == 'mail'): ?>class="active"<?php endif; ?>>(<?=$user->row['user_mails']; ?>)</a>
					</li>

					<li class="logout">
						<a href="./login.php?logout=1"><?=template::getLanguage($config['selected_language'],'logout');?></a>
					</li>
				<?php } else { ?>
					<li class="register">
						<a href="register.php"><?=template::getLanguage($config['selected_language'],'register');?></a>
					</li>

					<li class="login">
						<a href="login.php"><?=template::getLanguage($config['selected_language'],'login');?></a>
					</li>
				<?php } ?>

				<div class="clear"></div>
			</ul>
		</div>

		<div id="header_wrap">
			<header>
				<div class="logo">
					<a href="./" title="test1234"><img src="http://creatr.cc/creatr/logo/Web%20Power%20Board.png?1368529574" alt="Logo" /></a>
				</div>

				<?php template::displayArea('header'); ?>
			</header>
		</div>

		<div id="nav_wrap">
			<nav>
				<ul class="main">
					<?=standard::menu(); ?>
					<?php template::displayArea('menuPlugin'); ?>
					<li><a class="last"></a></li>
				</ul>
				<form action="search.php" method="GET">
				<input type="text" name="query" class="searchinput">
				</form>
				<div class="clear"></div>
			</nav>
		</div>

		<div class="clear"></div>

		<section id="content">