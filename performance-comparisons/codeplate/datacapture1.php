<?php


// STRING
$string = '
[#publications]
    [publication name="Good one" year="2015" type="Magazine"]
    [publication name="Good two" year="2013" type="Paper"]
    [publication name="Good three" year="2012" type="Magazine"]
    [publication name="Good four" year="2014" type="Journal"]
    [publication name="Good five" year="2009" type="Journal"]
    [publication name="Good six" year="2012" type="Book"]
    [publication name="Good seven" year="2015" type="Magazine"]
[/publications]
';
// $string = '
// [#publications]
//     [publication name="Good one" year="2015" type="Magazine"]
// [/publications]
// ';

// DATA
$data = array(
    'publications' => function($c) {
        $c = $this->capture($c);
// echo '<pre>';
// var_export($c);
// echo '</pre>';
        $s = '<ul>';
        foreach ($c['data']['publication'] as $pub) {
            $s .= '<li><strong>'.$pub['name'][0]['content'].'</strong><br />'.$pub['year'][0]['content'].'<br />'.$pub['type'][0]['content'].'</li>';
        }
        $s .= '</ul>';
        return $s;
    }
);

// CODEPLATE
include_once './../../codeplate.php';
$codeplate = new CODEPLATE;

// Microtime start
$microtimeRepeats = 5000;
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
echo ($microtimeEnd-$microtimeStart)/$microtimeRepeats . " sec";

//0.0005794852066 sec

?>
