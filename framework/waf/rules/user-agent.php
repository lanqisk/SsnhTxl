<?php
return [
    '(HTTrack|antSword|Apache-HttpClient|harvest|audit|dirbuster|pangolin|nmap|sqln|hydra|Parser|libwww|BBBike|sqlmap|w3af|owasp|Nikto|fimap|havij|zmeu|BabyKrokodil|netsparker|httperf| SF/)',
    '\.\.\/\.\.\/',
    '(?:etc\/\W*passwd)',
    '(gopher|doc|php|glob|file|phar|zlib|ftp|ldap|dict|ogg|data)\:\/',
    'base64_decode\(',
    '(?:define|eval|file_get_contents|include|require_once|shell_exec|phpinfo|system|passthru|chr|char|preg_\w+|execute|echo|print|print_r|var_dump|(fp)open|alert|showmodaldialog|file_put_contents|fopen|urldecode|scandir)\(',
    '\$_(GET|post|cookie|files|session|env|phplib|GLOBALS|SERVER)',
    '\s+(or|xor|and)\s+(=|<|>|\'|")',
    'select\s+.+(from|limit)\s+',
    '(?:(union(.*?)select))',
    'sleep\((\s*)(\d*)(\s*)\)',
    'benchmark\((.*)\,(.*)\)',
    '(?:from\W+information_schema\W)',
    '(?:(?:current_)user|database|schema|connection_id)\s*\(',
    'into(\s+)+(?:dump|out)file\s*',
    'group\s+by.+\(',
    '\<(iframe|script|body|img|layer|div|meta|style|base|object|input)',
    '(onmouseover|onerror|onload)\=',
    '(extractvalue\(|concat\(0x|user\(\)|substring\(|count\(\*\)|substring\(hex\(|updatexml\()',
    '(@@version|load_file\(|NAME_CONST\(|exp\(\~|floor\(rand\(|geometrycollection\(|multipoint\(|polygon\(|multipolygon\(|linestring\(|multilinestring\()',
    '(substr\()',
    '(ORD\(|MID\(|IFNULL\(|CAST\(|CHAR\))',
    '(EXISTS\(|SELECT\#|\(SELECT)',
    '(array_map\("ass)',
    '\|+\s+[\w\W]+=[\w\W]+'.
    '(bin\(|ascii\(|benchmark\(|concat_ws\(|group_concat\(|strcmp\(|left\(|datadir\(|greatest\()'
];