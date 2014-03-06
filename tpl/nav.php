<?php
use \Tango\Core\Config;
?>
<body>

	<div class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<?php
			if (0 && $_SERVER['SCRIPT_NAME'] === '/index.php') {
				?>
				<span class="navbar-brand white"><?= Config::get('html')['title'] ?></span>
				<?php
			} else {
				?>
				<a class="navbar-brand" href="/"><?= Config::get('html')['title'] ?></a>
				<?php
			}
			?>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
	</div>
</div>

<div class="container">

<!--
<header>
	<div>
	<div class="passport">
	</div>
	</div>
</header>
-->
