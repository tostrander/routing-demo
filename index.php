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
        echo '<h3>My Jewelry Site</h3>';
        echo '<a href="./shop">Start shopping</a>';
    });

//2. Define an additional route
//Add .htaccess (config file for Apache)
//All routes must be registered in project folder, index.php
//Try It:  Create an "about" page
$f3->route('GET /shop',
    function() {
        echo '<h1>Start shopping here!</h1>';

        //We could add a list of product categories here

    });

$f3->route('GET /about',
    function() {
        echo '<h1>Learn all about us!</h1>';
    });

//3. Routes can have as many levels as we want
$f3->route('GET /about/history',
    function() {
        echo "<h1>We've been around for 100 years.</h1>";
    });

//3b. Routes can have as many levels as we want
$f3->route('GET /about/media/news',
    function() {
        echo "<h1>We're in the news!</h1>";
    });

//4. Use a parameter
//@id is a "token"
//You can pass parameters using @notation, then retrieve values using
//built in $params array.
//Note that anonymous function now accepts two parameters:
//the instance of the base and a $params array
//In http://tostrander.greenriverdev.com/328/routing-demo/product/123,
// there is no route defined for /product/123, so f3 looks for a route
// that accepts a parameter
$f3->route('GET /product/@id',
    function($f3, $params) {

        echo '<pre>';
        print_r($params);
        echo '</pre>';
        echo 'Product '.$params['id'];
    });

//5. Rerouting and 404 error
$f3->route('GET /shop/@category',
    function($f3, $params) {
        switch($params['category']) {
            case 'necklaces':
                echo 'Necklaces!'; break;
            case 'bracelets':
                echo 'Bracelets!'; break;
            case 'rings':
                echo 'Rings!'; break;
            case 'nose-rings':
                //Reroute to another page
                $f3->reroute('/shop/rings');
            default:
                $f3->error(404);
        }
    });

//6. Define a global variable and display data in a TEMPLATE
//Fat-free stores variables in the router that can then be passed
//around your program. Values are stored in key/value pairs.
//Primarily used with Fat-free's templating language.
$f3->route('GET /locations/@city',
    function($f3, $params) {

        $f3->set('city', $params['city']);
        $f3->set('message', 'View our Stores');

        //load a page using a Template
        $template = new Template();
        echo $template->render('views/stores.html');
    });

//7. Multiple parameters
//@city and @state are "tokens"
//Modify locations.html
$f3->route('GET /locations/@city/@state',
    function($f3, $params) {

        $f3->set('city', $params['city']);
        $f3->set('state', $params['state']);
        $f3->set('message', 'View our Stores');

        //load a page using a Template
        $template = new Template();
        echo $template->render('views/stores.html');
    });

//8a. Add data to a session variable so it's available in another page
$f3->route('GET /login/@first',
    function($f3, $params) {

        $f3->set('first', $params['first']);
        $_SESSION['first'] = $f3->get('first');

        //Redirect to account page
        $f3->reroute('/account');
    });

//8b. Grab data from the session variable
$f3->route('GET /account',
    function() {
        echo 'Welcome, '.$_SESSION['first'];
    });

//Run fat-free
$f3->run();