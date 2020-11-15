<?php


class DATA {
    public function __call($name, $args) {
        if (isset($this->$name)) {
            if (is_callable($this->$name)) {
                call_user_func_array($this->$name, $args);
            }
            else {
                echo $this->$name;
            }
        }
        else {
            echo '';
        }
    }
    public function __get($name) {
        return '';
    }
}
class PHPTemplater {
    public function capture($template = null) {

    }
    public function render($template = null, $data = array()) {
        $d = new DATA;
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $kk = $k.'USED';
                $usedFunc = isset($data[$kk]) ? $data[$kk] : function() {};
                $d->$k = function($tpl) use ($v,$data,$usedFunc) {
                    $usedFunc();
                    $output = '';
                    foreach ($v as $vv) {
                        $vv = array_merge($data,$vv);
                        $output .= $this->render($tpl, $vv);
                    }
                    echo $output;
                };
            }
            //else if (is_callable($v)) {
            //    $d->$k = function($tpl) use ($v,$data) {
            //        echo $v($this,$tpl,$data);
            //    };
            //}
            else if (is_callable($v)) {
                $d->$k = function($tpl) use ($v,$data) {
                    //$data = $this->capture($tpl);
                    echo $v($this->render($tpl, $data));
                };
            }
            else if ($v === true) {
                $d->$k = function($tpl) use ($data) {
                    echo $this->render($tpl, $data);
                };
            }
            else {
                //$kk = $k.'USED';
                //$usedFunc = isset($data[$kk]) ? $data[$kk] : function() {};
                //$usedFunc();
                $d->$k = $v;
            }
        }
        $template = $template->bindTo($d);
        ob_start();
        $template();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

$phpTemplater = new PHPTemplater;

$profileTemplate = function() {?>
    <section class="profile">
        <h2><?php $this->image() ?><?php $this->name() ?></h2>
        <div><?php $this->biog() ?></div>
    </section>
<?php };

// Data
$data = array(
    'title' => function() { ?>
        <em><?php $this->name() ?></em>
    <?php },
    //'titleUSED' => function() {
    //    echo 'add JS and CSS';
    //},
    'test' => 'ad thing',
    'thing' => 'Unused',
    //'profile' => $profileTemplate,
    //'people' => array(
    //    array(
    //        'title' => 'Overwrite',
    //        'name' => 'Dave',
    //        'publicationsExist' => true,
    //        'publications' => array(
    //            array(
    //                'year' => '2011',
    //            ),
    //            array(
    //                'year' => '2012',
    //            ),
    //            array(
    //                'year' => '2013',
    //            ),
    //        ),
    //    ),
    //    array(
    //        'name' => 'Diana',
    //        'publicationsExist' => true,
    //        'publications' => array(
    //            array(
    //                'year' => '2012',
    //            ),
    //            array(
    //                'year' => '2013',
    //            ),
    //        ),
    //    ),
    //    array(
    //        'name' => 'Isla',
    //    ),
    //    array(
    //        'name' => 'Linnea',
    //    ),
    //    array(
    //        'name' => 'Gideon',
    //    ),
    //),
);

// Template
$template = function() {?>

    <?php $this->title(function() {?>
        My name is Bob
    <?php }) ?>

<?php };

$template2 = function() {?>

    <h1><?php $this->title() ?><?php $this->test() ?></h1>

    <?php $this->profile(function() {?>
        <?php $this->name(function() {?>Dave<?php }) ?>
        <?php $this->image(function() {?><img src="" alt="" /><?php }) ?>
        <?php $this->biog(function() {?>Blah<?php }) ?>
    <?php }) ?></p>

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



echo $phpTemplater->render($template, $data);


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
