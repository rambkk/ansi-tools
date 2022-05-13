# ansi-to-html
Application to convert ANSI art (CP437) ANSI data file to HTML web page file

(CP437 - Code page 437 character set)

Simple example (to remove SAUCE info check the ansi_to_html_simple.php, options below):
```php
<?php
require_once("lib-php/ansi_lib.php");

$input=file_get_contents('CP437.ANS');
echo ansi_TO_HTML($input);
?>
```

- works with ANSI data normal width and fixed width (with or without new line characters)
- does not work with attributes other than 0,1,5,7,30-37,40-47 m
- supports 16 colors for background (8 bright colors - iCE color)
- inverse (7m) is experimental, and blinking (5m) is not supported

# Font
The ***"IBM VGA 8x16"*** font seems to display ANSI art graphics quite well and this will be used
This font along with other cp485 fonts is available from www.int10h.org


# General steps in processing ANSI data:
   - check for SAUCE information and put it in SAUCE array structure
   - read ANSI data into array structure, separate into blocks by escape sequence or new line
   - process ANSI array and create inline data by add attribute for each block (without nesting)
   - process the array structure and add line-breaks based on width information
   - transcode each block content from cp437 to Unicode UTF-8 format
   - To generate HTML file output 
       - process ANSI data array structure to HTML content
       - output headers and footer (optional)
       - output HTML color code with content
       - output SAUCE information table (optional)
   - To generate JSON output 
       - convert ANSI array content backslash '\' and special characters to hex format \xNN
       - encode converted ANSI array into JSON format


# cp437 TO UTF-8
Convert the CP475 characters by replacing bytes with Unicode UTF-8 character codes

Displaying CP437 characters directly (even under HTML format) can be problematic as it depends on computer's code page and other factors.
Converting them to Unicode UTF-8 Unicode encoding should make it safe for displaying correctly.

Here are the CP437 characters (encoded in utf8 for displaying):
```ansi
 ☺☻♥♦♣♠•◘○◙♂♀♪♫☼►◄↕‼¶§▬↨↑↓→←∟↔▲▼ 
 !"#$%&'()*+,-./0123456789:;<=>?
@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_
`abcdefghijklmnopqrstuvwxyz{¦}~⌂
ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒ
áíóúñÑªº¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐
└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀
αßΓπΣσµτΦΘΩδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■ 
```
The process of conversion is quite simple by matching each byte with corresponding utf8 unicode character.

### Note:
Few characters should 'generally' be preserved from trancoding:
  * \*(There are options to force convert these to Unicode UTF8 symbols)
  - Carriage Return (0x0A)
  - Line feed (0x0D)
  - Horizontal Tab (0x09) 
  - Escape character (0x1B - this used at beginning of ANSI escape sequence)

Special
  - classic 'broken-pipe' ¦ is used instead of 'pipe' | (or use the non broken pipe)
  - HTML option: render null character (\x0) as specified character eg. ``' ' or '&#00;' or '&nbsp;' ...``
  - HTML option: render space character ' ' as ``&nbsp;``



# Requirement
You must have PHP installed

## Library/Include files:
* lib-php/ansi_lib.php
* lib-php/ansi_SAUCE_lib.php
* lib-php/cp437_utf8.php

## Examples - command-line (filename.ANS uses cp437) 
* Generate HTML from CP437.ANS without showing SAUCE data\
```php ansi_to_html.php -f CP437.ANS --show_sauce=false > CP437.html```
* Generate JSON outout from CP437.ANS \
```php ansi_to_json.php -f CP437.ANS > CP437.json```
* Generate Unicode UTF8 from  CP437.ANS\
```php cp437_to_utf8 -f CP437.ANS > CP437_utf8.ANS```
* for STDIN instead of file use ```-f -``` \
```cat CP437_1.ANS | php ansi_TO_HTML.php -f - > CP437.html ```
* Little bit more fancy \
```php ansi_to_html.php -f CP437.ANS --show_sauce=true --preserve_crlftab=false --preserve_escape=false --show_null=' ' > CP437.html```
 
# Options with example
Here is the list of default options:
```php
<?php
$option=[
        'SAUCEhide'     => true,
        'HTML_format'        => '<span style="color:%foreground%; background:%background%">%content%</span>',
        'HTML_show_sauce'    => true,
        'HTML_header'	     => true,
        'HTML_background'    => 'black',
        'HTML_foreground'    => 'white',
        'HTML_use_nbsp'      => false,
        'HTML_show_null'     => ' ',
        'width'         => 'auto',
        'use5m'         => true,
        'use7m'         => true,
        'ANSIfg'        => '37',
        'ANSIbg'        => '40',
        'cp437_to_utf8' => true,
        'broken_pipe'   => true,
        'preserve_crlftab'  => true,
        'preserve_escape'   => true,
        'JSON_pretty_print' => true
];
```
The option could be used like in this example:
```php
<?php
require_once("lib-php/ansi_lib.php");

$input=file_get_contents('CP437.ANS');

$option=[
	'HTML_show_sauce' => false,
	'HTML_foreground' =>'black',
	'HTML_background' =>'white'
];
echo ansi_TO_HTML($input,$option);
```

# Command line options:

## Options for ansi_to_html.php
```bash
php ansi_to_html.php

Require: -f [filename.ans |OR| '-' for STDIN]

 Option:
   --show_sauce="true |OR| false"
          (default: true)
          show SAUCE meta data
   --format='HTML format of output'
         default: '<span style="color:%foreground%; background:%background%">%content%</span>'
   --header="true |OR| false"
          (default: true)
          output html headers, etc.
   --background="html color/code"
          (default: "black")
          for html display area background
          eg. 'black', 'red', '#B8B800', etc.
   --background="html color/code"
          (default: "white")
          for html display area foreground
          eg. 'white', 'red', '#B8B800', etc.
   --cps437_to_utf8="true |OR| false"
          (default: true)
          transcode code page 437 to Unicode utf8 format
   --broken_pipe="true |OR| false"
          (default: true)
          transcode '|' to classic broken pipe
   --preserve_crlftab="true |OR| false"
          (default: true)
          keep carriage return,linefeed,tab - no transcode
   --preserve_escape="true |OR| false"
          (default: true)
          keep escape character - no transcode
   --show_null="character to show"
          (default: ' ')
          eg. '^@', '&#00;', ' ', '&nbsp;', etc.
   --nbsp="true |OR| false"
          (default: false)
          replace space ' ' with &nbsp;
   --use5m=="auto |OR| true |OR| false" default: auto)
          use bright 5m background (iCE colors)
   --use7m=="true |OR| false" default: true)
          do color inverse (inline 7m is experimental)
   --width="auto |OR| none |OR|i width in number"
          (default: auto)
          add CRLF at certain width
          auto   - use width from SAUCE if available, or none
          number - use this width
          none   - do not add any CRLF
          eg. --width="auto", --width=108, --width=21, etc.
   --bg="color code"
          (default: "40" - black)
          inline attribute "color code" as default ANSI background
          eg. 40, 41, 5;42, etc.
   --fg="color code"
          (default: "37" - grey)
          inline attribute "color code" as default ANSI foreground
          eg. 30, 31, 1;32, etc.
```

## Options for ansi_to_json.php
```bash
php ansi_to_json.php

Require: -f [filename.ans |OR| '-' for STDIN]

 Option:
   --pretty_print="true |OR| false" (default: true)
          output JSON data in pretty format
   --cps437_to_utf8="true |OR| false" (default: true)
          transcode code page 437 to Unicode utf8 format
   --broken_pipe="true |OR| false"
          (default: true)
          transcode '|' to classic broken pipe
   --preserve_crlftab="true |OR| false"
          (default: true)
          keep carriage return,linefeed,tab - no transcode
   --use5m=="auto |OR| true |OR| false" default: auto)
                use bright 5m background (iCE colors)
   --use7m=="true |OR| false" default: true)
          do color inverse (inline 7m is experimental)
   --width="auto |OR| none |OR| width in number"
          (default: auto)
          add CRLF at certain width
          auto   - use width from SAUCE if available, or none
          number - use this width
          none   - do not add any CRLF
          eg. --width="auto", --width=108, --width=21, etc.
   --bg="color code"
          (default: "40" - black)
          inline attribute "color code" as default ANSI background
          eg. 40, 41, 5;42, etc.
   --fg="color code"
          (default: "37" - grey)
          inline attribute "color code" as default ANSI foreground
          eg. 30, 31, 1;32, etc.
```



_(c) Ram Narula <ram@pluslab.com> github @rambkk_

