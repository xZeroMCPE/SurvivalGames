<?php

namespace Andre;

use pocketmine\scheduler\PluginTask;

class Timer extends PluginTask{

  public function __construct($plugin){
    $this->plugin = $plugin;
    parent::__construct($plugin);  }

  public function onRun($tick){
    
    if ($tick = 0) {
    $this->getServer()->broadcastMessage("- Go Go, The match has started ! ");
     } else {
    return false;
    // More will be added for 2 min :P 
  
  }

}
