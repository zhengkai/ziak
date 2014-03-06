</div>

<footer>
<div class="container">
	<hr />
	<div class="row">
		<div class="col-lg-12">
		<p>Base on <a href="https://github.com/zhengkai/tango">Tango Framework</a>. Style <a href="http://bootswatch.com/flatly/">Flatly</a> from <a href="http://thomaspark.me">Thomas Park</a> and <a href="http://getbootstrap.com">Bootstrap</a>. Icons from <a href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a>. Web fonts from <a href="http://www.google.com/webfonts">Google</a></p>
		<p class="debug">Time: <?=sprintf('%.2f', (microtime(TRUE) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000)?>ms , Memory Peak: <?=number_format(memory_get_peak_usage())?></p>
		</div>
	</div>
</div>
</footer>

</body>
</html>
