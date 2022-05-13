<?php
$INFO=<<<COPY
*
* Convert ANSI data to HTML
* 
* Requires:
*  lib-php/ansi_lib.php
*  lib-php/ansi_SAUCE_lib.php
*  lib-php/cp437_utf8_lib.php
*
* (c) Ram Narula <ram@pluslab.com> github @rambkk
COPY;

// DEFAULT OPTIONS 
$HTMLshow_sauce=true;
$ANSIbg=40;
$ANSIfg=37;
$background='black';
$foreground='white';
$header=true;
$width='auto';
$use5m='auto';
$use7m=true;
$cp437_to_utf8=true;
$broken_pipe=true;
$preserve_crlftab=true;
$preserve_escape=true;
$HTML_show_null="\x0";
$HTML_use_nbsp=false;
$HTMLformat='<span style="color:%foreground%; background:%background%">%content%</span>';

require_once("lib-php/ansi_lib.php");

if (defined('STDIN')) {
	$longopts  = array(
		//"required value ::"
		"bg::",    
		"fg::",   
		"cp437_to_utf8::",
		"background::",
		"foreground::",
		"width::",
		"broken_pipe::",
		"use5m::",    // Option
		"use7m::",    // Option
		"header::",  // Option
		"format::",  // Option
		"preserve_crlftab::",  // Option
		"preserve_escape::",  // Option
		"show_null::",  // Option
		"nbsp::",  // Option
		"show_sauce::",  // Option
	);
        $opt = getopt("f:",$longopts,$rest_index);
        if(array_key_exists('show_sauce',$opt)) {$HTMLshow_sauce=strcmp($opt['show_sauce'],'false')==0?false:true;}
	if(array_key_exists('width',$opt)) { $width=$opt['width']; }
        if(array_key_exists('bg',$opt))		{$ANSIbg=$opt['bg'];}
        if(array_key_exists('fg',$opt))		{$ANSIfg=$opt['fg'];}
        if(array_key_exists('use5m',$opt)){$use5m=$opt['use5m'];}
        if(array_key_exists('use7m',$opt)){$use7m=strcmp($opt['use7m'],'false')==0?false:true;}
	if(array_key_exists('background',$opt))	{$background=$opt['background'];}
	if(array_key_exists('foreground',$opt))	{$foreground=$opt['foreground'];}
	if(array_key_exists('cp437_to_utf8',$opt)){ $cp437_to_utf8=strcmp($opt['cp437_to_utf8'],'false')==0?false:true; }
	if(array_key_exists('broken_pipe',$opt)){ $broken_pipe=strcmp($opt['broken_pipe'],'false')==0?false:true; }
	if(array_key_exists('header',$opt)){ $header=strcmp($opt['header'],'false')==0?false:true; }
	if(array_key_exists('format',$opt)){ $HTMLformat=$opt['format']; }
	if(array_key_exists('preserve_crlftab',$opt)){ $preserve_crlftab=strcmp($opt['preserve_crlftab'],'false')==0?false:true; }
	if(array_key_exists('preserve_escape',$opt)){ $preserve_escape=strcmp($opt['preserve_escape'],'false')==0?false:true; }
	if(array_key_exists('show_null',$opt)){ $HTML_show_null=$opt['show_null']; }
	if(array_key_exists('nbsp',$opt)){ $HTML_use_nbsp=strcmp($opt['nbsp'],'false')==0?false:true; }
        if(array_key_exists('f',$opt)) {
                $fname=$opt['f'];
        } else {
	echo "\n";
        echo "Require: -f [filename.ans |OR| '-' for STDIN]  \n";
	echo "\n";
        echo " Option:\n";
        echo "   --show_sauce=\"true |OR| false\"\n";
	echo "          (default: true)\n";
	echo "          show SAUCE meta data\n";
	echo "   --format='HTML format of output'\n";
	echo "	 default: '<span style=\"color:%foreground%; background:%background%\">%content%</span>'\n";
	echo "   --header=\"true |OR| false\"\n";
	echo "          (default: true)\n";
	echo "          output html headers, etc.\n";
	echo "   --background=\"html color/code\" \n";
	echo "          (default: \"black\")\n";
	echo "          for html display area background\n";
	echo "          eg. 'black', 'red', '#B8B800', etc.\n"; 
	echo "   --background=\"html color/code\" \n";
	echo "          (default: \"white\")\n";
	echo "          for html display area foreground\n";
	echo "          eg. 'white', 'red', '#B8B800', etc.\n"; 
	echo "   --cps437_to_utf8=\"true |OR| false\"\n";
	echo "          (default: true)\n";
	echo "          transcode code page 437 to Unicode utf8 format\n";
	echo "   --broken_pipe=\"true |OR| false\"\n";
	echo "          (default: true)\n";
	echo "          transcode '|' to classic broken pipe\n";
	echo "   --preserve_crlftab=\"true |OR| false\"\n";
	echo "          (default: true)\n";
	echo "          keep carriage return,linefeed,tab - no transcode\n";
	echo "   --preserve_crlftab=\"true |OR| false\"\n";
	echo "          (default: true)\n";
	echo "          keep escape character - no transcode\n";
        echo "   --show_null=\"character to show\"\n";
	echo "          (default: \x0)\n";
	echo "          eg. '\x0', '&#00;', ' ', '&nbsp;', etc.\n";
        echo "   --nbsp=\"true |OR| false\"\n";
	echo "          (default: false)\n";
	echo "          replace space ' ' with &nbsp;\n";
        echo "   --use5m==\"auto |OR| true |OR| false\" default: auto)\n";
        echo "          use bright 5m background (iCE colors)\n";
        echo "   --use7m==\"true |OR| false\" default: true)\n";
        echo "          do color inverse (inline 7m is experimental) \n";
	echo "   --width=\"auto |OR| none |OR|i width in number\"\n";
	echo "          (default: auto)\n";
	echo "          add CRLF at certain width\n";
	echo "          auto   - use width from SAUCE if available, or none\n";
	echo "          number - use this width\n";
	echo "          none   - do not add any CRLF\n";
	echo "          eg. --width=\"auto\", --width=108, --width=21, etc.\n";
	echo "   --bg=\"color code\" \n";
	echo "          (default: \"40\" - black)\n";
	echo "          inline attribute \"color code\" as default ANSI background\n";
	echo "          eg. 40, 41, 5;42, etc.\n"; 
	echo "   --fg=\"color code\" \n";
	echo "          (default: \"37\" - grey)\n";
	echo "          inline attribute \"color code\" as default ANSI foreground\n";
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
	'HTMLformat'	=> $HTMLformat,
	'HTMLshow_sauce'	=> $HTMLshow_sauce,
	'width'		=> $width, // ie. 'auto', 'none', or a number 108,21,80, etc.
	'use5m'		=> $use5m,
	'use7m'		=> $use7m,
	'ANSIfg'	=> $ANSIfg,
	'ANSIbg'	=> $ANSIbg,
	'HTMLbackground'	=> $background,
	'HTMLforeground'	=> $foreground,
	'HTMLheader'	=> $header,
	'HTML_use_nbsp'	=> $HTML_use_nbsp,
	'HTML_show_null'	=> $HTML_show_null,
	'cp437_to_utf8' => $cp437_to_utf8,
	'broken_pipe'	=> $broken_pipe,
	'preserve_crlftab'	=> $preserve_crlftab,
	'preserve_escape'	=> $preserve_escape,
];

echo ansi_TO_HTML($input,$option);
