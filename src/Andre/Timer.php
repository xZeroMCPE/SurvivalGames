<?php

namespace Andre;

use pocketmine\scheduler\PluginTask;

class Timer extends PluginTask{

  public function __construct($plugin){
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }

  public function onRun($tick){
    
    if ($tick = 0) {
$this->getServer()->broadcastMessage("- Match will start in 1:150 Min !");
} else {
return false;
  }

}
