<?php

$data = array();
$data = array(
	'first' => 'fred',
	'list' => file_get_contents('list.component.php'),
	'first' => function($c){
		// $c['data']['t'][0]['content'];
		// $this->get($c, 't'); // not implemented
		$t = isset($c['data']['t'][0]['content']) ? $c['data']['t'][0]['content'] : '';
		$s = '<li><strong>'.$t.'</strong></li>';
		return $s;
	},
	'member' => array(
		array(
			 'first' => array(
				 array(
					 'content' => 'Dave'
				 )
			 ),
			 'last' => array(
				 array(
					 'content' => 'Smith'
				 )
			 )
		),
		array(
			 'first' => array(
				 array(
					 'content' => 'Dave'
				 )
			 ),
			 'last' => array(
				 array(
					 'content' => 'Smurf'
				 )
			 )
		),
		array(
			 'first' => 'Diana',
			 'last' => 'Kaiser',
		),
		array(
			'first' => 'Isla',
			'middle' => 'Grace',

			// Capture is called because this function exists
			// This function is called after the capture
			// Render is called after this function
			'last' => function($c) {

				// $string = $c['string'];
                // Contents of [#last][/last]

				// $array = $c['data'];
                // Current flattened upstream data

				// $array = $c->events;
                // Events array

				// $string = $this->render($c);
                // Render

				// $array = $this->capture($c['string']);
                // $array['data'] is captured data only

				// $array = $this->capture($c['string'], $c['data']);
                // $array['data'] is any captured data merged with $c['data']

				// $this->setChars('{','}','#','/');


				$d = $this->capture($c);
// echo '<pre style="background:#dee;">';
// //echo $c['string'];
// var_export($c);
// echo '</pre>';
                // $d->data['content']; // The contents of [#last][/last] captured
				// $d->string = '[test]';
				// $d->data = array(
				// 	'test' => 'pppp'
				// );
				// $d->string = $this->render($d);

				// I believe this function needs to return what would be expected as regular data
				return $c['string'];

			},
			//'last' => 'Smith',
		),
		// array(
		//	 'first' => 'Linnéa',
		//	 'middle' => 'Skye',
		//	 'last' => function() {
		//		 return '[middle] Smith';
		//	 },
		//	 //'last' => 'Smith',
		// ),
	),

);
// $data = array(
//	 'num' => function($o) {
//		 //echo '<br />++++++++++++++<pre>';
//		 //echo htmlspecialchars($o['template']);
//		 //	echo '<br />++++++++++++++<pre>';
//		 //	echo htmlspecialchars(print_r($o['data'],1));
//		 //echo '</pre>';
//
//		 //return '[hello]';
//		 //return array(
//		 //	'template' => '[hello]',
//		 //	'data' => array(
//		 //		'title' => 'Hello World 2!',
//		 //	),
//		 //);
//		 //$o['data']['hello'] = 'Hello World 3!';
//		 //$o['template'] = '[hello]';
//		 //return $o;
//		 $o['data']['num'] = $o['data']['other'][0];
//		 //$o['template'] = '[hello]';
//		 return $o;
//	 },
//	 'hello' => 'Hello World 1!',
//	 'poopExists' => true,
//	 //'poop' => 'Boooooop',
//	 'list' => function($capture) {
//	 },
//	 'title' => 'Great',
//	 'title' => function($capture, $render) {
//		 //echo '<br />++++++++++++++<pre>';
//		 //echo htmlspecialchars(print_r($capture,1));
//		 //var_dump($capture);
//		 //echo '</pre>';
//		 $capture['template'] = $render($capture);
//		 echo '<br />++++++++++++++<pre>';
//		 echo htmlspecialchars(print_r($capture,1));
//		 echo '</pre>';
//		 return $capture;
//	 },
//	 'quote' => function(){},
//	 'thing' => function(){},
//	 'profiles' => function(){},
//	 'figure134875' => array(
//		 'image' => 'image.jpg',
//		 'description' => 'Fab image',
//	 ),
//	 'people' => array(
//		 array(
//			 'title' => 'Overwrite',
//			 'name' => 'Dave',
//			 'publicationsExist' => true,
//			 'publications' => array(
//				 array(
//					 'year' => '2011',
//				 ),
//				 array(
//					 'year' => '2012',
//				 ),
//				 array(
//					 'year' => '2013',
//				 ),
//			 ),
//		 ),
//		 array(
//			 'name' => 'Diana',
//			 'publicationsExist' => true,
//			 'publications' => array(
//				 array(
//					 'year' => '2012',
//				 ),
//				 array(
//					 'year' => '2013',
//				 ),
//			 ),
//		 ),
//		 array(
//			 'name' => 'Isla',
//		 ),
//		 array(
//			 'name' => 'Linnea',
//		 ),
//		 array(
//			 'name' => 'Gideon',
//		 ),
//	 ),
// );
//$data = array(
//	'title' => 'Default Title',
//	'das' => array(
//		array(
//			'title' => 'Overwrite',
//			'das' => 'GO',
//			//'name' => 'Dave',
//			//'publicationsExist' => true,
//			//'publications' => array(
//			//	array(
//			//		'year' => '2011',
//			//	),
//			//	array(
//			//		'year' => '2012',
//			//	),
//			//	array(
//			//		'year' => '2013',
//			//	),
//			//),
//		),
//		array(
//			//'title' => 'Overwrite',
//			//'name' => 'Diana',
//			//'publicationsExist' => true,
//			//'publications' => array(
//			//	array(
//			//		'year' => '2012',
//			//	),
//			//	array(
//			//		'year' => '2013',
//			//	),
//			//),
//		),
//	),
//);
?>
