<?php
/*
 * Version Control:
 * 1. Create GitHub repo
 * 2. Copy URL
 * 3. Clone site in terminal window
 * 5. Commit each step to GitHub
 *    5a. git add index.html
 *    5b. git commit -m "..."
 *    5c. git push origin master
 *
 * Setup:
 * 1. Create project directory
 * 2. Add composer.json
 * 3. Install composer from terminal window
 * 4. Add index page
 * 5. Include vendor/autoload.php
 *
 */

session_start();

error_reporting(E-ALL);
ini_set('display_errors', '1');

//Require autoload
require_once('vendor/autoload.php');

//Create an instance of the Base class
$f3 = Base::instance();

//1. Set debug level
//Comment out routes and set to 0, 1, 2, 3
//0 on a live site, 3 for dev
$f3->set('DEBUG', 3);

//Define a default route
$f3->route('GET /',
    function() {
        echo '<h3>Hello world</h3>';
        echo '<a href="./page1">Go to Page 1</a>';
    });

//2. Define an additional route
//Add .htaccess (config file for Apache)
//All routes must be registered in project folder, index.php
//Try It:  Create a page 2
$f3->route('GET /page1',
    function() {
        echo 'This is page 1';
    });

//3. Routes can have as many levels as we want
$f3->route('GET /page1/subpage-a',
    function() {
        echo 'This is page 1, Subpage A';
    });

//4. Use a parameter
//@first is a "token"
//You can pass parameters using @notation, then retrieve values using
//built in $params array.
//Note that anonymous function now accepts two parameters:
//the instance of the base and a $params array
//In http://tostrander.greenriverdev.com/328/routing-demo/bob, there is no
//route for bob, so f3 looks for a route that accepts a parameter
$f3->route('GET /@first',
    function($f3, $params) {

        echo '<pre>';
        print_r($params);
        echo '</pre>';
        echo 'Hi, '.$params['first'];
    });


//5. Rerouting and 404 error
$f3->route('GET /language/@lang',
    function($f3, $params) {
        switch($params['lang']) {
            case 'swahili':
                echo 'Jumbo!'; break;
            case 'spanish':
                echo 'Hola!'; break;
            case 'farsi':
                echo 'Salam!'; break;
            case 'english':
                //Reroute to project home page
                $f3->reroute('/');
            default:
                $f3->error(404);
        }
    });

//6. Define a global variable and display data in a TEMPLATE
//Fat-free stores variables in the router that can then be passed
//around your program. Values are stored in key/value pairs.
//Primarily used with Fat-free's templating language.
$f3->route('GET /howdy/@first',
    function($f3, $params) {

        $f3->set('firstName', $params['first']);
        $f3->set('message', 'Howdy');

        //$view = new View();
        //echo $view->render('pages/howdy.html');

        //load a page using a Template
        $template = new Template();
        echo $template->render('views/howdy.html');
    });

//7. Multiple parameters
//@first and @last are "tokens"
//Modify howdy.html
$f3->route('GET /howdy/@first/@last',
    function($f3, $params) {

        $f3->set('firstName', $params['first']);
        $f3->set('lastName', $params['last']);
        $f3->set('message', 'Howdy');

        //load a page using a Template
        $template = new Template();
        echo $template->render('views/howdy.html');
    });

//8a. Add data to a session variable so it's available in another page
$f3->route('GET /hi/@first',
    function($f3, $params) {

        $f3->set('firstName', $params['first']);
        $f3->set('message', 'Hi');

        $_SESSION['firstName'] = $f3->get('firstName');

        //load a page using a Template
        $template = new Template();
        echo $template->render('views/howdy.html');
    });

//8b. Grab data from the session variable
$f3->route('GET /hi2',
    function() {
        echo 'Hi again, '.$_SESSION['firstName'];
    });


//Run fat-free
$f3->run();