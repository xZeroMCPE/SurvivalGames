<?php

namespace Andre;

use pocketmine\scheduler\PluginTask;

class Timer extends PluginTask{

  public function __construct($plugin){
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }

  public function onRun($tick){
  
  
  }

}
