<?php
namespace Tool;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use Tool\Commands\AFK;
use Tool\Commands\Antioch;
use Tool\Commands\Back;
use Tool\Commands\BreakCommand;
use Tool\Commands\Broadcast;
use Tool\Commands\Burn;
use Tool\Commands\ClearInventory;
use Tool\Commands\Compass;
use Tool\Commands\Condense;
use Tool\Commands\Depth;
#use Tool\Commands\Economy\Balance;
#use Tool\Commands\Economy\Eco;
#use Tool\Commands\Economy\Pay;
#use Tool\Commands\Economy\Sell;
#use Tool\Commands\Economy\SetWorth;
#use Tool\Commands\Economy\Worth;
use Tool\Commands\Tool;
use Tool\Commands\Feed;
use Tool\Commands\Extinguish;
use Tool\Commands\Fly;
use Tool\Commands\GetPos;
use Tool\Commands\God;
use Tool\Commands\Heal;
use Tool\Commands\Home\DelHome;
use Tool\Commands\Home\Home;
use Tool\Commands\Home\SetHome;
use Tool\Commands\ItemCommand;
use Tool\Commands\ItemDB;
use Tool\Commands\Jump;
use Tool\Commands\KickAll;
use Tool\Commands\Kit2;
use Tool\Commands\Lightning;
use Tool\Commands\More;
use Tool\Commands\Mute;
use Tool\Commands\Near;
use Tool\Commands\Nick;
use Tool\Commands\Nuke;
use Tool\Commands\Override\Gamemode;
use Tool\Commands\Override\Kill;
use Tool\Commands\Override\Msg;
use Tool\Commands\Ping;
use Tool\Commands\PowerTool\PowerTool;
use Tool\Commands\PowerTool\PowerToolToggle;
use Tool\Commands\PTime;
use Tool\Commands\PvP;
use Tool\Commands\RealName;
use Tool\Commands\Repair;
use Tool\Commands\Reply;
use Tool\Commands\Seen;
use Tool\Commands\SetSpawn;
use Tool\Commands\Spawn;
use Tool\Commands\Sudo;
use Tool\Commands\Suicide;
use Tool\Commands\Teleport\TPA;
use Tool\Commands\Teleport\Dongy;
use Tool\Commands\Teleport\TPAHere;
use Tool\Commands\Teleport\TPAll;
use Tool\Commands\Teleport\Tuchoi;
use Tool\Commands\Teleport\TPHere;
use Tool\Commands\TempBan;
use Tool\Commands\Top;
use Tool\Commands\Unlimited;
use Tool\Commands\Vanish;
use Tool\Commands\Warp\DelWarp;
use Tool\Commands\Warp\Setwarp;
use Tool\Commands\Warp\Warp;
use Tool\Commands\World;
use Tool\EventHandlers\OtherEvents;
use Tool\EventHandlers\PlayerEvents;
use Tool\EventHandlers\SignEvents;
use Tool\Events\CreateAPIEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
    /** @var BaseAPI */
    private $api;

    public function onEnable(){
        // Before anything else...
        $this->checkConfig();

        // Custom API Setup :3
        $this->getServer()->getPluginManager()->callEvent($ev = new CreateAPIEvent($this, BaseAPI::class));
        $class = $ev->getClass();
        $this->api = new $class($this);

        // Other startup code...
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        
	$this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        $this->registerEvents();
        $this->registerCommands();
        if(count($p = $this->getServer()->getOnlinePlayers()) > 0){
            $this->getAPI()->createSession($p);
        }
        if($this->getAPI()->isUpdaterEnabled()){
            $this->getAPI()->fetchToolUpdate(false);
        }
        $this->getAPI()->scheduleAutoAFKSetter();
    }

    public function onDisable(){
        if(count($l = $this->getServer()->getOnlinePlayers()) > 0){
            $this->getAPI()->removeSession($l);
        }
        $this->getAPI()->__destruct();
    }

    /**
     * Function to register all the Event Handlers that Tool provide
     */
    public function registerEvents(){
        $this->getServer()->getPluginManager()->registerEvents(new OtherEvents($this->getAPI()), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvents($this->getAPI()), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SignEvents($this->getAPI()), $this);
    }

    /**
     * Function to register all Tool's commands...
     * And to override some default ones
     */
    private function registerCommands(){
        $commands = [
            new AFK($this->getAPI()),
            new Antioch($this->getAPI()),
            new Back($this->getAPI()),
            //new BigTreeCommand($this->getAPI()), TODO
            new BreakCommand($this->getAPI()),
            new Broadcast($this->getAPI()),
            new Burn($this->getAPI()),
            new ClearInventory($this->getAPI()),
            new Compass($this->getAPI()),
            new Condense($this->getAPI()),
            new Depth($this->getAPI()),
            new Tool($this->getAPI()),
            new Extinguish($this->getAPI()),
            new Fly($this->getAPI()),
            new GetPos($this->getAPI()),
            new God($this->getAPI()),
            //new Hat($this->getAPI()), TODO: Implement when MCPE implements "Block-Hat rendering"
            new Heal($this->getAPI()),
            new ItemCommand($this->getAPI()),
            new ItemDB($this->getAPI()),
            new Jump($this->getAPI()),
            new KickAll($this->getAPI()),
            new Kit2($this->getAPI()),
            new Lightning($this->getAPI()),
            new More($this->getAPI()),
            new Mute($this->getAPI()),
            new Near($this->getAPI()),
            new Nick($this->getAPI()),
            new Nuke($this->getAPI()),
            new Ping($this->getAPI()),
            new Feed($this->getAPI()),
            new PTime($this->getAPI()),
            new PvP($this->getAPI()),
            new RealName($this->getAPI()),
            new Repair($this->getAPI()),
            new Seen($this->getAPI()),
            new SetSpawn($this->getAPI()),
            new Spawn($this->getAPI()),
            //new Speed($this->getAPI()), TODO
            new Sudo($this->getAPI()),
            new Suicide($this->getAPI()),
            new TempBan($this->getAPI()),
            new Top($this->getAPI()),
            //new TreeCommand($this->getAPI()), TODO
            new Unlimited($this->getAPI()),
            new Vanish($this->getAPI()),
            //new Whois($this->getAPI()), TODO
            new World($this->getAPI()),

            // Economy
            //new Balance($this->getAPI()),
            //new Eco($this->getAPI()),
            //new Pay($this->getAPI()),
            //new Sell($this->getAPI()),
            //new SetWorth($this->getAPI()),
            //new Worth($this->getAPI()),
            
            // Override
            new Gamemode($this->getAPI()),
            new Kill($this->getAPI()),
            
            // Messages
            new Msg($this->getAPI()),
            new Reply($this->getAPI()),

            // Homes
            new DelHome($this->getAPI()),
            new Home($this->getAPI()),
            new SetHome($this->getAPI()),

            // Powertool
            new PowerTool($this->getAPI()),

            // Teleporting
            new TPA($this->getAPI()),
            new Dongy($this->getAPI()),
            new TPAHere($this->getAPI()),
            new TPAll($this->getAPI()),
            new Tuchoi($this->getAPI()),
            new TPHere($this->getAPI()),

            // Warps
            new DelWarp($this->getAPI()),
            new Setwarp($this->getAPI()),
            new Warp($this->getAPI())
            ];
        
        $aliased = [];
        foreach($commands as $cmd){
            /** @var BaseCommand $cmd */
            $commands[$cmd->getName()] = $cmd;
            $aliased[$cmd->getName()] = $cmd->getName();
            foreach($cmd->getAliases() as $alias){
                $aliased[$alias] = $cmd->getName();
            }
        }
        $cfg = $this->getConfig()->get("commands", []);
        foreach($cfg as $del){
            if(isset($alias[$del])){
                unset($commands[$alias[$del]]);
            }else{
                $this->getLogger()->debug("\"$del\" command not found inside Tool, skipping...");
            }
        }
        $this->getServer()->getCommandMap()->registerAll("Tool", $commands);
    }

    public function checkConfig(){
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveDefaultConfig();
        }
        //$this->saveResource("Economy.yml");
        $this->saveResource("Kit2s.yml");
        $this->saveResource("Warps.yml");
        $cfg = $this->getConfig();

        if(!$cfg->exists("version") || $cfg->get("version") !== "0.0.2"){
            $this->getLogger()->debug(TextFormat::RED . "An invalid config file was found, generating a new one...");
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config.yml.old");
            $this->saveDefaultConfig();
            $cfg = $this->getConfig();
        }

        $booleans = ["enable-custom-colors"];
        foreach($booleans as $key){
            $value = null;
            if(!$cfg->exists($key) || !is_bool($cfg->get($key))){
                switch($key){
                    // Properties to auto set true
                    case "safe-afk":
                        $value = true;
                        break;
                    // Properties to auto set false
                    case "enable-custom-colors":
                        $value = false;
                        break;
                }
            }
            if($value !== null){
                $cfg->set($key, $value);
            }
        }

        $integers = ["oversized-stacks", "near-radius-limit", "near-default-radius"];
        foreach($integers as $key){
            $value = null;
            if(!is_numeric($cfg->get($key))){
                switch($key){
                    case "auto-afk-kick":
                        $value = 300;
                        break;
                    case "oversized-stacks":
                        $value = 64;
                        break;
                    case "near-radius-limit":
                        $value = 200;
                        break;
                    case "near-default-radius":
                        $value = 100;
                        break;
                }
            }
            if($value !== null){
                $cfg->set($key, $value);
            }
        }

        $afk = ["safe", "auto-set", "auto-broadcast", "auto-kick", "broadcast"];
        foreach($afk as $key){
            $value = null;
            $k = $this->getConfig()->getNested("afk." . $key);
            switch($key){
                case "safe":
                case "auto-broadcast":
                case "broadcast":
                    if(!is_bool($k)){
                        $value = true;
                    }
                    break;
                case "auto-set":
                case "auto-kick":
                    if(!is_int($k)){
                        $value = 300;
                    }
                    break;
            }
            if($value !== null){
                $this->getConfig()->setNested("afk." . $key, $value);
            }
        }

        $updater = ["enabled", "time-interval", "warn-console", "warn-players", "channel"];
        foreach($updater as $key){
            $value = null;
            $k = $this->getConfig()->getNested("updater." . $key);
            switch($key){
                case "time-interval":
                    if(!is_int($k)){
                        $value = 1800;
                    }
                    break;
                case "enabled":
                case "warn-console":
                case "warn-players":
                    if(!is_bool($k)){
                        $value = true;
                    }
                    break;
                case "channel":
                    if(!is_string($k) || ($k !== "stable" && $k !== "beta" && $k !== "development")){
                        $value = "stable";
                    }
            }
            if($value !== null){
                $this->getConfig()->setNested("updater." . $key, $value);
            }
        }
    }

    /**
     * @return BaseAPI
     */
    public function getAPI(): BaseAPI{
        return $this->api;
    }
}
