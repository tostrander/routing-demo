<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

//Require the autoload file
require_once('vendor/autoload.php');

//Create an instance of the Base class
$f3 = Base::instance();

//Set debug level
$f3->set('DEBUG', 3);

//Define a default route
$f3->route('GET /', function() {
    echo '<h1>Routing Demo</h1>';
}
);

//Define a page1 route
$f3->route('GET /page1', function() {
    echo '<h1>This is page 1</h1>';
}
);

//Define a page1 route
$f3->route('GET /page1/subpage-a', function() {
    echo '<h1>This is page 1, Subpage A</h1>';
}
);

//Define a jewelry route
$f3->route('GET /jewelry/rings/toe-rings', function() {
    //echo '<h1>Buy a toe ring today!</h1>';
    $template = new Template();
    echo $template->render('views/toe-rings.html');
}
);

//Define a route using parameters
$f3->route('GET /hello/@name',
    function($f3, $params) {
        //$name = $params['name'];
        //echo "<h1>Hello, $name</h1>";

        $f3->set('name', $params['name']);
        $template = new Template();
        echo $template->render('views/hello.html');

    }
);

$f3->route('GET /hi/@first/@last',
    function($f3, $params) {

        $f3->set('first', $params['first']);
        $f3->set('last', $params['last']);
        $f3->set('message', 'Hi');

        $_SESSION['first'] = $f3->get('first');
        $_SESSION['last'] = $f3->get('last');

        $template = new Template();
        echo $template->render('views/hi.html');
    }
);

$f3->route('GET /hi-again',
    function($f3, $params) {
        echo 'Hi again, '.$_SESSION['first'];
    }
);
//Define a route using parameters
$f3->route('GET /language/@lang',
    function($f3, $params) {
        switch($params['lang']){
            case 'swahili':
                echo 'Jumbo!'; break;
            case 'spanish':
                echo 'Hola!'; break;
            case 'russian':
                echo 'Privet!'; break;
            case 'farsi':
                echo 'Salam!'; break;
            //Reroute to another page
            case 'french':
                $f3->reroute('/');
            //404 error
            default:
                $f3->error(404);
        }
    }
);




//Run fat free
$f3->run();
