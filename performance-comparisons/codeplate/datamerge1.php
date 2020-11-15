<?php


// STRING
$string = '
[#publications]
[#pre]<ul>[/pre]
    <li><strong>[name]</strong><br/>[year]<br/>[type]</li>
[#post]</ul>[/post]
[/publications]
';


// DATA
$data = array(
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


// CODEPLATE
include_once './../../codeplate.php';
$codeplate = new CODEPLATE;


// Microtime start
// $microtimeRepeats = 5000;
$microtimeRepeats = 1;
$microtimeCounter = 0;
$microtimeStart = microtime(true);
for ($microtimeCounter = 0; $microtimeCounter < $microtimeRepeats; $microtimeCounter++) {

    $codeplate->render(array(
    // echo $codeplate->render(array(
        'string' => $string,
        'data' => $data,
    ));

}

// Microtime end
$microtimeEnd = microtime(true);
echo 'One time run, so calculate a typical average time<br>';
echo ($microtimeEnd-$microtimeStart)/$microtimeRepeats . " sec";

// Typical 1 time run was
// 0.00143885612488 sec

?>
