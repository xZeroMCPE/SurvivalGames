<?php

namespace Andre\SurvivalGames\Kit;

use pocketmine\event\Listener;

use pocketmine\level\Position;
use pocketmine\level\Location;


class Kits implements Listener
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /** Getting kits */
    public function getKits($kits)
    {
        // todo
    }
}