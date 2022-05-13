<?php
$INFO=<<<COPY
*
* Function library for ANSI to HTML/JSON 
* - with cp437 to Unicode UTF8 character transcode
* - with SAUCE information processing
* 
* Requires: 
*  cp437_TO_UTF8.php
*  ansi_SAUCE_lib.php
*
* (c) Ram Narula <ram@pluslab.com> github @rambkk
COPY;

// DEFAULT OPTIONS
function param($option,$default=[
        'HTMLformat'        => '<span style="color:%foreground%; background:%background%">%content%</span>',
        'HTMLshow_sauce'    => true,
        'SAUCEhide'     => true,
        'width'         => 'auto',
        'HTMLheader'    => true,
        'HTMLbackground'    => 'black',
        'HTMLforeground'    => 'white',
	'HTML_use_nbsp' => false,
        'use5m'         => true,
        'use7m'         => true,
        'ANSIfg'        => '37',
        'ANSIbg'        => '40',
        'cp437_to_utf8' => true,
        'broken_pipe'   => true,
        'preserve_crlftab' => true,
        'preserve_escape' => true,
	'HTML_show_null' => false,
	'JSON_pretty_print' => true
]){

	foreach($default as $key=>$value) {
		if(array_key_exists($key,$option)) {$default[$key]=$option[$key];}
	}	
	return $default;
}


function ansi_INIT($input,$option)
{

$cur=param($option);
$SAUCEhide=$cur['SAUCEhide'];
$width=$cur['width'];
$ANSIfg=$cur['ANSIfg'];
$ANSIbg=$cur['ANSIbg'];
$use5m=$cur['use5m'];
$use7m=$cur['use7m'];
$cp437_to_utf8=$cur['cp437_to_utf8'];

$cp437_to_utf8_option=[
        'preserve_crlftab'	=> $cur['preserve_crlftab'],
        'preserve_escape'	=> $cur['preserve_escape'],
        'broken_pipe'           => $cur['broken_pipe'],
];


require_once(dirname(__FILE__).'/ansi_SAUCE_lib.php');
$SAUCE=sauce_structure($input);
if($SAUCEhide==true && $SAUCE['SAUCEbytes']>0)$input=substr($input,0,-$SAUCE['SAUCEbytes']);

$original_input=ansi_TO_structure($input);
$input=$original_input;

$use5m=brightbgParam($use5m,$SAUCE);
$cur['use5m']=$use5m;
$input=ansi_structure_inline($input,$cur);

$curWidth=widthParam($width,$SAUCE);
$input=ansi_width_crlf($input,$curWidth);

$status=['width' => $curWidth,'use5m' => $use5m];

if($cp437_to_utf8) { $input=struct_cp437_to_utf8($input,$cp437_to_utf8_option); }

$det=(['SAUCE' => $SAUCE, 'OPTION' => $option, 'STATUS' => $status, 'ANSIDATA_ORIGINAL'=> $original_input, 'ANSIDATA' => $input]);


return $det;
}

function brightbgParam($use5m,$SAUCE) {
	if(strcmp($use5m,'auto')==0 && $SAUCE['SAUCEbytes']>0) {
		return $SAUCE['ansi_info']['B']==0?false:true;
	} else if(strcmp($use5m,'false')==0) {
		return false;
	} else return true;
}

function ansi_TO_JSON($input,$option=[])
{

$cur=param($option);
$input=ansi_INIT($input,$cur);
$input['ANSIDATA_ORIGINAL']=struct_ascii_TO_hex($input['ANSIDATA_ORIGINAL']);
$input['ANSIDATA']=struct_ascii_TO_hex($input['ANSIDATA']);

$JSON_OPTION=0;
$JSON_OPTION|=$cur['JSON_pretty_print']?JSON_PRETTY_PRINT:0;
return  json_encode($input,$JSON_OPTION);
}



function struct_cp437_to_utf8($input,$option) {
$cur=param($option);
require_once(dirname(__FILE__).'/cp437_utf8_lib.php');
$det=array();
        foreach($input as $block) {
		if(strlen($block['content'])>0 && !(array_key_exists('type',$block) && strcmp($block['type'],'EXTRA_CRLF')==0)) {
			$block['content']=cp437_TO_UTF8($block['content'],$cur);
		}
                $det[]=$block;
        }
//return ['SAUCE' => $input['SAUCE'], 'OPTION' => $cur, 'STATUS' => $input['STATUS'], 'ANSIDATA' => $det];
return $det;
}

	

//function param($default,$cur) {
//	foreach($default as $key=>$value) {
//		if(array_key_exists($key,$cur)) {$default[$key]=$cur[$key];}
//	}	
//	return $default;
//}
//

function ansi_TO_HTML($input,$option=[]) 
{
$cur=param($option);
$input=ansi_INIT($input,$cur);


$HTMLbackground=$cur['HTMLbackground'];
$HTMLforeground=$cur['HTMLforeground'];
$width=$input['STATUS']['width'];

$prewidth=$width>0?$width.'ch':'auto';
$html=struct_TO_html($input,$cur);
$HTML_SAUCE='';
if($cur['HTMLshow_sauce'] && $input['SAUCE']['SAUCEbytes']>0) {
	$HTML_SAUCE='<table border="1" style="border-collapse:collapse;">';
	foreach($input['SAUCE']['data'] as $key=>$value) {
		if(strcmp($key,"COMNT")!=0) { 
			$HTML_SAUCE.="<tr><td><pre>$key</pre></td><td><pre>$value</pre></td></tr>\n";
		} else {
			$HTML_SAUCE.="<tr><td><pre>Comments</pre></td><td></td></tr>\n";
			foreach($input['SAUCE']['data']['COMNT'] as $cindex=>$comment) {
			$HTML_SAUCE.="<tr><td><pre>". ($cindex+1)."</pre></td><td><pre>$comment</pre></td></tr>\n";
			}
		}
	}
	$HTML_SAUCE.='</table>';
}

$header=<<<EOH
<html>
<head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<style>
@font-face {
    font-family: "IBM VGA 8x16";
    src: url( "WebPlus_IBM_VGA_8x16.woff" ) format( "woff" );
}
</style>
<pre style='font-family: "IBM VGA 8x16", monospace; line-height: 1em; white-space:pre-wrap; line-break: anywhere; width: {$prewidth}; background: {$HTMLbackground}; color: {$HTMLforeground}'>

EOH;

$endhtml=<<<EOHTML
</pre>
EOHTML;
$footer=<<<EOF

</body></html>
EOF;

if($cur['HTMLheader']) { 
	$html=$header.$html.$endhtml.$HTML_SAUCE.$footer; 
} else {
	$html= $html.$HTML_SAUCE;
}
return $html;

}
 

function struct_TO_html($input,$option) {
$HTMLformat=$option['HTMLformat'];
$HTML_show_null=$option['HTML_show_null'];
$HTML_use_nbsp=$option['HTML_use_nbsp'];

$replace_option=false;
$replace_option_from=[];
$replace_option_to=[];
if($HTML_show_null) { $replace_option_from[]=chr(0); $replace_option_to[]='&#00;';  $replace_option=true;}
if($HTML_use_nbsp)  { $replace_option_from[]=' ';    $replace_option_to[]='&nbsp;'; $replace_option=true;}

$color=array();
$color[30]="black";
$color[31]="darkred";
$color[32]="darkgreen";
$color[33]="#8b8b00";
$color[34]="darkblue";
$color[35]="darkmagenta";
$color[36]="darkcyan";
$color[37]="gray";
$color[130]="dimgray";
$color[131]="red";
$color[132]="green";
$color[133]="yellow";
$color[134]="blue";
$color[135]="magenta";
$color[136]="cyan";
$color[137]="white";
$color[40]="black";
$color[41]="darkred";
$color[42]="darkgreen";
$color[43]="#8b8b00";
$color[44]="darkblue";
$color[45]="darkmagenta";
$color[46]="darkcyan";
$color[47]="gray";
$color[140]="dimgray";
$color[141]="red";
$color[142]="green";
$color[143]="yellow";
$color[144]="blue";
$color[145]="magenta";
$color[146]="cyan";
$color[147]="white";

$html='';
foreach ($input['ANSIDATA'] as $block) {
	if(array_key_exists('content',$block)) {
		if($replace_option) {$block['content']=str_replace($replace_option_from,$replace_option_to,$block['content']);}
		if(array_key_exists('foreground',$block) && array_key_exists('background',$block)) {
			$htmlfg=$color[intval(preg_replace("/1;(3[0-9])/",'1'.'${1}',$block['foreground']))];
			$htmlbg=$color[intval(preg_replace("/5;(4[0-9])/",'1'.'${1}',$block['background']))];
			$replace_from=['%foreground%',	'%background%',	'%content%'];
			$replace_to=  [$htmlfg,		$htmlbg,	$block['content']];
			$html.=str_replace($replace_from,$replace_to,$HTMLformat);
		} else {
			$html.=$block['content'];
		}
	}
}
return $html;
}
		

function struct_ascii_TO_hex($input) {
	$safehex='';
	$det=array();
	foreach($input as $block) {
		if(strlen($block['content']) > 0) {
			$bytes=str_split($block['content']);
			foreach($bytes as $byte) {
				$char=ord($byte);
				$safehex.=($char<32 || $char>126 || $char==92)?'\x'.strtoupper(dechex($char)):$byte;
			}
		}
		$block['content']=$safehex;
		$safehex='';
		$det[]=$block;
	}

return $det;
}

//function widthParam($input,$width,$SAUCE) {
function widthParam($width,$SAUCE) {
	if(strcmp($width,'auto')==0) {
	       return $SAUCE['SAUCEbytes']>0?$SAUCE['ansi_info']['width']:80;
	} else if(strcmp($width,'none') == 0) {
		return 0;
	} else { 
		return $width;
	}

}


function getSAUCE($input) {

if(strcmp('SAUCE',substr($input, -128,5))==0) { $havesauce=true; } else { 
	return ['saucebytes' => 0, 'width' => 0];
}

if($havesauce) {
	$saucelength=128+1;
	$saucewidth=ord(substr($input, -32,1));
	$commentchk=ord(substr($input,-24,1));
	$saucelength+=64*$commentchk+strlen("COMNT");

	return 	[
		'width' => $saucewidth,
		'saucebytes' => $saucelength
		];
}

}




function ansi_TO_structure($filedata) {

$linebreaks=preg_split("/\r\n/",$filedata);
$det=array();
foreach ($linebreaks as $line) {
  $esc=preg_split("/\x1B\[/",$line);
  foreach ($esc as $str) {
	  preg_match("/^[0-9]+(;[0-9]+)*m/",$str,$attrstring);
	  if(array_key_exists(0,$attrstring)) {
		$content=substr($str,strlen($attrstring[0]));
		$pair=array(
				'content' 	=> $content,
				'attrstring' 	=> $attrstring[0]
		);
	  	$attrs=explode(";",rtrim($pair['attrstring'],'m'));

		$block=[
			'm'   => $attrs,
			'sequence' => $pair['attrstring'],
			'content' => $pair['content']
		];
		$det[]=$block;
	  } else {
		  if(strlen($str)>0)  $det[]= ['content'=> $str];
	  }
  }
  $det[]=['type'=>'NORMAL_CRLF','content'=>"\r\n"];
}

//remove last "\r\n" 
array_pop($det);
return $det;
}

function ansi_structure_inline($input,$option) {

$def=[
        'ANSIfg' => '37',
        'ANSIbg' => '40',
        'use5m' => true,
        'use7m' => true,
];

$cur=param($option);

$ANSIfg=$cur['ANSIfg'];
$ANSIbg=$cur['ANSIbg'];
$use5m=$cur['use5m'];
$use7m=$cur['use7m'];

$color[30]="30";
$color[31]="31";
$color[32]="32";
$color[33]="33";
$color[34]="34";
$color[35]="35";
$color[36]="36";
$color[37]="37";
$color[130]="1;30";
$color[131]="1;31";
$color[132]="1;32";
$color[133]="1;33";
$color[134]="1;34";
$color[135]="1;35";
$color[136]="1;36";
$color[137]="1;37";
$color[40]="40";
$color[41]="41";
$color[42]="42";
$color[43]="43";
$color[44]="44";
$color[45]="45";
$color[46]="46";
$color[47]="47";
$color[140]="5;40";
$color[141]="5;41";
$color[142]="5;42";
$color[143]="5;43";
$color[144]="5;44";
$color[145]="5;45";
$color[146]="5;46";
$color[147]="5;47";



$sbold=preg_match('/(^1;)|(;1$)|(;1;)/',$ANSIfg)?100:0;
$sbrightbg=preg_match('/(^5;)|(;5$)|(;5;)/',$ANSIbg)?100:0;

preg_match_all('/(^|;)(3[0-7])(;|$)/',$ANSIfg,$matches);
$sfg=end($matches[2]);
preg_match_all('/(^|;)(4[0-7])(;|$)/',$ANSIbg,$matches);
$sbg=end($matches[2]);


$fg=$sfg;
$bg=$sbg;
$bold=$sbold;
$brightbg=$sbrightbg;
$finalfg=$color[$bold+$fg];
$finalbg=$color[$brightbg+$bg];



$det=array();
  foreach ($input as $block) {
	  if(array_key_exists('m',$block)) {
			$add0m='';
			foreach($block['m'] as $attr) {
				if($attr == 0) { $bold=$sbold; $brightbg=$sbrightbg; $fg=$sfg; $bg=$sbg; $add0m='0;';}
				if($attr == 1) { $bold=100; }
				if($attr == 2) { $bold=0; }
				if($attr >= 30 && $attr<=37) { $fg=$attr; }
				if($attr >= 40 && $attr<=47) { $bg=$attr; }
				if($attr==5) { $brightbg=100; }
				if($use7m && $attr==7) {	$tmp=$bold; $bold=$brightbg; $brightbg=$tmp;
								$tmp=$fg+10; $fg=$bg-10; $bg=$tmp;
				}
				if(!$use5m) {$brightbg=0;}
				$finalfg=$color[$bold+$fg];
				$finalbg=$color[$brightbg+$bg];
			}

			$block['foreground']=$finalfg;
			$block['background']=$finalbg;
                        $block['sequence-inline']=$add0m.$finalfg.';'.$finalbg.'m';
			$add0m='';
	  }
  $det[]=$block;
  }

return $det;

} //end function


function struct_TO_ansi($input) {
	foreach($input as $content) {
		if(array_key_exists('sequence-inline',$content)) {
		echo "\x1b[".$content['sequence-inline'];
		}
		echo stripcslashes($content['content']);
	}
}


function ansi_width_crlf($input,$width) {
	
if($width==0) return $input;

$wcount=0;
$det=array();
foreach($input as $block) {
	if(strcmp($block['content'],"\r\n")==0) {
		$det[]=['content'=>"\r\n"];
		$wcount=0;
	} else {
                $excess=0;
	     	$content=$block['content'];
	     	$contentl=strlen($content);
		$wcount+=$contentl;

                        while($wcount>$width) {

                                $excess=$wcount-$width;
				$nextblockcontent=substr($content,-$excess);
				$block['content']=substr($content,0,$contentl-$excess);
				$det[]=$block;
  				$det[]=['type'=>"EXTRA_CRLF",'content'=>"\r\n"];
  				//$det[]=['content'=>"\r\n"];

				$wcount-=$width;
				$content=$nextblockcontent;
				$contentl=strlen($content);
				$block['content']=$content;

                        }
			$det[]=$block;
			if($wcount==$width) {
  				$det[]=['type'=>"EXTRA_CRLF",'content'=>"\r\n"];
  				//$det[]=['content'=>"\r\n"];
				$wcount=0;
			}
                }

}

return $det;

}
