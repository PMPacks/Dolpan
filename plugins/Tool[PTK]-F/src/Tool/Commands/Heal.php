<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\level\particle\HeartParticle;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Heal extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "heal", "Hồi máu cho bạn hoặc cho người chơi khác", "[player]");
        $this->setPermission("tool.heal.use");
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
        if(isset($args[0]) && !($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        $player->heal($player->getMaxHealth(), new EntityRegainHealthEvent($player, $player->getMaxHealth() - $player->getHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
        $player->getLevel()->addParticle(new HeartParticle($player->add(0, 2), 4));
        $player->sendMessage(TextFormat::GREEN . "§b→§aBạn đã được hồi đầy máu!");
        if($player !== $sender){
            $sender->sendMessagey("§b→§aNgười chơi " . TextFormat::AQUA . $player->getDisplayName() . " §ađã được hồi đầy máu!");
        }
        return true;
    }
}
