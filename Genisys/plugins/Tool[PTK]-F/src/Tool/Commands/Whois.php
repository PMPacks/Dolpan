<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Whois extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "whois", "Display player information", "<player>");
        $this->setPermission("tool.whois");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($alias[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        $data = $this->getAPI()->getPlayerInformation($player);
        if(!$sender->hasPermission("tool.geoip.show") || $player->hasPermission("tool.geoip.hide")){
            unset($data["location"]);
        }
        $m =TextFormat::AQUA . "Information:\n";
        foreach($data as $k => $v){
            $m .= TextFormat::GRAY . " * " . ucfirst($k) . ": $v";
        }
        $sender->sendMessage($m);
        return true;
    }
}