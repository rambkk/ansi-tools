<?php
$INFO=<<<COPY
*
* Transcode cp437 data to Unicode UTF-8
*
* Requires:
*  lib-php/cp437_utf8_lib.php
*
* (c) Ram Narula <ram@pluslab.com> github @rambkk
COPY;

// DEFAULT OPTIONS
$preserve_escape=true;
$preserve_crlftab=true;
$suppress_newline=false;
$broken_pipe=true;

require_once("lib-php/cp437_utf8_lib.php");


if (defined('STDIN')) {
        $longopts  = array(
                //"required value ::"
                "broken_pipe::",
                "preserve_crlftab::",
                "preserve_escape::",
        );
        $opt = getopt("nf:",$longopts,$rest_index);
        if(array_key_exists('broken_pipe',$opt)){ $broken_pipe=strcmp($opt['broken_pipe'],'false')==0?false:true; }
        if(array_key_exists('preserve_crlftab',$opt)){ $preserve_crlftab=strcmp($opt['preserve_crlftab'],'false')==0?false:true; }
        if(array_key_exists('preserve_escape',$opt)){ $preserve_escape=strcmp($opt['preserve_crlftab'],'false')==0?false:true; }
	if(array_key_exists('n',$opt)) {$suppress_newline=true;}
	if(array_key_exists('f',$opt)) {
		$fname=$opt['f'];
	} else {
	echo "\n";
	echo "Require: -f [filename.ans |OR| - for STDIN]  \n";
	echo " Option:\n";
	echo "	 -n     do not output the trailing newline\n";
	echo "   --broken_pipe=\"true |OR| false\"\n";
        echo "          (default: true)\n";
        echo "          transcode '|' to classic broken pipe\n";
        echo "   --preserve_crlftab=\"true |OR| false\"\n";
        echo "          (default: true)\n";
        echo "   --preserve_escape=\"true |OR| false\"\n";
        echo "          (default: true)\n";
        echo "          keep escape sequence - no transcode\n";
	echo "\n";
	echo "(c) Ram Narula <ram@pluslab.com> github @rambkk\n";
	echo "\n";
	exit;
	}

}
$input=$fname=='-'?stream_get_contents(STDIN):file_get_contents($fname);

$option=[
	'preserve_escape'	=> $preserve_escape,
	'preserve_crlftab'	=> $preserve_crlftab,
	'broken_pipe'		=> $broken_pipe,
];

$line=cp437_TO_UTF8($input,$option);
print $line;
if(!$suppress_newline) { print "\n"; }


?>
