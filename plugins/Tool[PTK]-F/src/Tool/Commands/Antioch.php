<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Antioch extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "antioch", "Holy hand grenade", null, false, ["grenade", "tnt"]);
        $this->setPermission("tool.antioch");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->antioch($sender)){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Cannot throw the grenade, there isn't a near valid block");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "Grenade threw!");
        return true;
    }
}