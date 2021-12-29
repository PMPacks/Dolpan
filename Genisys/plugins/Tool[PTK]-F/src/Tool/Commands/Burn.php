<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Burn extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "burn", "Set a player on fire", "<player> <seconds>");
        $this->setPermission("tool.burn");
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
        if(count($args) != 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        if(!is_numeric($time = $args[1])){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid burning time");
            return false;
        }
        $player->setOnFire($time);
        $sender->sendMessage(TextFormat::YELLOW . $player->getDisplayName() . " is now on fire!");
        return true;
    }
}
