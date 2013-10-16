<?php


class Routes {

	public function __construct(){


		//Prefixe
		Router::prefix(Conf::$adminPrefix,'admin');

		//Connect
		Router::connect('','pages/home'); //Racine du site ( à laisser en premiere regle !)
		Router::connect('home/*','pages/home');
		Router::connect('vestiaire','vestiaire/pages/home');
		Router::connect('blog/post/:id/:slug','pages/blog/id:([0-9\-]+)/slug:([a-zA-Z0-9\-]+)');
		Router::connect('blog/*','pages/blog');
		Router::connect('calendar/date/:date','pages/home/date:([0-9\-]+)');
		Router::connect(':slug','pages/view/slug:([a-zA-Z0-9\-]+)');
		Router::connect(':sport/:id/:slug','events/view/id:([0-9\-]+)/slug:([a-zA-Z0-9\-]+)/sport:([a-zA-Z0-9\-]+)');
		Router::connect('events/view/:id/:slug','events/view/id:([0-9\-]+)/slug/:([a-zA-Z0-9\-]+)');
		//Router::connect('blog/:slug-:id','posts/view/id:([0-9]+)/slug:([a-zA-Z0-9\-]+)');


	}
}


?>