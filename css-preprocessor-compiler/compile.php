<?php
$cachedir = '/tmp/css-preprocessor-cache/';
$cachePrefix = '';
$cacheSuffix = '.css';

$cssCompilerCommand = '';

if(!is_dir($cachedir)) {
    mkdir($cachedir, 755, true);
}

if(preg_match("/.less$/", $_SERVER['PATH_TRANSLATED'])) {

    $cssCompilerCommand = "/usr/bin/env lessc ". $_SERVER['PATH_TRANSLATED'] . " 2>&1; echo $?";
    $cachePrefix = 'less-';

}
else if(preg_match("/.sass$/", $_SERVER['PATH_TRANSLATED'])
    || preg_match("/.scss$/", $_SERVER['PATH_TRANSLATED'])) {

    $cssCompilerCommand = "/usr/bin/env sass ". $_SERVER['PATH_TRANSLATED'] . " 2>&1; echo $?";
    $cachePrefix = 'sass-';

}
else if(preg_match("/.styl$/", $_SERVER['PATH_TRANSLATED'])) {

    $cssCompilerCommand = "/usr/bin/env stylus ". $_SERVER['PATH_TRANSLATED'] . " 2>&1; echo $?";
    $cachePrefix = 'stylus-';

}

$hash = substr(exec("/usr/bin/env sha1sum ". $_SERVER['PATH_TRANSLATED']), 0, 40);
$cache_req = $cachedir . $cachePrefix . $hash . $cacheSuffix;


function compile_css($cssCompilerCommand){
    $cmd = $cssCompilerCommand;
    $compiled = trim(`$cmd`);
    $exit_code = (int) substr($compiled,-1);
    $compiled = substr($compiled,0,-1);
    if (0 !== $exit_code) { throw new Exception($compiled); }
    return $compiled;
}

if (isset($_GET['recache'])) {
	@unlink($cache_req);
}

try {
	if (isset($_GET['nocompile'])) {
		header('Content-type: text/css;');
		passthru("cat ". $_SERVER['PATH_TRANSLATED']);
		exit(0);
	} elseif (isset($_GET['nocache'])) {
		header('Content-type: text/css;');
		$compiled = compile_css($cssCompilerCommand);
		echo $compiled;
		exit(0);
	} else {
		header('Content-type: text/css;');
		if (! is_readable($cache_req)) {
			$compiled = compile_css($cssCompilerCommand);
			file_put_contents($cache_req, $compiled);
		}
		echo file_get_contents($cache_req);
	}
} catch (Exception $e) {
	header('HTTP/1.1 500 Internal Server Error');
	header('Content-type: text/plain;');
	$msg = preg_replace('/\[[0-9]+m/', '', $e->getMessage());
	echo $msg;
	exit(1);
}
