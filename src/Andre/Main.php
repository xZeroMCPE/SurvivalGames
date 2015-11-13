
/*
 SurvivalGames Made By: Andre_The_Gamer
*\

namespace Andre;

/* 
 THINGS PLUGIN WILL USE
*\

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
use ResetChest\Main as ResetChest;
use killrate\Main as KillRate;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
#####

class Main extends PluginBase implements Listener
{	
	public $data;
	
	private static $object = null;
	
	public static function getInstance(){
		return self::$object;
	}

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
                $this->config = (new Config($this->getDataFolder()."config.yml", Config::YAML))->getAll();
                $this->saveDefaultConfig();
	}
        public function onJoin(PlayerJoinEvent $event){
        $JoinMessage == $this->getConfig()->get("Player_Join_Server");
        $event->getPlayer->sendMessage("$PlayerJoinServer");
        }
         public function playerBlockTouch(PlayerInteractEvent $event){   // Oak Log will be use as a join block when step on.
        if($event->getBlock()->getID() == 68 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 17){
            $sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
            if(!($sign instanceof Sign)){
                return;
            }
            $sign = $sign->getText();
            if($sign[0]=='[SurvivalGame]'){
            $ArenaJoin == $this->getConfig()->get("ArenaJoin");
            $Arena == $this->getConfig()->get("Arena");
            $event->getPlayer->sendMessage("$ArenaJoin");
            $event->getPlayer->sendMessage("$ArenaJoined");
            $event->getPlayer->sendMessage(" -=-=-=-=-= ")
            $event->getPlayer->sendMessage("You Have Joined: SG-1")
            $event->getPlayer->sendMessage("Map: $Arena");
            $event->getPlayer->sendMessage(" -=-=-=-=-= ");
        
        }
        public function onBlockPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
	$world == $this->getConfig()->get("Arena");
			if($player->hasPermission("survivalgame.edit")){
				return true;
			}elseif($player->getLevel()->getName() == $world){
				$player->sendMessage("[Edit] Sorry, you can't break that here.");
				$event->setCancelled();
	}
	public function onBlockBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
	$world == $this->getConfig()->get("Arena");
			if($player->hasPermission("survivalgame.edit")){
				return true;
			}elseif($player->getLevel()->getName() == $world){
				$player->sendMessage("[Edit] Sorry, you can't place that here.");
				$event->setCancelled();
	public function onDeath(PlayerDeathEvent $event){
	 $event->getKiller->sendMessage("You Have Killed A Player")
}
}
