<?php

namespace Andre\SurvivalGames\Arena;

use pocketmine\event\Listener;

use pocketmine\level\Position;
use pocketmine\level\Location;

class Management implements Listener{

private $plugin;
public function __construct(Main $plugin){
$this->plugin = $plugin;
}

       /** Creating an arena */
public function createArena($minPlayer, $name, $maxPlayer, $world, $spawns[]){

    // todo
}
      /** Deleting an arena */
  public function deleteArena($name)
{
    // todo
}

      /** List all arenas */
 public function listArena($arenas)
{
    return $arenas;
}
      /** kitSystem */
public function getKit()
{
    return $this->plugin->kit;
}
