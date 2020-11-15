<?php


function op($s) {
    if (1==0) {
        echo $s;
    }
}
function op2($s) {
    if (1==0) {
        echo $s;
    }
}
function op3($s) {
    if (1==1) {
        echo $s;
    }
}

function getChildren($inputString) {

    $tags = array();
    $strings = array();

    if (preg_match_all("/\{\{(#|\/)?([a-zA-Z0-9_]+)\}\}/", $inputString, $outputArray, PREG_OFFSET_CAPTURE)) {
        $level = 0;
        $start = 0;
        foreach ($outputArray[1] as $k => $sen) { // sen (start end none)
//op($outputArray[0][$k][0][2]);
            if ($sen[0] === '') {
                if ($level === 0) {
                    array_push($tags, null);
                    array_push($strings, substr($inputString, $start, $outputArray[0][$k][1] - $start));
                    // Push the "contents" of the singleton
                    array_push($tags, $outputArray[2][$k][0]);
                    array_push($strings, '');
                    $start = $outputArray[0][$k][1] + strlen($outputArray[2][$k][0]) + 4;
                }
            }
            else if ($sen[0] === '#') {
                if ($level === 0) {
                    array_push($tags, null);
                    array_push($strings, substr($inputString, $start, $sen[1] - $start - 2));
                    $start = $sen[1] + strlen($outputArray[2][$k][0]) + 3;
                }
                $level += 1;
            }
            else if ($sen[0] === '/') {
                $level -= 1;
                if ($level === 0) {
                    array_push($tags, $outputArray[2][$k][0]);
                    array_push($strings, substr($inputString, $start, $sen[1] - $start - 2));
                    $start = $sen[1] + strlen($outputArray[2][$k][0]) + 3;
                }
            }
        }
        array_push($tags, null);
        array_push($strings, substr($inputString, $start));
//op('<pre>'.$tag.' ');
//op(htmlspecialchars(print_r($output,1)));
//op('</pre>');
    }
    else {
        $strings = array($inputString);
        $tags = array('');
    }
    return array('tags' => $tags, 'strings' => $strings);
}


class TEMPLATER {
//need to make recursive
    public function capture($template, $data = array(), $dataTag = null) {

        $children = getChildren($template);
        $childrenLength = count($children['tags']);

//op('<pre>');
//op(htmlspecialchars(print_r($children,1)));
//op('capture: datakey: '.$dataTag.'<br />');
//op('capture: template: '.htmlspecialchars($template).'<br />');
//op('capture: data: '.htmlspecialchars(print_r($data,1)).'<br />');
//op('-------------');
//op('</pre>');

        if ($childrenLength < 2) {
            // Means there are no elements in this template

            // Sometimes I just want to return the template
                // So do nothing

            // Sometimes I want to add the contents to the data, but only if $dataTag is set
            if (isset($dataTag)) {
                $data = $template;
            }
        }

        else {

            $template = '';

            for ($i = 0; $i < $childrenLength; $i++) {

                $childString = $children['strings'][$i];
                $childTag = $children['tags'][$i];

                if ($i % 2 === 0) {

                    // String is before or after a top-level element so just return it
                    $template .= $childString;

                }
                else {

                    // String is inside an element
                    if ($dataTag === $childTag) {

                        // The special case where the element is left untouched
                        $template .= '{{#'.$dataTag.'}}'.$childString.'{{/'.$dataTag.'}}';

                    }
                    else {

                        //$data[$childTag] = array();

                        $cap = $this->capture($childString, array(), $childTag);

//op('capture: cap: '.htmlspecialchars(print_r($cap,1)).'<br />');

                        if (count($cap['data']) === 1) {
                            $data[$childTag] = $cap['data'];
                        }
                        else {

                            // The regular case where the element is to be captured
                            $data[$childTag][] = $cap['data'];
                        }


                        // Don't want the following
                        //$template .= $cap['template'];

                        // useful: do when $children 0 or 1
                        // 'tag' => 'string',

                        // useful: do when $children 2 or more
                        // 'tag' => array(
                        //    array('tag'=>recursion),
                        //    array('tag'=>recursion),
                        //    array('tag'=>recursion),
                        //    array('tag'=>recursion),
                        // )

                        // useless
                        // 'tag' => array(
                        //    'string',
                        //    'string',
                        //    'string',
                        // ),
                    }
                }
            }
        }
        return array('template' => $template, 'data' => $data);
    }

    public function render($template = null, $data = array(), $events = array(), $branch = null) {
op2('<dl>');
op2('<dt style="font-weight:bold">render branch: '.$branch.'</dt><dd>');

op('<dl>');
        // Create the object the template refers to
        $currentBranch = isset($branch) ? $branch.'.' : '';

        $children = getChildren($template);
        $childrenLength = count($children['tags']);

op2('template getChildrenArray<pre>');
//op2('template:'.htmlspecialchars($template));
op2(htmlspecialchars(print_r($children,1)));
op2('</pre>');
        $template = '';
//op('template getChildrenArray<pre>');
//op(htmlspecialchars(print_r($children,1)));
//op('</pre>');

        for ($i = 0; $i < $childrenLength; $i++) {

            $childTag = $children['tags'][$i];
            $childString = $children['strings'][$i];
            if ($i % 2 === 0) {
                $template .= $childString;
            }
            else {


                // Loop through the $data array
                foreach ($data as $dataTag => $dataValue) {

                    if ($dataTag === $childTag) {

                        $branch = $currentBranch.$dataTag;
op('<dt style="font-weight:bold">data: '.$branch.'</dt><dd>');
//op2('<dt style="font-weight:bold">data: '.$branch.'</dt><dd>');
op3('<dt style="font-weight:bold">branch: '.$branch.'</dt><dd>');
//op($branch.'<br />');

                        if (is_array($dataValue)) {

op('data: array<br />');

                            if (isset($events[$branch])) {
                                $events[$branch]();
                            }

                            if ($childString !== '') {
                                foreach ($dataValue as $dataValueValue) {
                                    $dataValueValue = array_merge($data, $dataValueValue);
op('template: to render<pre>');
op(print_r($childString,1));
op('</pre>');
                                    $temp = $this->render($childString, $dataValueValue, $events, $branch);
                                    $template .= $temp;
op('template:rendered<pre>');
op($temp);
op('</pre>template:renderedEnd<br />');
                                }
                            }
                            else {
op('template: tag string length 0');
                                //$template .= '';
                            }

                        }








                        else if (is_callable($dataValue)) {
op('data: function<br />');
                            // A capture template
                            if (isset($events[$branch])) {
                                $events[$branch]();
                            }
//op('data: template to capture<pre>');
//op(htmlspecialchars($childString));
//op('</pre>');
                            $captured = $this->capture($childString, array(), $dataTag);
                            // Wrap everything in the current tag, so it can be rendered
                            $captured['data'] = array(
                                $dataTag => array($captured['data']),
                            );
//op('data: captured<pre>');
//op(htmlspecialchars(print_r($captured,1)));
//op('</pre>');
                            $captured['data'] = array_merge($data, $captured['data']);
//op('data: captured<pre>');
//op(htmlspecialchars(print_r($captured,1)));
//op('</pre>');
                            $closured = $dataValue($captured);
op('data: captured<pre>');
op($closured === null ? 'null' : print_r($closured,1));
op('</pre>');

                            // if return is null
                            if (isset($closured)) {
                                // if string returned
                                if (is_string($closured)) {
                                    $template .= $closured;
                                }
                                // if array returned, must be array('template'=>'','data'=>array())
                                else {
                                    $template .= $this->render($closured['template'], $closured['data'], $events, $branch);
                                }
                            }
                            // else render the captured
                            else {
                                //$captured['data'][$dataTag] = $captured['data'];
//op('data: captured<pre>');
//op(htmlspecialchars(print_r($captured,1)));
//op('</pre>');
                                $template .= $this->render($captured['template'], $captured['data'], $events, $branch);
                            }
//op('data: temp<pre>');
//op(htmlspecialchars(print_r($captured,1)));
//op('</pre>');
                        }

                        else if ($dataValue === true) {
op('data: boolean === true<br />');

                            if (isset($events[$branch])) {
                                $events[$branch]();
                            }

op2('data: boolean: childString<br />'.htmlspecialchars($childString).'<br /><br />');

                            if ($childString !== '') {
op('data: boolean: childString<br />');
//op(htmlspecialchars($childString));
                                $ren = $this->render($childString, $data, $events, $branch);
                                $template .= $ren;
op2(htmlspecialchars($ren));
                            }
                            else {
op('no call');
                            }

                        }
                        else {
op2('asdasdasdasd');
op('data: string<br />');
op('template: tag replaced<br />');
                            $template .= $dataValue;

                        }
                    }
                }
            }
        }
op('</dd>');
op('</dl>');
op2('</dd>');
op2('</dl>');
        return $template;

    }

}


// Template
ob_start();
include 'template.html';
$template = ob_get_contents();
ob_end_clean();

// Data
include 'data.php';


$templater = new TEMPLATER;
$output = $templater->render($template, $data, array(
    'quote.quotes' => function() {
        echo '<span style="background:#9f9">XXXXXXXXX</span>';
    },
    'thing' => function() {
        echo '<span style="background:#9ff">XXXXXXXXX</span>';
    },
));
op('<hr />');
echo $output;
?>


<?php
// SMC
// course eire
/*
coursera


*/
?>
