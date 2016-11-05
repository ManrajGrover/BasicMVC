<?php

  class IndexModel {

    private $teams;

    public function getTeams() {
      $this->teams = file_get_contents(dirname(dirname(__FILE__))."/DataSource/data.json");
      return $this->teams;
    }

  }

?>
