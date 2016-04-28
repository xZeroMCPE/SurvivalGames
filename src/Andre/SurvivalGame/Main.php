<?php
namespace Andre\SurvivalGame;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\OfflinePlayer;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\block\Block;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use killrate\Main as KillRate;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;

class Main extends PluginBase implements Listener
{	
	public $data;
	
	private static $object = null;
	
	public static function getInstance(){
		return self::$object;
	}

	public function onEnable() {
		$this->api = EconomyAPI::getInstance();
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this,"gameTimber"]),20);
		@mkdir($this->getDataFolder(), 0777, true);
		
		# Custom Config Saving
		$this->saveResource("points.yml");
		$this->points = new Config($this->getDataFolder(). "points.yml", Config::YAML);
		
		# Custom Points Config Saving
		$this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML, array());
				
	// ------------------ MULTILANGUAGE SUPPORT ------------------ \\
     $Lang = $this->getConfig()->get('Language');
        if(!(is_dir($this->getDataFolder()."Message-".$Lang.".yml"))){
			$this->plugin->saveResource("Message-".$Lang.".yml", true);
		 }else{
			 $this->getServer()->getLogger()->info("Loading lang file.....");
		 }
		$ChooseLang = $this->getResource("Message-" . $Lang . ".yml"); 
		$Already_Playing  = $ChooseLang->get("Already_Playing"); 
                $Joined_Arena  = $ChooseLang->get("Joined_Arena"); 
                $Not_In_Match  = $ChooseLang->get("Not_In_-Match"); 
                $Matched_Running  = $ChooseLang->get("Matched_Running"); 
                $No_Permission  = $ChooseLang->get("No_Permission"); 
                $Force_Start  = $ChooseLang->get("Force_Start"); 
                $Blocked_Command  = $ChooseLang->get("Blocked_Command"); 
                $Starting  = $ChooseLang->get("Starting"); 
                $Timer  = $ChooseLang->get("Timer"); 
                $Not_Enough_Players  = $ChooseLang->get("Not_Enough_Players"); 
                $Started  = $ChooseLang->get("Started"); 
                $Chest_Refilled  = $ChooseLang->get("Chest_Refilled"); 
                $Deathmatch_starting  = $ChooseLang->get("Deathmatch_starting"); 
                $Deatchmatch_started  = $ChooseLang->get("Deatchmatch_started"); 
                $Match_Ending  = $ChooseLang->get("Match_Ending"); 
                $Match_Ended  = $ChooseLang->get("Match_Ended"); 
		{
			$this->sign=$this->config->get("sign");
			$this->pos1=$this->config->get("pos1");
			$this->pos2=$this->config->get("pos2");
			$this->pos3=$this->config->get("pos3");
			$this->pos4=$this->config->get("pos4");
			$this->pos5=$this->config->get("pos5");
			$this->pos6=$this->config->get("pos6");
			$this->pos7=$this->config->get("pos7");
			$this->pos8=$this->config->get("pos8");
			$this->pos9=$this->config->get("pos9");
			$this->pos10=$this->config->get("pos10");
			$this->pos11=$this->config->get("pos11");
			$this->pos12=$this->config->get("pos12");
			$this->pos13=$this->config->get("pos13");
			$this->pos14=$this->config->get("pos14");
			$this->pos15=$this->config->get("pos15");
			$this->pos16=$this->config->get("pos16");
			$this->pos17=$this->config->get("pos17");
 			$this->pos18=$this->config->get("pos18");
 			$this->pos19=$this->config->get("pos19");
 			$this->pos20=$this->config->get("pos20");
 			$this->pos21=$this->config->get("pos21");
 			$this->pos22=$this->config->get("pos22");
 			$this->pos23=$this->config->get("pos23");
 			$this->pos24=$this->config->get("pos24");			
			$this->lastpos=$this->config->get("lastpos");
			$this->signlevel=$this->getServer()->getLevelByName($this->config->get("sign")["level"]);
			$this->sign=new Vector3($this->sign["x"],$this->sign["y"],$this->sign["z"]);
			$this->pos1=new Vector3($this->pos1["x"]+0.5,$this->pos1["y"],$this->pos1["z"]+0.5);
			$this->pos2=new Vector3($this->pos2["x"]+0.5,$this->pos2["y"],$this->pos2["z"]+0.5);
			$this->pos3=new Vector3($this->pos3["x"]+0.5,$this->pos3["y"],$this->pos3["z"]+0.5);
			$this->pos4=new Vector3($this->pos4["x"]+0.5,$this->pos4["y"],$this->pos4["z"]+0.5);
			$this->pos5=new Vector3($this->pos5["x"]+0.5,$this->pos5["y"],$this->pos5["z"]+0.5);
			$this->pos6=new Vector3($this->pos6["x"]+0.5,$this->pos6["y"],$this->pos6["z"]+0.5);
			$this->pos7=new Vector3($this->pos7["x"]+0.5,$this->pos7["y"],$this->pos7["z"]+0.5);
			$this->pos8=new Vector3($this->pos8["x"]+0.5,$this->pos8["y"],$this->pos8["z"]+0.5);
			$this->pos9=new Vector3($this->pos9["x"]+0.5,$this->pos9["y"],$this->pos9["z"]+0.5);
			$this->pos10=new Vector3($this->pos10["x"]+0.5,$this->pos10["y"],$this->pos10["z"]+0.5);
			$this->pos11=new Vector3($this->pos11["x"]+0.5,$this->pos11["y"],$this->pos11["z"]+0.5);
                        $this->pos12=new Vector3($this->pos12["x"]+0.5,$this->pos12["y"],$this->pos12["z"]+0.5);
                        $this->pos13=new Vector3($this->pos13["x"]+0.5,$this->pos13["y"],$this->pos13["z"]+0.5);
                        $this->pos14=new Vector3($this->pos14["x"]+0.5,$this->pos14["y"],$this->pos14["z"]+0.5);
                        $this->pos15=new Vector3($this->pos15["x"]+0.5,$this->pos15["y"],$this->pos15["z"]+0.5);
                        $this->pos16=new Vector3($this->pos16["x"]+0.5,$this->pos16["y"],$this->pos16["z"]+0.5);
                        $this->pos17=new Vector3($this->pos17["x"]+0.5,$this->pos17["y"],$this->pos17["z"]+0.5);
                        $this->pos18=new Vector3($this->pos18["x"]+0.5,$this->pos18["y"],$this->pos18["z"]+0.5);
                        $this->pos19=new Vector3($this->pos19["x"]+0.5,$this->pos19["y"],$this->pos19["z"]+0.5);
                        $this->pos20=new Vector3($this->pos20["x"]+0.5,$this->pos20["y"],$this->pos20["z"]+0.5);
                        $this->pos21=new Vector3($this->pos21["x"]+0.5,$this->pos21["y"],$this->pos21["z"]+0.5);
                        $this->pos22=new Vector3($this->pos22["x"]+0.5,$this->pos22["y"],$this->pos22["z"]+0.5);
                        $this->pos23=new Vector3($this->pos23["x"]+0.5,$this->pos23["y"],$this->pos23["z"]+0.5);
                        $this->pos24=new Vector3($this->pos24["x"]+0.5,$this->pos24["y"],$this->pos24["z"]+0.5);
			$this->lastpos=new Vector3($this->lastpos["x"]+0.5,$this->lastpos["y"],$this->lastpos["z"]+0.5);
		}
		if(!$this->config->exists("endTime"))
		{
			$this->config->set("endTime",180);
		}
		if(!$this->config->exists("gameTime"))
		{
			$this->config->set("gameTime",300);
		}
			if(!$this->config->exists("prefix"))
		{
			$this->config->set("prefix","Game");
		
		}
			
		if(!$this->config->exists("waitTime"))
		{
			$this->config->set("waitTime",180);
		}
		
		$this->endTime=(int)$this->config->get("endTime");
		$this->gameTime=(int)$this->config->get("gameTime");
		$this->waitTime=(int)$this->config->get("waitTime");
		$this->prefix=$this->config->get("prefix");
		$this->gameStatus=0;
		$this->lastTime=(int)0;
		$this->players=array();
		$this->SetStatus=array();
		$this->all=0;
		$this->config->save();
		$pm = $this->getServer()->getPluginManager();
		if(!($this->money = $pm->getPlugin("EconomyAPI"))
        && !($this->money = $pm->getPlugin("PocketMoney")))			{
			$this->getServer()->getLogger()->info(TextFormat::RED. "[SG] ----Warning!!!----");
			$this->getServer()->getLogger()->info(TextFormat::RED. "[SG] Economy isn't installed!");
			$this->getServer()->getLogger()->info(TextFormat::RED. "[SG] Please install Economy or PocketMoney to enable SG");
		} else {
			$this->getServer()->getLogger()->info(TextFormat::DARK_BLUE."[SG] Using Money System From... ".
											 TextFormat::YELLOW.$this->money->getName()." v".
											 $this->money->getDescription()->getVersion());
											 }
		$this->getServer()->getLogger()->info(TextFormat::BLUE."[SG] SurvivalGame Has Been Enable");
		$this->getServer()->getLogger()->info(TextFormat::BLUE."[SG] By: AndreTheGamer");
		$this->getServer()->getLogger()->info(TextFormat::BLUE."[SG] File: Config Loaded !");
		$this->getServer()->getLogger()->info(TextFormat::BLUE."[SG] File: Point Loaded !");
		
	
	}

	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args)
	{
		if($command->getName()=="lobby")
		{
			if($this->gameStatus>=2)
			{
				$sender->sendMessage("[{$this->getConfig()->get("prefix")}] $Already_Playing");
				return;
			}
			if(isset($this->players[$sender->getName()]))
			{	
				unset($this->players[$sender->getName()]);
				$sender->setLevel($this->signlevel);
				$sender->teleport($this->signlevel->getSpawnLocation());
				$sender->sendMessage(TextFormat::GREEN."[{$this->getConfig()->get("prefix")}] Teleporting to lobby...");
				$this->getServer()->broadcastMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}]Player ".$sender->getName()." left the match.");
				$this->changeStatusSign();
				if($this->gameStatus==1 && count($this->players)<2)
				{
					$this->gameStatus=0;
					$this->lastTime=0;
					$event->getPlayer()->sendMessage("[{$this->getConfig()->get("prefix")}] $Arena");
					/*foreach($this->players as $pl)
					{
						$p=$this->getServer()->getPlayer($pl["id"]);
						$p->setLevel($this->signlevel);
						$p->teleport($this->signlevel->getSpawnLocation());
						unset($p,$pl);
					}*/
				}
			}
			else
			{
				$sender->sendMessage(TextFormat::RED . "[{$this->getConfig()->get("prefix")}] $Not_In_Match");
			}
			return true;
		}
		if(!isset($args[0])){unset($sender,$cmd,$label,$args);return false;};
		switch ($args[0])
		{
		case "stats":
			if($sender->hasPermission("sg.command.stats") or $sender->hasPermission("sg.command") or $sender->hasPermission("sg")){
                                if(!(isset($args[1]))){
                                $player = $sender->getName();
		                $deaths = $this->points->get($player)[0];
				$kills = $this->points->get($player)[1];
				$points = $this->points->get($player)[2];
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §l§f--[[§e---+++---§r§f]]--");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §bYou're stats");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §lDeaths: §9$deaths");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §lKills: §9$kills");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §l§f--[[§e---+++---§r§f]]--");
				return true;
                        }else{
                                $player = $args[1];
				$deaths = $this->points->get($player)[0];
				$kills = $this->points->get($player)[1];
				$points = $this->points->get($player)[2];
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §l§f--[[§e---+++---§r§f]]--");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §bPlayer: §9$player Stats");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §lDeaths: §9$deaths");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §lKills: §9$kills");
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §l§f--[[§e---+++---§r§f]]--");
				return true;
                                }
                        }else{
                                $sender->sendMessage("$No_Permissio");
				return true; }
				break; 
		case "set":
			if($this->config->exists("lastpos"))
			{
				$sender->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] §cArena is already setup. use /sg remove to remove the current arena.");
			}
			else
			{
				$name=$sender->getName();
				$this->SetStatus[$name]=0;
				$sender->sendMessage(TextFormat::DARK_BLUE. "[{$this->getConfig()->get("prefix")}] Tap a sign to set join sign.");
			}
			break;
		case "remove":
			$this->config->remove("sign");
			$this->config->remove("pos1");
			$this->config->remove("pos2");
			$this->config->remove("pos3");
			$this->config->remove("pos4");
			$this->config->remove("pos5");
			$this->config->remove("pos6");
			$this->config->remove("pos7");
			$this->config->remove("pos8");
			$this->config->remove("pos9");
			$this->config->remove("pos10");
			$this->config->remove("pos11");
			$this->config->remove("pos12");
			$this->config->remove("pos13");
			$this->config->remove("pos14");
			$this->config->remove("pos15");
			$this->config->remove("pos16");
			$this->config->remove("pos17");
 			$this->config->remove("pos18");
 			$this->config->remove("pos19");
 			$this->config->remove("pos20");
 			$this->config->remove("pos21");
 			$this->config->remove("pos22");
 			$this->config->remove("pos23");
 			$this->config->remove("pos24");			
			$this->config->remove("lastpos");
			$this->config->save();
			unset($this->sign,$this->pos1,$this->pos2,$this->pos3,$this->pos4,$this->pos5,$this->pos6,$this->pos7,$this->pos8,$this->pos9,$this->pos10,$this->pos11,$this->pos12,$this->pos13,$this->pos14,$this->pos15,$this->pos16,$this->pos17,$this->pos18,$this->pos19,$this->pos20,$this->pos21,$this->pos22,$this->pos23,$this->pos24,$this->lastpos);
			$sender->sendMessage(TextFormat::GREEN . "Game settings successfully removed.");
			break;
		case "start":
			Server::getInstance()->broadcastMessage(TextFormat::BLUE. "$Force_Start");
			$this->gameStatus=1;
			$this->lastTime=5;
			break;
		case "reload":
			@mkdir($this->getDataFolder(), 0777, true);
			$this->config=new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
			if($this->config->exists("lastpos"))
			{
				$this->sign=$this->config->get("sign");
				$this->pos1=$this->config->get("pos1");
				$this->pos2=$this->config->get("pos2");
				$this->pos3=$this->config->get("pos3");
				$this->pos4=$this->config->get("pos4");
				$this->pos5=$this->config->get("pos5");
				$this->pos6=$this->config->get("pos6");
				$this->pos7=$this->config->get("pos7");
				$this->pos8=$this->config->get("pos8");
				$this->pos9=$this->config->get("pos9");
				$this->pos10=$this->config->get("pos10");
			        $this->pos11=$this->config->get("pos11");
			        $this->pos12=$this->config->get("pos12");
			        $this->pos13=$this->config->get("pos13");
			        $this->pos14=$this->config->get("pos14");
			        $this->pos15=$this->config->get("pos15");
			        $this->pos16=$this->config->get("pos16");
 			        $this->pos17=$this->config->get("pos17");
 			        $this->pos18=$this->config->get("pos18");
 			        $this->pos19=$this->config->get("pos19");
 		           	$this->pos20=$this->config->get("pos20");
 		        	$this->pos21=$this->config->get("pos21");
 		        	$this->pos22=$this->config->get("pos22");
 		        	$this->pos23=$this->config->get("pos23");
 			        $this->pos24=$this->config->get("pos24");			        
				$this->lastpos=$this->config->get("lastpos");
				$this->signlevel=$this->getServer()->getLevelByName($this->config->get("sign")["level"]);
				$this->sign=new Vector3($this->sign["x"],$this->sign["y"],$this->sign["z"]);
				$this->pos1=new Vector3($this->pos1["x"]+0.5,$this->pos1["y"],$this->pos1["z"]+0.5);
				$this->pos2=new Vector3($this->pos2["x"]+0.5,$this->pos2["y"],$this->pos2["z"]+0.5);
				$this->pos3=new Vector3($this->pos3["x"]+0.5,$this->pos3["y"],$this->pos3["z"]+0.5);
				$this->pos4=new Vector3($this->pos4["x"]+0.5,$this->pos4["y"],$this->pos4["z"]+0.5);
				$this->pos5=new Vector3($this->pos5["x"]+0.5,$this->pos5["y"],$this->pos5["z"]+0.5);
				$this->pos6=new Vector3($this->pos6["x"]+0.5,$this->pos6["y"],$this->pos6["z"]+0.5);
				$this->pos7=new Vector3($this->pos7["x"]+0.5,$this->pos7["y"],$this->pos7["z"]+0.5);
				$this->pos8=new Vector3($this->pos8["x"]+0.5,$this->pos8["y"],$this->pos8["z"]+0.5);
				$this->pos9=new Vector3($this->pos9["x"]+0.5,$this->pos9["y"],$this->pos9["z"]+0.5);
				$this->pos10=new Vector3($this->pos10["x"]+0.5,$this->pos10["y"],$this->pos10["z"]+0.5);
				$this->pos11=new Vector3($this->pos11["x"]+0.5,$this->pos11["y"],$this->pos11["z"]+0.5);
				$this->pos12=new Vector3($this->pos12["x"]+0.5,$this->pos12["y"],$this->pos12["z"]+0.5);
				$this->pos13=new Vector3($this->pos13["x"]+0.5,$this->pos13["y"],$this->pos13["z"]+0.5);
				$this->pos14=new Vector3($this->pos14["x"]+0.5,$this->pos14["y"],$this->pos14["z"]+0.5);
				$this->pos15=new Vector3($this->pos15["x"]+0.5,$this->pos15["y"],$this->pos15["z"]+0.5);
				$this->pos16=new Vector3($this->pos16["x"]+0.5,$this->pos16["y"],$this->pos16["z"]+0.5);
                                $this->pos17=new Vector3($this->pos17["x"]+0.5,$this->pos17["y"],$this->pos17["z"]+0.5);
 				$this->pos18=new Vector3($this->pos18["x"]+0.5,$this->pos18["y"],$this->pos18["z"]+0.5);
 				$this->pos19=new Vector3($this->pos19["x"]+0.5,$this->pos19["y"],$this->pos19["z"]+0.5);
 				$this->pos20=new Vector3($this->pos20["x"]+0.5,$this->pos20["y"],$this->pos20["z"]+0.5);
 				$this->pos21=new Vector3($this->pos21["x"]+0.5,$this->pos21["y"],$this->pos21["z"]+0.5);
 				$this->pos22=new Vector3($this->pos22["x"]+0.5,$this->pos22["y"],$this->pos22["z"]+0.5);
 				$this->pos23=new Vector3($this->pos23["x"]+0.5,$this->pos23["y"],$this->pos23["z"]+0.5);
 				$this->pos24=new Vector3($this->pos24["x"]+0.5,$this->pos24["y"],$this->pos24["z"]+0.5);				
				$this->lastpos=new Vector3($this->lastpos["x"]+0.5,$this->lastpos["y"],$this->lastpos["z"]+0.5);
			}
			if(!$this->config->exists("gameTime"))
			{
				$this->config->set("gameTime",300);
			}
			if(!$this->config->exists("prefix"))
			{
				$this->config->set("prefix","Game");
			}
			$this->gameTime=(int)$this->config->get("gameTime");//how long a match is
			$this->prefix=(int)$this->config->get("prefix");
			$this->gameStatus=0;//status of the game (sign)
			$this->lastTime=0;//just a variable :P
			$this->players=array();//players
			$this->SetStatus=array();
			$this->all=0;//
			$this->config->save();
			$sender->sendMessage(TextFormat::GREEN. "[SG] Config reloaded");
			break;
		default:
			return false;
			break;
		}
		return true;
	}
	
	public function onPlace(BlockPlaceEvent $event)
	{
		if(!isset($this->sign))
		{
			return;
		}
		$block=$event->getBlock();
		if($this->PlayerIsInGame($event->getPlayer()->getName()) || $block->getLevel()==$this->level)
		{
			if(!$event->getPlayer()->isOp())
			{
				$event->setCancelled();
			}
		}
		unset($block,$event);
	}
	public function onMove(PlayerMoveEvent $event)
	{
		if(!isset($this->sign))
		{
			return;
		}
		if($this->PlayerIsInGame($event->getPlayer()->getName()) && $this->gameStatus<=1)
		{
			if(!$event->getPlayer()->isOp())
			{
				$event->setCancelled();
			}
		}
		unset($event);
	}
	public function onBreak(BlockBreakEvent $event)
	{
		if(!isset($this->sign))
		{
			return;
		}
		$sign=$this->config->get("sign");
		$block=$event->getBlock();
		if($this->PlayerIsInGame($event->getPlayer()->getName()) || ($block->getX()==$sign["x"] && $block->getY()==$sign["y"] && $block->getZ()==$sign["z"] && $block->getLevel()->getFolderName()==$sign["level"]) || $block->getLevel()==$this->signlevel)
		{
			if(!$event->getPlayer()->isOp())
			{
				$event->setCancelled();
			}
		}
		unset($sign,$block,$event);
	}
	
	public function onPlayerCommand(PlayerCommandPreprocessEvent $event)
	{
		if(!$this->PlayerIsInGame($event->getPlayer()->getName()) || $event->getPlayer()->isOp() || substr($event->getMessage(),0,1)!="/")
		{
			unset($event);
			return;
		}
		switch(strtolower(explode(" ",$event->getMessage())[0]))
		{
		case "/kill":
		case "/lobby":
			
			break;
		default:
			$event->setCancelled();
			$event->getPlayer()->sendMessage("[{$this->getConfig()->get("prefix")}] $Blocked_Command");
			break;
		}
		unset($event);
	
        }
	public function PlayerIsInGame($name)
	{
		return isset($this->players[$name]);
	}
	
	public function PlayerDeath(PlayerDeathEvent $event){
		if($this->gameStatus==3 || $this->gameStatus==4)
		{
			if(isset($this->players[$event->getEntity()->getName()]))
			{
				$this->ClearInv($event->getEntity());
				unset($this->players[$event->getEntity()->getName()]);
				if(count($this->players)>1)
				{
					$this->sendMessage("[{$this->getConfig()->get("prefix")}]{$event->getEntity()->getName()} died.");
				$event->getPlayer()->sendMessage("[{$this->getConfig()->get("prefix")}] Players left: ".count($this->players));
					$event->getPlayer()->sendMessage("[{$this->getConfig()->get("prefix")}] Time remaining: ".$this->lastTime." seconds.");
				}
				$this->changeStatusSign();
			}
			
		}
	}
	public function sendMessage($msg){
		foreach($this->players as $pl)
		{
			$this->getServer()->getPlayer($pl["id"])->sendMessage($msg);
		}
		$this->getServer()->getLogger()->info($msg);
		unset($pl,$msg);
	}
	public function gameTimber(){
		if(!isset($this->lastpos) || $this->lastpos==array())
		{
			return false;
		}
			$this->level = $this->getServer()->getLevelByName($this->config->get("pos1")["level"]);
			$this->signlevel = $this->getServer()->getLevelByName($this->config->get("sign")["level"]);
			if(!$this->signlevel instanceof Level)
			{
				return false;
			}
		$this->changeStatusSign();
		if($this->gameStatus==0)
		{
			$i=0;
			foreach($this->players as $key=>$val)
			{
				$i++;
				$p=$this->getServer()->getPlayer($val["id"]);
				//echo($i."\n");
				$p->setLevel($this->level);
				eval("\$p->teleport(\$this->pos".$i.");");
				unset($p);
			}
		}
		if($this->gameStatus==1)
		{
			$this->lastTime--;
			$i=0;
			foreach($this->players as $key=>$val)
			{
				$i++;
				$p=$this->getServer()->getPlayer($val["id"]);
				//echo($i."\n");
				$p->setLevel($this->level); 
				eval("\$p->teleport(\$this->pos".$i."");
				unset($p);
			}
			switch($this->lastTime)
			{
			case 1:
				$this->sendMessage("$Starting ".$this->lastTime." seconds");
				break;
			case 2:
				$this->sendMessage("$Starting ".$this->lastTime." seconds");
				break;
			case 3:
				$this->sendMessage("$Starting ".$this->lastTime." seconds");
				break;
			case 4:
				$this->sendMessage("$Starting ".$this->lastTime." seconds");
				break;
			case 5:
				$this->sendMessage("$Starting ".$this->lastTime." seconds");
				break;	
			case 10:
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] $Time 0:10.");
				break;
			case 30:
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] $Time 0:30.");
				break;
			case 60:
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] $Time 1:00.");
				break;
			case 90:
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] $Time 1:30.");
				break;
			case 120:
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] $Time 2:00.");
				break;
			case 150:
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] $Time 2:30.");
				break;
			case 0:
				$this->gameStatus=2;
				$arena = $this->getConfig()->get("Arena-Map");
				Server::getInstance()->broadcastMessage(TextFormat::BLUE. "$Started");
				foreach($this->players as $key=>$val)
				{
					$p=$this->getServer()->getPlayer($val["id"]);
					$p->setMaxHealth(25);
					$p->setHealth(25);
					$p->setGamemode(0); //Those who cheats
					$p->setLevel($this->level);
				}
				$this->all=count($this->players);
				break;
			}
		}
		if($this->gameStatus==2)
		{
			$this->lastTime--;
			if($this->lastTime<=0)
			{
				$this->gameStatus=3;
				$this->sendMessage("$Chest_Refilled");
				$this->lastTime=$this->gameTime;
				
			}
		}
		if($this->gameStatus==3 || $this->gameStatus==4)
		{
			if(count($this->players)==1)
			{
				$this->sendMessage(TextFormat::RED."[{$this->getConfig()->get("prefix")}] "); // DELETED
				foreach($this->players as &$pl)
				{
					$p=$this->getServer()->getPlayer($pl["id"]);
					Server::getInstance()->broadcastMessage(""); // Will be deleted soon, since it's a bad practice
					$p->setLevel($this->signlevel);
					$p->getInventory()->clearAll();
					$p->setMaxHealth(25);
					$p->setHealth(25);
					$p->teleport($this->signlevel->getSpawnLocation());
					unset($pl,$p);
				}
				$this->players=array();
				$this->gameStatus=0;
				$this->lastTime=0;
			}
			else if(count($this->players)==0)
			{
				Server::getInstance()->broadcastMessage(""); // DELETED
				$this->gameStatus=0;
				$this->lastTime=0;
				$this->ClearAllInv();
			}
		}
		if($this->gameStatus==3)
		{
			$this->lastTime--;
			switch($this->lastTime)
			{
			case 1:
				$this->sendMessage("$Deathmatch_starting §b1 seconds");
				break;
			case 2:
				$this->sendMessage("$Deathmatch_starting §b2 seconds");
				break;
			case 3:
				$this->sendMessage("$Deathmatch_starting §b3 seconds");
				break;
			case 4:
				$this->sendMessage("$Deathmatch_starting §b5 seconds");
				break;
			case 5:
				$this->sendMessage("$Deathmatch_starting §b5 seconds");
				break;	
			case 10:
				$this->sendMessage(TextFormat::YELLOW."[{$this->getConfig()->get("prefix")}] Deathmatch will start in 0:10.");
				break;
			case 0:
				$this->sendMessage(TextFormat::YELLOW."[{$this->getConfig()->get("prefix")}] $Deatchmatch_started);
				foreach($this->players as $pl)
				{
					$p=$this->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->level);
					$p->teleport($this->lastpos);
					unset($p,$pl);
				}
				$this->gameStatus=4;
				$this->lastTime=$this->endTime;
				break;
			}
		}
		if($this->gameStatus==4)
		{
			$this->lastTime--;
			switch($this->lastTime)
			{
			case 1:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Ending " .$this->lastTime. ".");
				break;
			case 2:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Ending " .$this->lastTime. ".");
				break;
			case 3:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Endingn " .$this->lastTime. ".");
				break;
			case 4:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Ending " .$this->lastTime. ".");
				break;
			case 5:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Ending " .$this->lastTime. ".");
				break;	
			case 10:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Ending 0:10.");
				break;
			//case 20:
			case 30:
				$this->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] $Match_Ending 0:30.");
				break;
			case 0:
				$this->sendMessage(TextFormat::BLUE. "[{$this->getConfig()->get("prefix")}] $Match_Ended");
				foreach($this->players as $pl)
				{
					$p=$this->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->signlevel);
					$p->teleport($this->signlevel->getSpawnLocation());
					$p->getInventory()->clearAll();
					$p->setMaxHealth(25);
					$p->setHealth(25);
					unset($p,$pl);
				}
				$this->players=array();
				$this->gameStatus=0;
				$this->lastTime=0;
				break;
			}
		}
		$this->changeStatusSign();
	}
	
	public function getMoney($name){
		return EconomyAPI::getInstance()->myMoney($name);
	}
	
	public function addMoney($name,$money){
		EconomyAPI::getInstance()->addMoney($name,$money);
		unset($name,$money);
	}
	
	public function setMoney($name,$money){
		EconomyAPI::getInstance()->setMoney($name,$money);
		unset($name,$money);
	}
	public function seeMoney($name){
		return PocketMoney::getInstance()->money($name);
	}
	
	public function grantMoney($name,$money){
		PocketMoney::getInstance()->grantMoney($name,$money);
		unset($name,$money);
	}
	public function killRate()
	{
		KillRate::getInstance()->stats($pl,$money);
	}
	
	public function changeStatusSign()
	{
		if(!isset($this->sign))
		{
			return false;
		}
		$Arena = $this->getConfig()->get("Arena");
		$sign=$this->signlevel->getTile($this->sign);
		if($sign instanceof Sign)
		{
			switch($this->gameStatus)
			{
			case 0:
				$sign->setText("§7[§aJoin§7] §b:§9".count($this->players)."§9/16","§l§fMap:§r §b$Arena","§eSG 1");
				break;
			case 1:
				$sign->setText("§7[§aJoin§7] §b:§9".count($this->players)."§9/24","§l§fMap:§r §b$Arena","§eSG 1");
				break;
			case 2:
				$sign->setText("§7[§5Running§7] §b:§9".count($this->players)."§9/24","§l§fMap§r §b$Arena","§eSG 1");
				break;
			case 3:
				$sign->setText("§7[§5Running§7] §b:§9".count($this->players)."§9/24","§l§fMap:§r §b$Arena","§eSG 1");
				break;
			case 4:
				$sign->setText("§7[§cDM§7] §b:§9".count($this->players)."§9/24","§l§fMap:§r §b$Arena","§eSG 1");
				break;
			}
		}
		unset($sign);
	}
	public function playerBlockTouch(PlayerInteractEvent $event){
		$player=$event->getPlayer();
		$username=$player->getName();
		$block=$event->getBlock();
		$levelname=$player->getLevel()->getFolderName();
		if(isset($this->SetStatus[$username]))
		{
			switch ($this->SetStatus[$username])
			{
			case 0:
				if($event->getBlock()->getID() != 63 && $event->getBlock()->getID() != 68)
				{
					$player->sendMessage(TextFormat::GREEN."[SurvivalGame] Click a sign to set join sign.");
				}
				$this->sign=array(
					"x" =>$block->getX(),
					"y" =>$block->getY(),
					"z" =>$block->getZ(),
					"level" =>$levelname);
				$this->config->set("sign",$this->sign);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Join Sign has been created");
				$player->sendMessage(TextFormat::GREEN."Please click on the 1st spawnpoint.");
				$this->signlevel=$this->getServer()->getLevelByName($this->config->get("sign")["level"]);
				$this->sign=new Vector3($this->sign["x"],$this->sign["y"],$this->sign["z"]);
				$this->changeStatusSign();
				break;
			case 1:
				$this->pos1=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos1",$this->pos1);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 1 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 2nd spawnpoint.");
				$this->pos1=new Vector3($this->pos1["x"]+0.5,$this->pos1["y"],$this->pos1["z"]+0.5);
				break;
			case 2:
				 $this->pos2=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos2",$this->pos2);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 2 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 3rd spawnpoint.");
				$this->pos2=new Vector3($this->pos2["x"]+0.5,$this->pos2["y"],$this->pos2["z"]+0.5);
				break;	
			case 3:
				$this->pos3=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos3",$this->pos3);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 3 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 4th spawnpoint");
				$this->pos3=new Vector3($this->pos3["x"]+0.5,$this->pos3["y"],$this->pos3["z"]+0.5);
				break;	
			case 4:
				$this->pos4=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos4",$this->pos4);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 4 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 5th spawnpoint.");
				$this->pos4=new Vector3($this->pos4["x"]+0.5,$this->pos4["y"],$this->pos4["z"]+0.5);
				break;
			case 5:
				$this->pos5=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos5",$this->pos5);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 5 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 6th spawnpoint.");
				$this->pos5=new Vector3($this->pos5["x"]+0.5,$this->pos5["y"],$this->pos5["z"]+0.5);
				break;
			case 6:
				$this->pos6=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos6",$this->pos6);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 6 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 7th spawnpoint.");
				$this->pos6=new Vector3($this->pos6["x"]+0.5,$this->pos6["y"],$this->pos6["z"]+0.5);
				break;
			case 7:
				$this->pos7=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos7",$this->pos7);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 7 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 8th spawnpoint.");
				$this->pos7=new Vector3($this->pos7["x"]+0.5,$this->pos7["y"],$this->pos7["z"]+0.5);
				break;	
			case 8:
				$this->pos8=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos8",$this->pos8);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 8 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 9th spawnpoint.");
				$this->pos8=new Vector3($this->pos8["x"]+0.5,$this->pos8["y"],$this->pos8["z"]+0.5);
				break;
			case 9:
				$this->pos9=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos9",$this->pos9);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 9 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 10th spawnpoint.");
				$this->pos9=new Vector3($this->pos9["x"]+0.5,$this->pos9["y"],$this->pos9["z"]+0.5);
				break;
			case 10:
				$this->pos10=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos10",$this->pos10);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 10 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 11th spawnpoint.");
				$this->pos10=new Vector3($this->pos10["x"]+0.5,$this->pos10["y"],$this->pos10["z"]+0.5);
				break;
			case 11:
				$this->pos11=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos11",$this->pos11);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 11 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 12th spawnpoint.");
				$this->pos11=new Vector3($this->pos11["x"]+0.5,$this->pos11["y"],$this->pos11["z"]+0.5);
				break;
			case 12:
				$this->pos12=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos12",$this->pos12);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 12 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 13th spawnpoint.");				
				$this->pos12=new Vector3($this->pos12["x"]+0.5,$this->pos12["y"],$this->pos12["z"]+0.5);
				break;
			case 13:
				$this->pos13=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos13",$this->pos13);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 13 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 14th spawnpoint.");				
				$this->pos13=new Vector3($this->pos13["x"]+0.5,$this->pos13["y"],$this->pos13["z"]+0.5);
				break;
			case 14:
				$this->pos14=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos14",$this->pos14);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 14 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 15th spawnpoint.");				
				$this->pos14=new Vector3($this->pos14["x"]+0.5,$this->pos14["y"],$this->pos14["z"]+0.5);
				break;
			case 15:
				$this->pos15=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos15",$this->pos15);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 15 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 16th spawnpoint.");				
				$this->pos15=new Vector3($this->pos15["x"]+0.5,$this->pos15["y"],$this->pos15["z"]+0.5);
				break;
			case 16:
				$this->pos16=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos16",$this->pos16);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 16 created!");
				$player->sendMessage(TextFormat::GREEN."Please click on the 17th spawnpoint.");				
				$this->pos16=new Vector3($this->pos16["x"]+0.5,$this->pos16["y"],$this->pos16["z"]+0.5);
				break;
			case 17:
 				$this->pos17=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos17",$this->pos17);
 				$this->config->save();
 				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 17 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 18th spawnpoint.");				
 				$this->pos17=new Vector3($this->pos17["x"]+0.5,$this->pos17["y"],$this->pos17["z"]+0.5);
 				break;
 			case 18:
 				$this->pos18=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos18",$this->pos18);
 				$this->config->save();
				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 18 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 19th spawnpoint.");				
 				$this->pos18=new Vector3($this->pos18["x"]+0.5,$this->pos18["y"],$this->pos18["z"]+0.5);
 				break;
 			case 19:
 				$this->pos19=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos19",$this->pos19);
 				$this->config->save();
 				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 19 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 20th spawnpoint.");				
 				$this->pos19=new Vector3($this->pos19["x"]+0.5,$this->pos19["y"],$this->pos19["z"]+0.5);
 				break;
 			case 20:
 				$this->pos20=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos20",$this->pos20);
 				$this->config->save();
 				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 20 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 21st spawnpoint.");				
 				$this->pos20=new Vector3($this->pos20["x"]+0.5,$this->pos20["y"],$this->pos20["z"]+0.5);
 				break;
 		        case 21:
 				$this->pos21=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos21",$this->pos21);
 				$this->config->save();
 				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 21 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 22nd spawnpoint.");				
 				$this->pos21=new Vector3($this->pos21["x"]+0.5,$this->pos21["y"],$this->pos21["z"]+0.5);
 				break;
 			case 22:
 				$this->pos22=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos22",$this->pos22);
 				$this->config->save();
 				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 22 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 23rd spawnpoint.");				
 				$this->pos22=new Vector3($this->pos22["x"]+0.5,$this->pos22["y"],$this->pos22["z"]+0.5);
 				break;
 			case 23:
 				$this->pos23=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos23",$this->pos23);
 				$this->config->save();
 				$this->SetStatus[$username]++;
 				$player->sendMessage(TextFormat::GREEN."Spawnpoint 23 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the 24th spawnpoint");				
 				$this->pos23=new Vector3($this->pos23["x"]+0.5,$this->pos23["y"],$this->pos23["z"]+0.5);
 				break;
 			case 24:
 				$this->pos24=array(
 					"x" =>$block->x,
 					"y" =>$block->y,
 					"z" =>$block->z,
 					"level" =>$levelname);
 				$this->config->set("pos24",$this->pos24);
 				$this->config->save();
 				$this->SetStatus[$username]++;		
				$player->sendMessage(TextFormat::GREEN."Spawnpoint 24 created!");
 				$player->sendMessage(TextFormat::GREEN."Please click on the deathmatch location");				
 				$this->pos24=new Vector3($this->pos24["x"]+0.5,$this->pos24["y"],$this->pos24["z"]+0.5);
 				break;		
			case 25:
			$this->lastpos=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("lastpos",$this->lastpos);
				$this->config->save();
				unset($this->SetStatus[$username]);
				$player->sendMessage(TextFormat::GREEN."Deathmatch spawnpoint created!");
				$this->saveResource("config.yml");
				$this->saveResource("points.yml");
				$player->sendMessage(TextFormat::GREEN."[Setup] The arena has been setup, You may now join!, all settings have been saved.");
				$this->lastpos=new Vector3($this->lastpos["x"]+0.5,$this->lastpos["y"],$this->lastpos["z"]+0.5);
				$this->level=$this->getServer()->getLevelByName($this->config->get("pos1")["level"]);					
			}
		}
		else
		{
			$sign=$event->getPlayer()->getLevel()->getTile($event->getBlock());
			if(isset($this->lastpos) && $this->lastpos!=array() && $sign instanceof Sign && $sign->getX()==$this->sign->x && $sign->getY()==$this->sign->y && $sign->getZ()==$this->sign->z && $event->getPlayer()->getLevel()->getFolderName()==$this->config->get("sign")["level"])
			{
				if(!$this->config->exists("lastpos"))
				{
					$event->getPlayer()->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] The game hasn't been set yet.");
				}else{
				if(!$event->getPlayer()->hasPermission("sg.touch.startgame"))
				{
					$event->getPlayer()->sendMessage("You don't have permission to join this game.");
				}else{
				
				if($this->gameStatus==0 || $this->gameStatus==1)
				{
					if(!isset($this->players[$event->getPlayer()->getName()]))
					{
						if(count($this->players)>=6)
						{
							$MatchFull == $this->getConfig()->get("Match-Full");
							$event->getPlayer()->sendMessage("[{$this->getConfig()->get("prefix")}] $MatchFull");
						}
						
						$this->players[$event->getPlayer()->getName()]=array("id"=>$event->getPlayer()->getName());
						$event->getPlayer()->sendMessage(TextFormat::BLUE. "[{$this->getConfig()->get("prefix")}] Welcome To SG-1");
						if($this->gameStatus==0 && count($this->players)>=2)
						{
							$this->gameStatus=1;
							$this->lastTime=$this->waitTime;
							$event->getPlayer()->sendMessage(TextFormat::YELLOW. "[{$this->getConfig()->get("prefix")}] The tournament will begin soon");
						}else{
						if(count($this->players)==8 && $this->gameStatus==1 && $this->lastTime>5)
						{
							$event->getPlayer()->sendMessage(TextFormat::GREEN. "[{$this->getConfig()->get("prefix")}] This match is already running!");
							$this->lastTime=5;
						}
							$this->changeStatusSign();
						}
					}
					else
					{
						$event->getPlayer()->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] Please leave server, to quit match!");
					}
				}
				else
				{
					$event->getPlayer()->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] This match already started!");
				}
			}
		}
	}
		}
	}
	
	public function PlayerQuit(PlayerQuitEvent $event){
		if(isset($this->players[$event->getPlayer()->getName()]))
		{	
			unset($this->players[$event->getPlayer()->getName()]);
			$playername = $event->getPlayer()->getName()
			$this->broadcastMessage("$playername has left the match")
			$this->changeStatusSign();
			if($this->gameStatus==1 && count($this->players)<2)
			{
				$this->gameStatus=0;
				$this->lastTime=0;
				$event->getPlayer()->sendMessage(TextFormat::RED. "[{$this->getConfig()->get("prefix")}] There aren't enough players. Countdown has stopped.");
				/*foreach($this->players as $pl)
				{
					$p=$this->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->signlevel);
					$p->teleport($this->signlevel->getSpawnLocation());
					unset($p,$pl);
				}*/
			}
		}
	}
	
	public function onDisable(){
			$this->getServer()->getLogger()->info(TextFormat::GREEN."[SG] Saving All Data...");
			$this->saveResource("config.yml");
        	        $this->saveResource("points.yml");
        	        $this->saveResource("config.yml");
			$this->getServer()->getLogger()->info(TextFormat::GREEN."[SG] Please wait...........");
			$this->getServer()->getLogger()->info(TextFormat::GREEN."[SG] All Data/Settings has been fixed");
	}
}
?>
