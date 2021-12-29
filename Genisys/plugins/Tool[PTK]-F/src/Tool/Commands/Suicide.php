<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class Suicide extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "suicide", "Giết chính bạn", null, true, ["die"]);
        $this->setPermission("tool.suicide");
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
        $sender->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($sender, EntityDamageEvent::CAUSE_SUICIDE, ($sender->getHealth())));
        if($ev->isCancelled()){
            return true;
        }
        $sender->setLastDamageCause($ev);
        $sender->setHealth(0);
        $sender->sendMessage("§b→§aBạn mới bị giết bởi ma thuật á đù.");
        return true;
    }
} 