<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TempBan extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tempban", "Temporary bans the specified player", "<player> <time...> [reason ...]");
        $this->setPermission("tool.tempban");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $name = array_shift($args);
        if(!($info = $this->getAPI()->stringToTimestamp(implode(" ", $args)))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Please specify a valid time");
            return false;
        }
        /** @var \DateTime $date */
        $date = $info[0];
        $reason = $info[1];
        if(($player = $this->getAPI()->getPlayer($name)) instanceof Player){
            if($player->hasPermission("tool.ban.exempt")){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] " . $player->getDisplayName() . " can't be banned");
                return false;
            }else{
                $player->kick(TextFormat::RED . "Banned until " . TextFormat::AQUA . $date->format("l, F j, Y") . TextFormat::RED . " at " . TextFormat::AQUA . $date->format("h:ia") . (trim($reason) !== "" ? TextFormat::YELLOW . "\nReason: " . TextFormat::RESET . $reason : ""), false);
            }
        }
        $sender->getServer()->getNameBans()->addBan(($player instanceof Player ? $player->getName() : $name), (trim($reason) !== "" ? $reason : null), $date, "toolpe");
        $this->broadcastCommandMessage($sender, "Banned player " . ($player instanceof Player ? $player->getName() : $name) . " until " . $date->format("l, F j, Y") . " at " . $date->format("h:ia") . (trim($reason) !== "" ? TextFormat::YELLOW . " Reason: " . TextFormat::RESET . $reason : ""));
        return true;
    }
}
