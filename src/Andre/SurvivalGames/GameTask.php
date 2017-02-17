<?php
namespace Andre\SurvivalGames;

use pocketmine\scheduler\PluginTask;

class GameTask extends PluginTask{
	
	public function __construct(Main $pl){
		parent::__construct($pl);
		$this->plugin = $pl;
	}
	public function onRun($currentTicks){
		if(!isset($this->plugin->lastpos) || $this->plugin->lastpos==array())
		{
			return false;
		}
			$this->plugin->level = $this->plugin->getServer()->getLevelByName($this->plugin->config->get("pos1")["level"]);
			$this->plugin->signlevel = $this->plugin->getServer()->getLevelByName($this->plugin->config->get("sign")["level"]);
			if(!$this->plugin->signlevel instanceof Level)
			{
				return false;
			}
		$this->plugin->changeStatusSign();
		if($this->plugin->gameStatus==0)
		{
			$i=0;
			foreach($this->plugin->players as $key=>$val)
			{
				$i++;
				$p=$this->plugin->getServer()->getPlayer($val["id"]);
				//echo($i."\n");
				$p->setLevel($this->plugin->level);
				eval("\$p->teleport(\$this->plugin->pos".$i .");");
				unset($p);
			}
		}
		if($this->plugin->gameStatus==1)
		{
			$this->plugin->lastTime--;
			$i=0;
			foreach($this->plugin->players as $key=>$val)
			{
				$i++;
				$p=$this->plugin->getServer()->getPlayer($val["id"]);
				//echo($i."\n");
				$p->setLevel($this->plugin->level); 
				eval("\$p->teleport(\$this->plugin->pos".$i .");");
				unset($p);
			}
			switch($this->plugin->lastTime)
			{
			case 1:
				$this->plugin->sendMessage("$Starting ".$this->plugin->lastTime." seconds");
				break;
			case 2:
				$this->plugin->sendMessage("$Starting ".$this->plugin->lastTime." seconds");
				break;
			case 3:
				$this->plugin->sendMessage("$Starting ".$this->plugin->lastTime." seconds");
				break;
			case 4:
				$this->plugin->sendMessage("$Starting ".$this->plugin->lastTime." seconds");
				break;
			case 5:
				$this->plugin->sendMessage("$Starting ".$this->plugin->lastTime." seconds");
				break;	
			case 10:
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] $Time 0:10.");
				break;
			case 30:
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] $Time 0:30.");
				break;
			case 60:
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] $Time 1:00.");
				break;
			case 90:
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] $Time 1:30.");
				break;
			case 120:
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] $Time 2:00.");
				break;
			case 150:
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] $Time 2:30.");
				break;
			case 0:
				$this->plugin->gameStatus=2;
				$arena = $this->plugin->getConfig()->get("Arena-Map");
				Server::getInstance()->broadcastMessage(TextFormat::BLUE. "$Started");
				foreach($this->plugin->players as $key=>$val){
					if($p->hasPermission("SurvivalGames.vip")){
						$p->getInventory()->addItem(new Item(Item::IRON_HELMET, 0, 1));
						$p->getInventory()->addItem(new Item(Item::IRON_CHESTPLATE, 0, 1));
						$p->getInventory()->addItem(new Item(Item::IRON_LEGGINGS, 0, 1));
						$p->getInventory()->addItem(new Item(Item::IRON_BOOTS, 0, 1));
						$p->getInventory()->addItem(new Item(Item::DIAMOND_AXE, 0, 1));
					}
					
					$p=$this->plugin->getServer()->getPlayer($val["id"]);
					$p->setMaxHealth(25);
					$p->setHealth(25);
					$p->setGamemode(0); //Those who cheats
					$p->setLevel($this->plugin->level);
				}
				$this->plugin->all=count($this->plugin->players);
				break;
			}
		}
		if($this->plugin->gameStatus==2)
		{
			$this->plugin->lastTime--;
			if($this->plugin->lastTime<=0)
			{
				$this->plugin->gameStatus=3;
				$this->plugin->sendMessage("$Chest_Refilled");
				$this->plugin->lastTime=$this->plugin->gameTime;
				
			}
		}
		if($this->plugin->gameStatus==3 || $this->plugin->gameStatus==4)
		{
			if(count($this->plugin->players)==1)
			{
				$this->plugin->sendMessage(TextFormat::RED."[{$this->plugin->getConfig()->get("prefix")}] "); // DELETED
				foreach($this->plugin->players as &$pl)
				{
					$p=$this->plugin->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->plugin->signlevel);
					$p->getInventory()->clearAll();
					$p->setMaxHealth(25);
					$p->setHealth(25);
					$p->teleport($this->plugin->signlevel->getSpawnLocation());
					unset($pl,$p);
				}
				$this->plugin->players=array();
				$this->plugin->gameStatus=0;
				$this->plugin->lastTime=0;
			}
			else if(count($this->plugin->players)==0)
			{
				$this->plugin->gameStatus=0;
				$this->plugin->lastTime=0;
				$this->plugin->ClearAllInv();
			}
		}
		if($this->plugin->gameStatus==3)
		{
			$this->plugin->lastTime--;
			switch($this->plugin->lastTime)
			{
			case 1:
				$this->plugin->sendMessage("$Deathmatch_starting §b1 seconds");
				break;
			case 2:
				$this->plugin->sendMessage("$Deathmatch_starting §b2 seconds");
				break;
			case 3:
				$this->plugin->sendMessage("$Deathmatch_starting §b3 seconds");
				break;
			case 4:
				$this->plugin->sendMessage("$Deathmatch_starting §b4 seconds");
				break;
			case 5:
				$this->plugin->sendMessage("$Deathmatch_starting §b5 seconds");
				break;	
			case 10:
				$this->plugin->sendMessage(TextFormat::YELLOW."[{$this->plugin->getConfig()->get("prefix")}] Deathmatch will start in 0:10.");
				break;
			case 0:
				$this->plugin->sendMessage(TextFormat::YELLOW."[{$this->plugin->getConfig()->get("prefix")}] $Deatchmatch_started");
				foreach($this->plugin->players as $pl)
				{
					$p=$this->plugin->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->plugin->level);
					$p->teleport($this->plugin->lastpos);
					unset($p,$pl);
				}
				$this->plugin->gameStatus=4;
				$this->plugin->lastTime=$this->plugin->endTime;
				break;
			}
		}
		if($this->plugin->gameStatus==4)
		{
			$this->plugin->lastTime--;
			switch($this->plugin->lastTime)
			{
			case 1:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ending " .$this->plugin->lastTime. ".");
				break;
			case 2:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ending " .$this->plugin->lastTime. ".");
				break;
			case 3:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Endingn " .$this->plugin->lastTime. ".");
				break;
			case 4:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ending " .$this->plugin->lastTime. ".");
				break;
			case 5:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ending " .$this->plugin->lastTime. ".");
				break;	
			case 10:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ending 0:10.");
				break;
			//case 20:
			case 30:
				$this->plugin->sendMessage(TextFormat::RED. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ending 0:30.");
				break;
			case 0:
				$this->plugin->sendMessage(TextFormat::BLUE. "[{$this->plugin->getConfig()->get("prefix")}] $Match_Ended");
				foreach($this->plugin->players as $pl)
				{
					$p=$this->plugin->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->plugin->signlevel);
					$p->teleport($this->plugin->signlevel->getSpawnLocation());
					$p->getInventory()->clearAll();
					$p->setMaxHealth(25);
					$p->setHealth(25);
					unset($p,$pl);
				}
				$this->plugin->players=array();
				$this->plugin->gameStatus=0;
				$this->plugin->lastTime=0;
				break;
			}
		}
		$this->plugin->changeStatusSign();
	}
}
