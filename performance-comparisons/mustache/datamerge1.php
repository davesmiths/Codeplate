<?php

// STRING
$string = '
{{#publicationsExist}}<ul>{{/publicationsExist}}
{{#publications}}
    <li><strong>{{name}}</strong><br/>{{year}}<br/>{{type}}</li>
{{/publications}}
{{#publicationsExist}}</ul>{{/publicationsExist}}
';
$data = array(
    'publicationsExist' => true,
    'publications' => array(
        array(
            'name' => 'Good one',
            'year' => '2015',
            'type' => 'Magazine',
        ),
        array(
            'name' => 'Good two',
            'year' => '2013',
            'type' => 'Paper',
        ),
        array(
            'name' => 'Good three',
            'year' => '2012',
            'type' => 'Magazine',
        ),
        array(
            'name' => 'Good four',
            'year' => '2014',
            'type' => 'Journal',
        ),
        array(
            'name' => 'Good five',
            'year' => '2009',
            'type' => 'Journal',
        ),
        array(
            'name' => 'Good six',
            'year' => '2012',
            'type' => 'Book',
        ),
        array(
            'name' => 'Good seven',
            'year' => '2015',
            'type' => 'Magazine',
        ),
    )
);
include_once './mustache/Autoloader.php';
Mustache_Autoloader::register();
$m = new Mustache_Engine;

// Microtime start
$microtimeRepeats = 5000;
$microtimeRepeats = 2;
$microtimeCounter = 0;
$microtimeStart = microtime(true);
for ($microtimeCounter = 0; $microtimeCounter < $microtimeRepeats; $microtimeCounter++) {

    $m->render($string, $data);

}
// Microtime end
$microtimeEnd = microtime(true);
echo 'One time run, so calculate a typical average time<br>';
echo ($microtimeEnd-$microtimeStart)/$microtimeRepeats . " sec";

// Looks like mustache is caching the output in some way
// repeats of 1 are roughly double the time for 2 etc.

// Typical 1 time run was
// 0.00497448444366 sec

?>
