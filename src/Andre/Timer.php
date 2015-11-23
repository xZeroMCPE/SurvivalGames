<?php

namespace Andre;

use pocketmine\scheduler\PluginTask;

class Timer extends PluginTask{

  public function __construct($plugin){
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }

  public function onRun($tick){
    //do something (this will be executed every second)
    //you can execute a *public* function in your main class using:
    //$this->plugin->someFuntction();
  }

}
