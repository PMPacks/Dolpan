<?php

namespace PTK\Bar;

use pocketmine\Server;
use PTK\Bar\Tasks\PanelTask;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use _64FF00\PurePerms\PurePerms;
use pocketmine\FactionsPro\FactionMain;

class Main extends PluginBase implements Listener {

 	
 
 	public function onEnable() {
 		$this->getServer()->getPluginManager()->registerEvents($this, $this);
 		$this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
 		$this->PurePerms = $this->getServer()->getPluginManager()->getPlugin('PurePerms');
 		$this->FactionPro = $this->getServer()->getPluginManager()->getPlugin("FactionsPro");
      $this->getServer()->getScheduler()->scheduleRepeatingTask(new PanelTask($this), 10);
		$this->getLogger()->info("§aĐã bật");
      @mkdir($this->getDataFolder());
      $this->saveDefaultConfig();
      $cfg = new Config($this->getDataFolder()."config.yml",Config::YAML);
     
	}
 	
	public function onPanel() {
        foreach($this->getServer()->getOnlinePlayers() as $p) {
			   $player = $p->getPlayer()->getName();
			$x = floor($p->getX());
			$y = floor($p->getY());
			$z = floor($p->getZ());
            $online = count(Server::getInstance()->getOnlinePlayers());
            $max = $this->getServer()->getMaxPlayers(); 
            $tps = $this->getServer()->getTicksPerSecond();
            $item = $p->getInventory()->getItemInHand();					
            $id = $item->getId();					
            $meta = $item->getDamage();
            $rank = $this->PurePerms->getUserDataMgr()->getGroup($p)->getName();
            $fac = $this->FactionPro->getPlayerFaction($player);
		    $money = $this->EconomyAPI->mymoney($player);
			   $t = str_repeat(" ", 85);
           $p->sendTip($t. "§l§e•§d=====§e[".$this->getConfig()->get("nameserver")."]§d=====§r\n" .$t. "§l§e•§cT§6ê§en§f: §b" .$player."§r\n" .$t. "§l§e•§cO§6n§el§ai§bn§9e§f: §b" . $online."§f/§b".$max."§r\n" .$t. "§l§e•§cT§6i§eề§an§f: §b".$money." Xu§r\n".$t."§l§e•§cC§6h§eứ§ac§f: §b".$rank."§r\n".$t."§l§e•§cH§6ộ§ei§f: §b".$fac."§r\n".$t."§l§e•§cI§6T§eE§aM§f: §b".$id.":".$meta."§r\n".$t."§l§e•§cX§6/§eY§6/§aZ§f: §c".$x."§6/§e".$y."§6/§a".$z." §r\n".$t."§l§e•§d=================§r".str_repeat("\n", 20));
			
		}
    }

	public function onDisable() {
		$this->getLogger()->info("§4BarTip closed");
	}
}