<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class RealName extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "realname", "Check the realname of a player", "<player>");
        $this->setPermission("tool.realname");
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
        if(count($args) != 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        $sender->sendMessage(TextFormat::YELLOW .  $player->getDisplayName() . (substr($player->getName(), -1, 1) === "s" ? "'" : "'s") . " realname is: " . TextFormat::RED . $player->getName());
        return true;
    }
}
