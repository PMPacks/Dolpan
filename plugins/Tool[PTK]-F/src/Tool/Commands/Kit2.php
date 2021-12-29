<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Kit2 extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "kit2", "Get a pre-defined kit2!", "[name] [player]", "[<name> <player>]", ["kit22"]);
        $this->setPermission("tool.kit2.use");
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
        if(count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(count($args) === 0){
            if(($list = $this->getAPI()->kit2List(false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "There are no Kit2s currently available");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "Available kit22:\n" . $list);
            return true;
        }elseif(!isset($args[1]) && !$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!($kit2 = $this->getAPI()->getKit2($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Kit2 doesn't exist");
            return false;
        }
        switch(count($args)){
            case 1:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                if(!$sender->hasPermission("tool.kit22.*") && !$sender->hasPermission("tool.kit22." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You can't get this kit2");
                    return false;
                }
                $kit2->giveToPlayer($sender);
                $sender->sendMessage(TextFormat::GREEN . "Getting kit2 " . TextFormat::AQUA . $kit2->getName() . "...");
                break;
            case 2:
                if(!$sender->hasPermission("tool.kit2.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!$sender->hasPermission("tool.kit22.*") && !$sender->hasPermission("tool.kit22." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You can't get this kit2");
                    return false;
                }
                if(!($player = $this->getAPI()->getPlayer($args[1]))){
                    $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                    return false;
                }
                $kit2->giveToPlayer($player);
                $player->sendMessage(TextFormat::GREEN . "Getting kit2 " . TextFormat::AQUA . $kit2->getName() . "...");
                $sender->sendMessage(TextFormat::GREEN . "Giving " . TextFormat::YELLOW . $player->getDisplayName() . TextFormat::GREEN . " kit2 " . TextFormat::AQUA . $kit2->getName() . TextFormat::GREEN . "...");
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("tool.kit2.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        if(!$sender->hasPermission("tool.kit22.*") && !$sender->hasPermission("tool.kit22." . strtolower($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You can't get this kit2");
            return false;
        }
        $player->sendMessage(TextFormat::GREEN . "Getting kit2 " . TextFormat::AQUA . $kit2->getName() . "...");
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . "Giving " . TextFormat::YELLOW . $player->getDisplayName() . TextFormat::GREEN . " kit2 " . TextFormat::AQUA . $kit2->getName() . TextFormat::GREEN . "...");
        }
        return true;
    }
}