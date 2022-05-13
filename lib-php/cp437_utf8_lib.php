<?php
$INFO=<<<COPY
*
* Function library for cp437 to Unicode UTF8 transcoding
* 
* (c) Ram Narula <ram@pluslab.com> github @rambkk
COPY;

// DEFAULT OPTIONS
function param_cp437_TO_utf8($option) {
	$default=[
	'preserve_escape'	=> true,
	'preserve_crlftab'	=> true,
	'broken_pipe'		=> true,
	'show_null_space'	=> false
	]; 

        foreach($default as $key=>$value) {
                if(array_key_exists($key,$option)) {$default[$key]=$option[$key];}
        }
        return $default;
}

function cp437_TO_utf8($text,$option) {

$cur=param_cp437_TO_utf8($option);
$preserve_escape=$cur['preserve_escape'];
$preserve_crlftab=$cur['preserve_crlftab'];
$broken_pipe=$cur['broken_pipe'];
$show_null_space=$cur['show_null_space'];

$utf8=array();

$utf8[0]="\u{0}";
$utf8[1]="\u{263A}";
$utf8[2]="\u{263B}";
$utf8[3]="\u{2665}";
$utf8[4]="\u{2666}";
$utf8[5]="\u{2663}";
$utf8[6]="\u{2660}";
$utf8[7]="\u{2022}";
$utf8[8]="\u{25D8}";
$utf8[9]="\u{9}";
$utf8[10]="\u{A}";
$utf8[11]="\u{2642}";
$utf8[12]="\u{2640}";
$utf8[13]="\u{D}";
$utf8[14]="\u{266B}";
$utf8[15]="\u{263C}";
$utf8[16]="\u{25BA}";
$utf8[17]="\u{25C4}";
$utf8[18]="\u{2195}";
$utf8[19]="\u{203C}";
$utf8[20]="\u{B6}";
$utf8[21]="\u{A7}";
$utf8[22]="\u{25AC}";
$utf8[23]="\u{21A8}";
$utf8[24]="\u{2191}";
$utf8[25]="\u{2193}";
$utf8[26]="\u{2192}";
$utf8[27]="\u{1B}";
$utf8[28]="\u{221F}";
$utf8[29]="\u{2194}";
$utf8[30]="\u{25B2}";
$utf8[31]="\u{25BC}";
$utf8[32]="\u{20}";
$utf8[33]="\u{21}";
$utf8[34]="\u{22}";
$utf8[35]="\u{23}";
$utf8[36]="\u{24}";
$utf8[37]="\u{25}";
$utf8[38]="\u{26}";
$utf8[39]="\u{27}";
$utf8[40]="\u{28}";
$utf8[41]="\u{29}";
$utf8[42]="\u{2A}";
$utf8[43]="\u{2B}";
$utf8[44]="\u{2C}";
$utf8[45]="\u{2D}";
$utf8[46]="\u{2E}";
$utf8[47]="\u{2F}";
$utf8[48]="\u{30}";
$utf8[49]="\u{31}";
$utf8[50]="\u{32}";
$utf8[51]="\u{33}";
$utf8[52]="\u{34}";
$utf8[53]="\u{35}";
$utf8[54]="\u{36}";
$utf8[55]="\u{37}";
$utf8[56]="\u{38}";
$utf8[57]="\u{39}";
$utf8[58]="\u{3A}";
$utf8[59]="\u{3B}";
$utf8[60]="\u{3C}";
$utf8[61]="\u{3D}";
$utf8[62]="\u{3E}";
$utf8[63]="\u{3F}";
$utf8[64]="\u{40}";
$utf8[65]="\u{41}";
$utf8[66]="\u{42}";
$utf8[67]="\u{43}";
$utf8[68]="\u{44}";
$utf8[69]="\u{45}";
$utf8[70]="\u{46}";
$utf8[71]="\u{47}";
$utf8[72]="\u{48}";
$utf8[73]="\u{49}";
$utf8[74]="\u{4A}";
$utf8[75]="\u{4B}";
$utf8[76]="\u{4C}";
$utf8[77]="\u{4D}";
$utf8[78]="\u{4E}";
$utf8[79]="\u{4F}";
$utf8[80]="\u{50}";
$utf8[81]="\u{51}";
$utf8[82]="\u{52}";
$utf8[83]="\u{53}";
$utf8[84]="\u{54}";
$utf8[85]="\u{55}";
$utf8[86]="\u{56}";
$utf8[87]="\u{57}";
$utf8[88]="\u{58}";
$utf8[89]="\u{59}";
$utf8[90]="\u{5A}";
$utf8[91]="\u{5B}";
$utf8[92]="\u{5C}";
$utf8[93]="\u{5D}";
$utf8[94]="\u{5E}";
$utf8[95]="\u{5F}";
$utf8[96]="\u{60}";
$utf8[97]="\u{61}";
$utf8[98]="\u{62}";
$utf8[99]="\u{63}";
$utf8[100]="\u{64}";
$utf8[101]="\u{65}";
$utf8[102]="\u{66}";
$utf8[103]="\u{67}";
$utf8[104]="\u{68}";
$utf8[105]="\u{69}";
$utf8[106]="\u{6A}";
$utf8[107]="\u{6B}";
$utf8[108]="\u{6C}";
$utf8[109]="\u{6D}";
$utf8[110]="\u{6E}";
$utf8[111]="\u{6F}";
$utf8[112]="\u{70}";
$utf8[113]="\u{71}";
$utf8[114]="\u{72}";
$utf8[115]="\u{73}";
$utf8[116]="\u{74}";
$utf8[117]="\u{75}";
$utf8[118]="\u{76}";
$utf8[119]="\u{77}";
$utf8[120]="\u{78}";
$utf8[121]="\u{79}";
$utf8[122]="\u{7A}";
$utf8[123]="\u{7B}";
$utf8[124]="\u{00A6}";
$utf8[125]="\u{7D}";
$utf8[126]="\u{7E}";
$utf8[127]="\u{2302}";
$utf8[128]="\u{C7}";
$utf8[129]="\u{FC}";
$utf8[130]="\u{E9}";
$utf8[131]="\u{E2}";
$utf8[132]="\u{E4}";
$utf8[133]="\u{E0}";
$utf8[134]="\u{E5}";
$utf8[135]="\u{E7}";
$utf8[136]="\u{EA}";
$utf8[137]="\u{EB}";
$utf8[138]="\u{E8}";
$utf8[139]="\u{EF}";
$utf8[140]="\u{EE}";
$utf8[141]="\u{EC}";
$utf8[142]="\u{C4}";
$utf8[143]="\u{C5}";
$utf8[144]="\u{C9}";
$utf8[145]="\u{E6}";
$utf8[146]="\u{C6}";
$utf8[147]="\u{F4}";
$utf8[148]="\u{F6}";
$utf8[149]="\u{F2}";
$utf8[150]="\u{FB}";
$utf8[151]="\u{F9}";
$utf8[152]="\u{FF}";
$utf8[153]="\u{D6}";
$utf8[154]="\u{DC}";
$utf8[155]="\u{A2}";
$utf8[156]="\u{A3}";
$utf8[157]="\u{A5}";
$utf8[158]="\u{20A7}";
$utf8[159]="\u{192}";
$utf8[160]="\u{E1}";
$utf8[161]="\u{ED}";
$utf8[162]="\u{F3}";
$utf8[163]="\u{FA}";
$utf8[164]="\u{F1}";
$utf8[165]="\u{D1}";
$utf8[166]="\u{AA}";
$utf8[167]="\u{BA}";
$utf8[168]="\u{BF}";
$utf8[169]="\u{2310}";
$utf8[170]="\u{AC}";
$utf8[171]="\u{BD}";
$utf8[172]="\u{BC}";
$utf8[173]="\u{A1}";
$utf8[174]="\u{AB}";
$utf8[175]="\u{BB}";
$utf8[176]="\u{2591}";
$utf8[177]="\u{2592}";
$utf8[178]="\u{2593}";
$utf8[179]="\u{2502}";
$utf8[180]="\u{2524}";
$utf8[181]="\u{2561}";
$utf8[182]="\u{2562}";
$utf8[183]="\u{2556}";
$utf8[184]="\u{2555}";
$utf8[185]="\u{2563}";
$utf8[186]="\u{2551}";
$utf8[187]="\u{2557}";
$utf8[188]="\u{255D}";
$utf8[189]="\u{255C}";
$utf8[190]="\u{255B}";
$utf8[191]="\u{2510}";
$utf8[192]="\u{2514}";
$utf8[193]="\u{2534}";
$utf8[194]="\u{252C}";
$utf8[195]="\u{251C}";
$utf8[196]="\u{2500}";
$utf8[197]="\u{253C}";
$utf8[198]="\u{255E}";
$utf8[199]="\u{255F}";
$utf8[200]="\u{255A}";
$utf8[201]="\u{2554}";
$utf8[202]="\u{2569}";
$utf8[203]="\u{2566}";
$utf8[204]="\u{2560}";
$utf8[205]="\u{2550}";
$utf8[206]="\u{256C}";
$utf8[207]="\u{2567}";
$utf8[208]="\u{2568}";
$utf8[209]="\u{2564}";
$utf8[210]="\u{2565}";
$utf8[211]="\u{2559}";
$utf8[212]="\u{2558}";
$utf8[213]="\u{2552}";
$utf8[214]="\u{2553}";
$utf8[215]="\u{256B}";
$utf8[216]="\u{256A}";
$utf8[217]="\u{2518}";
$utf8[218]="\u{250C}";
$utf8[219]="\u{2588}";
$utf8[220]="\u{2584}";
$utf8[221]="\u{258C}";
$utf8[222]="\u{2590}";
$utf8[223]="\u{2580}";
$utf8[224]="\u{3B1}";
$utf8[225]="\u{DF}";
$utf8[226]="\u{393}";
$utf8[227]="\u{3C0}";
$utf8[228]="\u{3A3}";
$utf8[229]="\u{3C3}";
$utf8[230]="\u{B5}";
$utf8[231]="\u{3C4}";
$utf8[232]="\u{3A6}";
$utf8[233]="\u{398}";
$utf8[234]="\u{3A9}";
$utf8[235]="\u{3B4}";
$utf8[236]="\u{221E}";
$utf8[237]="\u{3C6}";
$utf8[238]="\u{3B5}";
$utf8[239]="\u{2229}";
$utf8[240]="\u{2261}";
$utf8[241]="\u{B1}";
$utf8[242]="\u{2265}";
$utf8[243]="\u{2264}";
$utf8[244]="\u{2320}";
$utf8[245]="\u{2321}";
$utf8[246]="\u{F7}";
$utf8[247]="\u{2248}";
$utf8[248]="\u{B0}";
$utf8[249]="\u{2219}";
$utf8[250]="\u{B7}";
$utf8[251]="\u{221A}";
$utf8[252]="\u{207F}";
$utf8[253]="\u{B2}";
$utf8[254]="\u{25A0}";
$utf8[255]="\u{A0}";

if(!$broken_pipe) {
	$utf8[124]="\u{7C}";
}
if(!$preserve_crlftab) {
	$utf8[9]="\u{25CB}";
	$utf8[10]="\u{25D9}";
	$utf8[13]="\u{266A}";
}
if(!$preserve_escape) {
	$utf8[27]="\u{2190}";
}

$bytes=str_split($text);
$out='';
foreach ($bytes as $v) {
	$out.=$utf8[ord($v)];
}

return $out;
}

?>
