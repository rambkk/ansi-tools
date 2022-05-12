# ansi-to-html
Application to convert ANSI art (CP437) ANSI data file to HTML web page file

(CP437 - Code page 437 character set)

_The ***"IBM VGA 8x16"*** font seems to display ANSI art graphics quite well and this will be used_

- works with ANSI data with fixed width (without new line characters)
- does not work with attributes other than 0,1,5,7,30-37,40-47 m
- supports 16 colors for background (8 bright colors - iCE color)
- inverse (7m) is experimental, and blinking (5m) is not supported

General steps in processing ANSI data:
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


## cp437 TO UTF-8
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
  * \*(There are option to convert these to Unicode UTF-8 by using special parameters)
  - Carriage Return (0x0A)
  - Line feed (0x0D)
  - Horizontal Tab (0x09) 
  - Escape character (0x1B - this used at beginning of ANSI escape sequence)

Special
  - classic 'broken-pipe' ¦ is used instead of 'pipe' | (or use the non broken pipe)
  - render null character (\x00) as space (or show null which should not take up any space)


# Requirement
You must have PHP installed

### Library/Include files:
* lib-php/ansi_lib.php
* lib-php/ansi_SAUCE_lib.php
* lib-php/cp437_utf8.php

### Examples - command-line (filename.ANS uses cp437) 
* Generate HTML from CP437.ANS without showing SAUCE data\
```php ansi_to_html.php -f CP437.ANS --show_sauce=false > CP437.html```
* Generate JSON outout from CP437.ANS \
```php ansi_to_json.php -f CP437.ANS > CP437.json```
* Generate Unicode UTF8 from  CP437.ANS\
```php cp437_to_utf8 -f CP437.ANS > CP437_utf8.ANS```
* for STDIN instead of file use ```-f -``` \
```cat CP437_1.ANS | php ansi_TO_HTML.php -f - > CP437.html ```
* Little bit more fancy \
```php ansi_to_html.php -f CP437.ANS --show_sauce=true --preserve_crlftab=false --preserve_escape=false --show_null_space=true > CP437.html```
 
