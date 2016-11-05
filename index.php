<?php

  $route = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'],'/')) : '/';

  if ($route == '/' || $route[0] == 'index') {

    require_once(__DIR__.'/Models/IndexModel.php');
    require_once(__DIR__.'/Controllers/IndexController.php');
    require_once(__DIR__.'/Views/IndexView.php');

    $indexModel = New IndexModel();
    $indexController = New IndexController($indexModel);
    $indexView = New IndexView($indexController, $indexModel);

    print $indexView->index();

  } else {

    $routeController = $route[0]; 
    $routeAction = isset($route[1]) ? $route[1] : '';
    $routeParams = array_slice($route, 2); 
    $controlFile = __DIR__.'/Controllers/'.$routeController.'Controller.php';

    if (file_exists($ctrlPath)) {

      require_once(__DIR__.'/Models/'.ucfirst($routeController).'Model.php');
      require_once(__DIR__.'/Controllers/'.ucfirst($routeController).'Controller.php');
      require_once(__DIR__.'/Views/'.ucfirst($routeController).'View.php');

      $modelName = ucfirst($routeController).'Model';
      $controllerName = ucfirst($routeController).'Controller';
      $viewName = ucfirst($routeController).'View';

      $controllerObj = new $controllerName( new $modelName );
      $viewObj = new $viewName( $controllerObj, new $modelName );

      if ($requestedAction != '') {
        print $viewObj->$routeAction($routeParams);
      }

    } else {
      header('HTTP/1.1 404 Not Found');
      $template = file_get_contents(__DIR__.'/templates/404.html');
      $page = str_replace("{{controlPath}}", $controlFile, $template);
      die($page);
    }
  }
?>
