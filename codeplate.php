<?php



class CODEPLATE {


    // Defaults open and close tags
    private $tagOpen = '[';
    private $tagClose = ']';

    private $startTagChar = '#';
    private $endTagChar = '/';


//  CONSTRUCTOR
    function __construct($o = array()) {


        // Set the open and close tags
        if (isset($o['tagOpen'])) {
            $this->tagOpen = $o['tagOpen'];
        }
        $this->tagOpenEscaped = preg_quote($this->tagOpen, '/');


        if (isset($o['tagClose'])) {
            $this->tagClose = $o['tagClose'];
        }
        $this->tagCloseEscaped = preg_quote($this->tagClose, '/');


        if (isset($o['startTagChar'])) {
            $this->startTagChar = $o['startTagChar'];
        }
        $this->startTagCharEscaped = preg_quote($this->startTagChar, '/');


        if (isset($o['endTagChar'])) {
            $this->endTagChar = $o['endTagChar'];
        }
        $this->endTagCharEscaped = preg_quote($this->endTagChar, '/');


        $this->startTagOpen = $this->tagOpen.$this->startTagChar;
        $this->endTagOpen = $this->tagOpen.$this->endTagChar;


        $this->regexTags = "".
                        "/".
                            $this->tagOpenEscaped.                  //  [
                            "(".                                    //      Capture 0 or 1 of
                                $this->startTagCharEscaped.         //      #
                                "|".                                //      or
                                $this->endTagCharEscaped.           //      /
                            ")?".
                            "([a-zA-Z0-9_]+)".                      //      Capture the tagname
                            '([^'.$this->tagCloseEscaped.'\/]*)'.   //      Capture any attributes, requires that attributes do not contain ] or /
                            '(\/?)'.
                            $this->tagCloseEscaped.                 //  ]
                        "/";

    }





//  RENDER
//  Returns a string
    public function render($c) {

        $data = $c['data'];
        $string_parentnode = $c['string'];
        $events = isset($c['events']) ? $c['events'] : array();
        $dataPath = isset($c['dataPath']) ? $c['dataPath'] : NULL;


        // Update the data path
        $currentDataPath = isset($dataPath) ? $dataPath.'.' : '';


        // Get the children elements of the string
        $children = $this->getChildren($string_parentnode);


        // Inititiate the string that will replace the string
        $stringReplace = '';


        //  Loop through the children and do stuff
        foreach ($children as $child) {


            if ($child['type'] === 'text') {

                $stringReplace .= $child['string'];

            }

            else if ($child['type'] === 'element') {


                if ($child['tagname'] === 'template' && $child['string'] !== NULL) {
                    $stringReplace = $this->render($this->capture($c, false));
                    break;
                }


                // CHILD TAGNAME NOT IN DATA
                // If the element tagname has not been set in the data
                // do nothing, i.e., replace with itself
                if (!isset($data[$child['tagname']])) {

                    if ($child['string'] === NULL) {
                        $stringReplace .= $this->tagOpen.$child['tagname'].$this->tagClose;
                    }
                    else {
                        $stringReplace .= $this->startTagOpen.$child['tagname'].$this->tagClose;
                        $stringReplace .= $child['string'];
                        $stringReplace .= $this->endTagOpen.$child['tagname'].$this->tagClose;
                    }
                }


                // CHILD TAGNAME IN DATA
                // The element tagname is in the data
                // There are several possibilities
                else {



                    $dataValue = $data[$child['tagname']];

                    // CHILD TAGNAME == CONTENT
                    if ($child['tagname'] === 'content') {

                        $stringReplace .= $dataValue;

                    }
                    else {


                        $atts = $this->parse_atts($child['atts']);
                        $dataPath = $currentDataPath.$child['tagname'];



// echo '<pre>';
// var_export($dataValue);
// echo '</pre>';



                        // data[CHILD TAGNAME] => FUNCTION
                        // Data item is a function
                        if (is_callable($dataValue)) {
// echo 'c-';

                            $dataFunction = $dataValue;

                            // A capture string
                            if (isset($events[$dataPath])) {
                                $events[$dataPath]();
                            }

                            // Bind the data function to the Codeplate instance
                            $dataFunction = $dataFunction->bindTo($this);
// echo '<pre>';
// var_export($atts);
// echo '</pre>';
// echo '<pre>';
// var_export($data);
// echo '</pre>';
// Instead merging attributes into the data, to add them as a separate key $c['atts']
// I can be sure there will only be one of each
// Whereas in the data I can't
                            $data = array_merge($data, $atts);
// echo '<pre style="background:#dee;">';
// var_export($data);
// echo '</pre>';

                            // Run the data function
                            $result = $dataFunction(array(
                                'string' => $child['string'],
                                'data' => $data,
                                'events' => $events,
                                'dataPath' => $dataPath,
                            ));

// echo '<pre>';
// var_export($result);
// echo '</pre>';
                            // if the result is set to something other than NULL
                            if ($result !== NULL) {
                                // if string returned, then it must be the string
                                if (is_string($result)) {
                                    $stringReplace .= $this->render(array(
                                        'string' => $result,
                                        'data' => $data,
                                        'events' => $events,
                                        'dataPath' => $dataPath
                                    ));
                                }
                                else {
                                    $stringReplace .= $this->render(array(
                                        'string' => $result['string'],
                                        'data' => $result['data'],
                                        'events' => $events,
                                        'dataPath' => $dataPath
                                    ));
                                }
                            }
                        }




                        else if (isset($dataValue[0])) {
// echo 'a-';

                            // data[CHILD TAGNAME] => STRING || ARRAY
                            // Normalise this to an array
                            // if (is_string($dataValue)) {
    // echo '<pre>';
    // var_export(is_string($dataValue)).'<br>';
    // var_export(!isset($dataValue[0])).'<br>';
    // var_export(!isset($dataValue[0]['content'])).'<br>';
    // var_export(count($dataValue[0])).'<br>';
    // var_export($dataValue);
    // echo '</pre>';
// $t = 'bob';
// var_export($t[0]); => 'b'
// var_export($t[0][0]); => 'b'
// $t = array(array('content'=>'bob'));
// var_export($t[0]);
// var_export($t[0][0]);
// $t = array(array('name'=>'bob'));
// var_export($t[0]);
// var_export($t[0][0]);


                            // if (gettype($dataValue) === 'string') {
                            // if (is_string($dataValue)) {
                            if (isset($dataValue[0][0])) {
    // var_export('===='.$dataValue.'<br>');
                                $dataValue = array(
                                    array(
                                        'content' => $dataValue
                                    )
                                );
                            }
    // echo '<pre>';
    // var_export($dataValue);
    // echo '</pre>';
                        // else if (is_array($dataValue)) {

                            $array = $dataValue;


                            $array[0] = array_merge($array[0], $atts);
    // echo '<pre>';
    // var_export($array);
    // echo '</pre>';

                            if (isset($events[$dataPath])) {
                                $events[$dataPath]();
                            }


                            // [#tagname][/tagname]
                            //
                            if ($child['string'] !== NULL && $child['string'] !== '') {

                                $arrayLength = count($array);
                                $arrayLengthMinus1 = $arrayLength - 1;

                                $pre = true;
                                $post = false;

                                for ($j = 0; $j < $arrayLength; $j++) {

                                    $arrayItem =& $array[$j];

                                    if ($j === 1) {
                                        $pre = false;
                                    }
                                    else if ($j === $arrayLengthMinus1) {
                                        $post = true;
                                    }

                                    $arrayItem['pre'] = $pre;
                                    $arrayItem['post'] = $post;
                                    $arrayItem = array_merge($data, $arrayItem);

                                    $temp = $this->render(array(
                                        'string' => $child['string'],
                                        'data' => $arrayItem,
                                        'events' => $events,
                                        'dataPath' => $dataPath
                                    ));

                                    $stringReplace .= $temp;

                                }

                            }

                            // [tagname]
                            // Therefore loop over array and output any content data
                            else {
                                foreach ($array as $arrayItem) {
                                    $stringReplace .= isset($arrayItem['content']) ? $arrayItem['content'] : '';
                                }
                            }
                        }



                        // data[CHILD TAGNAME] => TRUE
                        // Data item is true
                        else if ($dataValue === true) {
// echo 't-';
                            if (isset($events[$dataPath])) {
                                $events[$dataPath]();
                            }

                            if ($child['string'] !== '') {
                                $ren = $this->render(array(
                                    'string' => $child['string'],
                                    'data' => $data,
                                    'events' => $events,
                                    'dataPath' => $dataPath
                                ));
                                $stringReplace .= $ren;
                            }
                            else {
                            }

                        }


                        // data[CHILD TAGNAME] => FALSE
                        else if ($dataValue === false) {
// echo 'f-';
                            // Do nothing
                        }



                    }
                }
            }
        }
// echo '</dd>';
// echo '</dl>';
        return $stringReplace;

    }




//  CAPTURE
//  Returns an array of string and data
    public function capture($c, $merge = true) {

        $children = $this->getChildren($c['string']);

        $stringReplace = '';



        $data = array();
        $data['content'] = '';

        foreach ($children as $child) {


            if ('text' === $child['type']) {

                $data['content'] .= $child['string'];

            }
            else if ('element' === $child['type']) {


                if ($child['tagname'] === 'template') {
                    $stringReplace = $child['string'];
                }
                else {

                    $atts = $this->parse_atts($child['atts']);

                    $c['string'] = $child['string'];

                    $capture = $this->capture($c, false);

                    if (!isset($data[$child['tagname']])) {
                        $data[$child['tagname']] = array();
                    }
                    $data[$child['tagname']][] = array_merge($atts, $capture['data']);

                }
            }
        }
        $c['string'] = $stringReplace;
        if (isset($c['data'])) {
            $data = array_merge($c['data'], $data);
        }
        $c['data'] = $data;
        return $c;

    }






    private function parse_atts($text) {
       $atts = array();
       $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
       $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
       if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
           foreach ($match as $m) {
               if (!empty($m[1]))
                   $atts[strtolower($m[1])] = array(array('content' => stripcslashes($m[2])));
               elseif (!empty($m[3]))
                   $atts[strtolower($m[3])] = array(array('content' => stripcslashes($m[4])));
               elseif (!empty($m[5]))
                   $atts[strtolower($m[5])] = array(array('content' => stripcslashes($m[6])));
               elseif (isset($m[7]) && strlen($m[7]))
                   $atts[strtolower($m[7])] = true;
                //    $atts[] = stripcslashes($m[7]);
               elseif (isset($m[8]))
                   $atts[strtolower($m[8])] = true;
                //    $atts[] = stripcslashes($m[8]);
           }
       }
       else {
        //    $atts = ltrim($text);
       }
       return $atts;
    }



    // rationale is that the string is the source and therefore
    // the action is based on its contents and not the data array
    // however this may be less good than basing it on the data
    private function getChildren($string) {

        $output = array();

        if (preg_match_all($this->regexTags, $string, $matches, PREG_OFFSET_CAPTURE)) {
// echo '<pre>';
// print_r($matches);
// echo '</pre>';
// $matches[0] is the full capture: [#tag] or [/tag] or [tag]
// $matches[1] is the tag type capture: # (start) or / (end) or '' (void)
// $matches[2] is the tag name capture: 'tagname'
// $matches[0][$i][0] is the match string
// $matches[0][$i][1] is the match offset
            $level = 0;
            $start = 0;
            $atts = '';

            foreach ($matches[1] as $i => $match) {

                // VOID ELEMENT
                // If a void element {{tag}} push the string and then the "contents" of the void element
                if ($match[0] == '') {
                    if ($level == 0) {
                        // Push the string before the void element
                        array_push($output, array(
                            'type' => 'text',
                            'string' => substr($string, $start, $matches[0][$i][1] - $start),
                            'tagname' => NULL,
                        ));
                        // Push the "contents" of the void element
                        array_push($output, array(
                            'type' => 'element',
                            'string' => NULL,
                            'tagname' => $matches[2][$i][0],
                            'atts' => $matches[3][$i][0],
                        ));
                        // Set the start point for the next substr call on string
                        $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
                    }
                }

                // OPENING
                // If an opening tag push the preceding string
                else if ($match[0] == '#') {
                    if ($level == 0) {
                        array_push($output, array(
                            'type' => 'text',
                            'string' => substr($string, $start, $matches[0][$i][1] - $start),
                            'tagname' => $matches[2][$i][0], // Required to ensure I have an option to keep or remove tags when they don't get a match
                        ));
                        $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
                        $atts = $matches[3][$i][0];
                    }
                    $level += 1;
                }

                // CLOSING
                // If a closing tag push the element's contents
                else if ($match[0] == '/') {
                    $level -= 1;
                    if ($level == 0) {
                        array_push($output, array(
                            'type' => 'element',
                            'string' => substr($string, $start, $matches[0][$i][1] - $start),
                            'tagname' => $matches[2][$i][0],
                            'atts' => $atts,
                        ));
                        $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
                        $atts = '';
                    }
                }
            }
            array_push($output, array(
                'type' => 'text',
                'string' => substr($string, $start),
                'tagname' => NULL,
            ));
        }
        else {
            array_push($output, array(
                'type' => 'text',
                'string' => $string,
                'tagname' => NULL,
            ));
        }

        return $output;

    }


}

?>
