<?php
namespace Tango\Core;

Layout::set(FALSE);

http_response_code(500);
?>

<article class="html-error">

<div class="head">
<h1>HTTP 500 服务器内部错误</h1>
<p>服务器出现错误，暂时无法正确响应。请访问其他页面</p>
</div>

<?php
if (Tango::isDebug()) {
	echo '<pre class="message text-primary">';
	echo htmlspecialchars(TangoException::getLastError());
	echo '</pre>';
}
?>

</article>
