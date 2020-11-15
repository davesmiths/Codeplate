<?php


// STRING
ob_start();
include 'string.php';
$string = ob_get_contents();
ob_end_clean();


// DATA
include 'data.php';


// EVENTS
$events = array(
   'quote.quote' => function() {
       echo '<span style="background:#9f9">quote.quote XXXXXXXXX</span>';
   },
   'thing' => function() {
       echo '<span style="background:#9ff">thing XXXXXXXXX</span>';
   },
   'first' => function() {
       echo '<span style="background:#9ff">first XXXXXXXXX</span>';
   },
);


// CODEPLATE
include_once 'codeplate.php';
// $codeplate = new CODEPLATE(array(
//     'tagOpen' => '{{',
//     'tagClose' => '}}',
// ));
$codeplate = new CODEPLATE;
$output = $codeplate->render(array(
    'string' => $string,
    'data' => $data,
    'events' => $events,
));

echo $output;

?>
