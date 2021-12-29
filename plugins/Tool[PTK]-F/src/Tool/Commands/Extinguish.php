<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Extinguish extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "extinguish", "Extinguish a player", "[player]", true, ["ext"]);
        $this->setPermission("tool.extinguish.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("tool.extinguish.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        $player->extinguish();
        $sender->sendMessage(TextFormat::AQUA . ($player === $sender ? "You were" : $player->getDisplayName() . "has been") . TextFormat::AQUA . " extinguished");
        return true;
    }
}
