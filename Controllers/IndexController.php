<?php

  class IndexController {

    private $model;

    function __construct($model) {
      $this->model = $model;
    }

    public function index() {

      $teams = json_decode($this->model->getTeams(), true);

      $clubs = $teams["clubs"];

      $page = file_get_contents(dirname(dirname(__FILE__)).'/templates/index.html');

      $page = str_replace("{{title}}", $teams["name"], $page);

      $table = '<table class="table"><thead><th>Name</th><th>Code</th></thead><tbody>';

      foreach ($clubs as $club) {
        $table .= "<tr><td>".$club['name']."</td><td>".$club['code']."</td></tr>";
      }

      $table .= "</tbody></table>";

      $page = str_replace("{{table}}", $table, $page);

      return $page;
    }
  }

?>
