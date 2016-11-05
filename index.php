<?php
  
  /**
   * Get route information from the URL
   * @var String
   */
  $route = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'],'/')) : '/';

  /**
   * If route is 'index', render 'IndexView'
   */
  if ($route == '/' || $route[0] == 'index') {

    /**
     * Get Index Controller, Model and View and render it
     */
    require_once(__DIR__.'/Models/IndexModel.php');
    require_once(__DIR__.'/Controllers/IndexController.php');
    require_once(__DIR__.'/Views/IndexView.php');

    $indexModel = New IndexModel();
    $indexController = New IndexController($indexModel);
    $indexView = New IndexView($indexController, $indexModel);

    print $indexView->index();

  } else {

    /**
     * Get the corresponding route details
     */
    $routeController = $route[0]; 
    $routeAction = isset($route[1]) ? $route[1] : '';
    $routeParams = array_slice($route, 2);

    /**
     * Get corresponding controller file name
     */
    $controlFile = __DIR__.'/Controllers/'.$routeController.'Controller.php';

    /**
     * Check if file exist
     */
    if (file_exists($controlFile)) {

      /**
       * Get Corresponding Controller, Model and View and render it
       */
      require_once(__DIR__.'/Models/'.ucfirst($routeController).'Model.php');
      require_once(__DIR__.'/Controllers/'.ucfirst($routeController).'Controller.php');
      require_once(__DIR__.'/Views/'.ucfirst($routeController).'View.php');

      /**
       * Get Controller, Model, and View Class Name
       */
      $model = ucfirst($routeController).'Model';
      $controller = ucfirst($routeController).'Controller';
      $view = ucfirst($routeController).'View';

      /**
       * Create Controller and View Instance
       */
      $controllerInstance = new $controller(new $model);
      $viewInstance = new $view( $controllerInstance, new $model );

      /**
       * If there is action given, execute it
       */
      if ($routeAction != '') {
        print $viewInstance->$routeAction($routeParams);
      }

    } else {
      /**
       * If file does not exist, throw an error
       */
      header('HTTP/1.1 404 Not Found');
      $template = file_get_contents(__DIR__.'/templates/404.html');
      $page = str_replace("{{controlPath}}", $controlFile, $template);
      die($page);
    }
  }
?>
