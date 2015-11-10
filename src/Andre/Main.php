
####
# SurvivalGames Made By: Andre_The_Gamer
####

namespace Andre;

### 
## THINGS PLUGIN WILL USE
###
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
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this,"gameTimber"]),20);
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
		)); if($this->config->exists("lastpos"))
	}
	}
