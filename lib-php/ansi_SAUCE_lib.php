<?php
$INFO=<<<COPY
* Function library for processing ANSI SAUCE information
*
* Requires:
*  cp437_TO_UTF8.php
*  ansi_SAUCE_lib.php
*
* (c) Ram Narula <ram@pluslab.com> github @rambkk
COPY;

function sauce_structure($input) {
$SAUCEraw=substr($input, -128);
if(strcmp(substr($SAUCEraw,0,5),'SAUCE')!=0) {
	return ['SAUCEbytes' => 0];
}
$sauce_format =
            'A5ID/' . 
            'A2Version/' .
            'A35Title/' .
            'A20Author/' .
            'A20Group/' .
            'A8Date/' .
            'V1FileSize/' .
            'C1DataType/' .
            'C1FileType/' .
            'v1TInfo1/' .
            'v1TInfo2/' .
            'v1TInfo3/' .
            'v1TInfo4/' .
            'C1Comments/' .
            'C1TFlags/' .
            'A22TInfoS/'; 


$SAUCE=[];
$SAUCE['status']='SAUCE';
$SAUCE['description']=[
	'B' => "Non-blink mode (iCE Color).\nWhen 0, only the 8 low intensity colors are supported for the character background. The high bit set to 1 in each attribute byte results in the foreground color blinking repeatedly.\nWhen 1, all 16 colors are supported for the character background. The high bit set to 1 in each attribute byte selects the high intensity color instead of blinking.",
	'LS' => [ 
		0=>'Legacy value. No preference.',
		1=>'Select 8 pixel font.',
		2=>'Select 9 pixel font.',
		3=>'Not currently a valid value.',
	], 
	'AR' => [ 
		0=>'Legacy value. No preference.',
		1=>'Image was created for a legacy device. When displayed on a device with square pixels, either the font or the image needs to be stretched.',
		2=>'Image was created for a modern device with square pixels. No stretching is desired on a device with square pixels.',
		3=>'Not currently a valid value',
	],
	'reference' => 'information from: www.acid.org'

];

$SAUCE['data']=unpack($sauce_format,$SAUCEraw);
$comments=$SAUCE['data']['Comments'];
$SAUCE['SAUCEbytes']=$comments>0?1+5+$comments*64+128:1+128;
$SAUCE['ansi_info']=[
	'width' => $SAUCE['data']['TInfo1'],
	'lines' => $SAUCE['data']['TInfo2'],
	'B'  => ($SAUCE['data']['TFlags'] & 1)==1?true:false,
	'LS' => ($SAUCE['data']['TFlags'] >>1) & 1,
	'AR' => ($SAUCE['data']['TFlags'] >>3) & 3,
	'FontName' => $SAUCE['data']['TInfoS'],
];

if($comments > 0) {
$CommentsHeader=substr($input,-(64*$comments+128+5),5);
if(strcmp($CommentsHeader,'COMNT')==0) {
	$CommentsBlock=substr($input,-(64*$comments+128),-128);
	$SAUCE['data']['COMNT']=str_split($CommentsBlock,64);
}

}

return $SAUCE;

}
