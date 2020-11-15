<?php
$data = array(
    'num' => function($o) {
        //echo '<br />++++++++++++++<pre>';
        //echo htmlspecialchars($o['template']);
        //    echo '<br />++++++++++++++<pre>';
        //    echo htmlspecialchars(print_r($o['data'],1));
        //echo '</pre>';

        //return '[hello]';
        //return array(
        //    'template' => '[hello]',
        //    'data' => array(
        //        'title' => 'Hello World 2!',
        //    ),
        //);
        //$o['data']['hello'] = 'Hello World 3!';
        //$o['template'] = '[hello]';
        //return $o;
        $o['data']['num'] = $o['data']['other'][0];
        //$o['template'] = '[hello]';
        return $o;
    },
    'hello' => 'Hello World 1!',
    'poopExists' => true,
    //'poop' => 'Boooooop',
    'list' => function($capture) {
    },
    'title' => 'Great',
    'title' => function($capture, $render) {
        //echo '<br />++++++++++++++<pre>';
        //echo htmlspecialchars(print_r($capture,1));
        //var_dump($capture);
        //echo '</pre>';
        $capture['template'] = $render($capture);
        echo '<br />++++++++++++++<pre>';
        echo htmlspecialchars(print_r($capture,1));
        echo '</pre>';
        return $capture;
    },
    'quote' => function(){},
    'thing' => function(){},
    'profiles' => function(){},
    'figure134875' => array(
        'image' => 'image.jpg',
        'description' => 'Fab image',
    ),
    'people' => array(
        array(
            'title' => 'Overwrite',
            'name' => 'Dave',
            'publicationsExist' => true,
            'publications' => array(
                array(
                    'year' => '2011',
                ),
                array(
                    'year' => '2012',
                ),
                array(
                    'year' => '2013',
                ),
            ),
        ),
        array(
            'name' => 'Diana',
            'publicationsExist' => true,
            'publications' => array(
                array(
                    'year' => '2012',
                ),
                array(
                    'year' => '2013',
                ),
            ),
        ),
        array(
            'name' => 'Isla',
        ),
        array(
            'name' => 'Linnea',
        ),
        array(
            'name' => 'Gideon',
        ),
    ),
);
//$data = array(
//    'title' => 'Default Title',
//    'das' => array(
//        array(
//            'title' => 'Overwrite',
//            'das' => 'GO',
//            //'name' => 'Dave',
//            //'publicationsExist' => true,
//            //'publications' => array(
//            //    array(
//            //        'year' => '2011',
//            //    ),
//            //    array(
//            //        'year' => '2012',
//            //    ),
//            //    array(
//            //        'year' => '2013',
//            //    ),
//            //),
//        ),
//        array(
//            //'title' => 'Overwrite',
//            //'name' => 'Diana',
//            //'publicationsExist' => true,
//            //'publications' => array(
//            //    array(
//            //        'year' => '2012',
//            //    ),
//            //    array(
//            //        'year' => '2013',
//            //    ),
//            //),
//        ),
//    ),
//);
?>
