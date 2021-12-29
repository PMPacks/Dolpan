<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Feed extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "feed", "Feed yourself or other players", "[player]");
        $this->setPermission("tool.feed.use");
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
        $player->setFood(20);
        $player->getLevel()->addParticle(new HappyVillagerParticle($player->add(0, 2), 4));
        $player->sendMessage(TextFormat::GREEN . "You have been fed!");
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . $player->getDisplayName() . " has been fed!");
        }
        return true;
    }
}
