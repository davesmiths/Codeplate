<?php


// STRING
$string = '
[publications]
    [publication name="Good one" year="2015" type="Magazine"]
    [publication name="Good two" year="2013" type="Paper"]
    [publication name="Good three" year="2012" type="Magazine"]
    [publication name="Good four" year="2014" type="Journal"]
    [publication name="Good five" year="2009" type="Journal"]
    [publication name="Good six" year="2012" type="Book"]
    [publication name="Good seven" year="2015" type="Magazine"]
[/publications]
';


include_once 'shortcodes.php';

add_shortcode('publications', function($atts, $s = NULL) {
    return '<ul>'.do_shortcode($s).'</ul>';
});
add_shortcode('publication', function($atts, $s = NULL) {
    extract(shortcode_atts(array(
        'name' => 'name',
        'year' => 'year',
        'type' => 'type',
    ), $atts));
    return '<li><strong>'.$name.'</strong>'.'<br />'.$year.'<br />'.$type.'</li>';
});



// Microtime start
$microtimeRepeats = 5000;
$microtimeRepeats = 1;
$microtimeCounter = 0;
$microtimeStart = microtime(true);
for ($microtimeCounter = 0; $microtimeCounter < $microtimeRepeats; $microtimeCounter++) {

    do_shortcode($string);

}
// Microtime end
$microtimeEnd = microtime(true);
echo ($microtimeEnd-$microtimeStart)/$microtimeRepeats . " sec";

//0.000455514383316 sec
?>
