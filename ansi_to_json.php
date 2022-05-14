<?php
$INFO=<<<COPY
*
* Convert ANSI data to JSON
*
* Requires:
*  lib-php/ansi_lib.php
*  lib-php/ansi_SAUCE_lib.php
*  lib-php/cp437_utf8_lib.php
*
* (c) Ram Narula <ram@pluslab.com> github @rambkk
COPY;

// DEFAULT OPTIONS
$ANSIbg=40;
$ANSIfg=37;
$width='auto';
$use5m='auto';
$use7m=true;
$cp437_to_utf8=true;
$broken_pipe=true;
$preserve_crlftab=true;
$preserve_escape=true;
$JSON_pretty_print=true;

require_once("lib-php/ansi_lib.php");

if (defined('STDIN')) {
	$longopts  = array(
		//"required:",     // Required value
		"bg::",    // Optional value
		"fg::",    // Optional value
		"width::",    // Optional value
		"cp437_to_utf8::",    // Optional value
		"use5m::",    // Option value
		"use7m::",    // Option value
		"broken_pipe::",    // Option value
		"preserve_crlftab::",
		"preserve_escape::",
		"pretty_print::",
	);
        $opt = getopt("f:",$longopts,$rest_index);
	if(array_key_exists('width',$opt)) {$width=$opt['width'];}
        if(array_key_exists('bg',$opt))	{$ANSIbg=$opt['bg'];}
        if(array_key_exists('fg',$opt))	{$ANSIfg=$opt['fg'];}
	if(array_key_exists('cp437_to_utf8',$opt)){$cp437_to_utf8=strcmp($opt['cp437_to_utf8'],'false')==0?false:true;}
	if(array_key_exists('broken_pipe',$opt)){ $broken_pipe=strcmp($opt['broken_pipe'],'false')==0?false:true; }
	if(array_key_exists('preserve_crlftab',$opt)){ $preserve_crlftab=strcmp($opt['preserve_crlftab'],'false')==0?false:true; }
	if(array_key_exists('preserve_escape',$opt)){ $preserve_escape=strcmp($opt['preserve_escape'],'false')==0?false:true; }
	if(array_key_exists('pretty_print',$opt)){ $JSON_pretty_print=strcmp($opt['pretty_print'],'false')==0?false:true; }
	if(array_key_exists('use5m',$opt)){$use5m=$opt['use5m'];}
	if(array_key_exists('use7m',$opt)){$use7m=strcmp($opt['use7m'],'false')==0?false:true;}

        if(array_key_exists('f',$opt)) {
                $fname=$opt['f'];
        } else {
	echo "\n";
        echo "Require: -f [filename.ans |OR| '-' for STDIN]  \n";
	echo "\n";
        echo " Option:\n";
	echo "   --pretty_print='true |OR| false' (default: true)\n";
	echo "          output JSON data in pretty format\n";
	echo "   --cps437_to_utf8='true |OR| false' (default: true)\n";
	echo "          transcode code page 437 to Unicode utf8 format\n";
	echo "   --broken_pipe='true |OR| false' (default: true)\n";
	echo "          transcode '|' to classic broken pipe\n";
	echo "   --preserve_crlftab='true |OR| false' (default: true)\n";
	echo "          keep carriage return,linefeed,tab - no transcode\n";
	echo "   --preserve_escape='true |OR| false' (default: true)\n";
	echo "          keep escape character - no transcode\n";
        echo "   --use5m=='auto |OR| true |OR| false' default: auto)\n";
        echo "          use bright 5m background (iCE colors)\n";
        echo "   --use7m=='true |OR| false' default: true)\n";
        echo "          do color inverse (inline 7m is experimental) \n";
	echo "   --width='auto |OR| none |OR| width in number'\n";
	echo "          (default: auto)\n";
	echo "          add CRLF at certain width\n";
	echo "          'auto' - use SAUCE if available, or none\n";
	echo "          'a number' - use this width\n";
	echo "          'none'     - do not add any CRLF\n";
	echo "          eg. --width='auto', --width='108', --width='21', etc.\n";
	echo "   --bg='color code' (default: '40' - black)\n";
	echo "          use this as default ANSI background\n";
	echo "          eg. 40, 41, 5;42, etc.\n"; 
	echo "   --fg='color code' (default: '37' - grey)\n";
	echo "          use this as default ANSI foreground\n";
	echo "          eg. 30, 31, 1;32, etc.\n"; 
	echo "\n";
	echo "(c) Ram Narula <ram@pluslab.com> github @rambkk\n";
	echo "\n";
	exit;
        }
}

$input=$fname=='-'?stream_get_contents(STDIN):file_get_contents($fname);


////BEGIN////

$option=[
	'width'	=>$width,
	'ANSIfg' => $ANSIfg,
	'ANSIbg' => $ANSIbg,
	'use5m' => $use5m,
	'use7m' => $use7m,
        'cp437_to_utf8' => $cp437_to_utf8,
        'broken_pipe' 	=> $broken_pipe,
	'preserve_crlftab'	=> $preserve_crlftab,
	'preserve_ecape'	=> $preserve_escape,
	'JSON_pretty_print'	=> $JSON_pretty_print,
];

echo ansi_TO_JSON($input,$option);

?>
