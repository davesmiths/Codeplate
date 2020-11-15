<?php


class RENDER {
    public function __call($name, $args) {
        if (isset($this->$name)) {
            call_user_func_array($this->$name, $args);
        }
        else {
            echo '';
        }
    }
}
class CAPTURE {
    public function __call($name, $args) {
        ob_start();
        $args[0]();
        $output = ob_get_contents();
        ob_end_clean();
        $this->$name = $output;
    }
}
class TEMPLATER {

    public function capture($templateFunc, $dataKey) {
        $templateObject = new CAPTURE;
        ob_start();
        $templateFunc = $templateFunc->bindTo($templateObject);
        $templateFunc();
        $output = ob_get_contents();
        ob_end_clean();
        if (isset($templateObject->$dataKey) === false) {
            $templateObject->$dataKey = $output;
        }
        return $templateObject;
    }

    public function render($templateFunc = null, $data = array(), $events = array(), $branch = null) {

        // Create the object the template refers to
        $templateObject = new RENDER;

        $currentBranch = isset($branch) ? $branch.'.' : '';

        // Loop through the $data array
        foreach ($data as $dataKey => $dataValue) {

            $branch = $currentBranch.$dataKey;
            //echo $branch.'<br />';

            if (is_array($dataValue)) {
                $templateObject->$dataKey = function($templateFunc) use ($data, $dataValue, $events, $branch) {

                    if (isset($events[$branch])) {
                        $events[$branch]();
                    }
                    $output = '';
                    foreach ($dataValue as $dataValueValue) {
                        $dataValueValue = array_merge($data, $dataValueValue);
                        $output .= $this->render($templateFunc, $dataValueValue, $events, $branch);
                    }
                    echo $output;
                };
            }
            else if (is_callable($dataValue)) {
                // A capture template
                $templateObject->$dataKey = function($templateFunc) use ($dataKey, $dataValue, $events, $branch) {
                    if (isset($events[$branch])) {
                        $events[$branch]();
                    }
                    $capturedData = $this->capture($templateFunc, $dataKey);
                    echo $this->render($dataValue, $capturedData, $events, $branch);
                };
            }
            else if ($dataValue === true) {
                $templateObject->$dataKey = function($templateFunc) use ($data, $events, $branch) {
                    if (isset($events[$branch])) {
                        $events[$branch]();
                    }
                    echo $this->render($templateFunc, $data, $events, $branch);
                };
            }
            else {
                $templateObject->$dataKey = function() use ($dataValue, $events, $branch) {
                    if (isset($events[$branch])) {
                        $events[$branch]();
                    }
                    echo $dataValue;
                };
            }
        }
        $templateFunc = $templateFunc->bindTo($templateObject);
        ob_start();
        $templateFunc();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}


// Templates
$profileTemplate = function() {?>

    <section class="profile">
        <h2><?php $this->image() ?><?php $this->name() ?></h2>
        <div><?php $this->biog() ?></div>
    </section>

<?php };

$quoteTemplate = function() {?>

    <blockquote>
        <?php $this->quotes() ?>
        <?php $this->boop() ?>
    </blockquote>

<?php };

$thingTemplate = function() {?>

    <p><strong>
        <?php $this->thing() ?>
    </strong></p>

<?php };

$template = function() {?>

    <h1><?php $this->title() ?><?php $this->test() ?></h1>


    <?php $this->quote(function() {?>
        <?php $this->quotes(function() {?>
            "Way to go!"
        <?php }) ?>
        <?php $this->boop(function() {?>
            "BOOOP!"
        <?php }) ?>
    <?php }) ?>

    <?php $this->thing(function() {?>
        "Thing to go!"
    <?php }) ?>


    <?php $this->profile(function() {?>
        <?php $this->name(function() {?>
            Dave
        <?php }) ?>
        <?php $this->image(function() {?>
            <img src="" alt="" />
        <?php }) ?>
        <?php $this->biog(function() {?>
            Blah
        <?php }) ?>
    <?php }) ?>

    <?php $this->profile(function() {?>
        <?php $this->name(function() {?>
            Diana
        <?php }) ?>
        <?php $this->image(function() {?>
            <img src="" alt="" />
        <?php }) ?>
        <?php $this->biog(function() {?>
            Blah blah
        <?php }) ?>
    <?php }) ?>

    <?php $this->people(function() {?>

        <?php $this->input(function() {?>
            Stuff here <?php $this->title(); ?>
        <?php }) ?></p>

        <p>output: <?php $this->output() ?></p>

        <p>Title: <?php $this->title() ?></p>
        <p>Name: <?php $this->name() ?></p>

        <?php $this->publicationsExist(function() {?>
        <ul>
        <?php }) ?>

        <?php $this->publications(function() {?>
            <li>Year: <?php $this->year() ?>,
                title: <?php $this->title() ?>, name: <?php $this->name() ?></li>
        <?php }) ?>

        <?php $this->publicationsExist(function() {?>
        </ul>
        <?php }) ?>

    <?php }) ?>

<?php };

// Data
$data = array(
    'title' => 'Great',
    'quote' => $quoteTemplate,
    'thing' => $thingTemplate,
    'profile' => $profileTemplate,
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

$templater = new TEMPLATER;
echo $templater->render($template, $data, array(
    'quote.quotes' => function() {
        echo '<span style="background:#9f9">XXXXXXXXX</span>';
    },
    'thing' => function() {
        echo '<span style="background:#9ff">XXXXXXXXX</span>';
    },
));


/*

Page
----
{{profile}}
{{image}}<img src="asd" alt="" />{{/image}}
{{name}}Dave Smith{{/name}}
{{biog}}Blah{{/blah}}
{{/profile}}


Template
--------
{{profile}}
<section class="profile">
    <h2>{{image}}{{name}}</h2>
    <div>{{biog}}</div>
</section>
{{/profile}}

*/


?>
