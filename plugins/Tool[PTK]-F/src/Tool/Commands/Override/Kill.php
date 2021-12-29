<?php
namespace Tool\Commands\Override;

use Tool\BaseFiles\BaseAPI;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Kill extends BaseOverrideCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "kill", "Giết ai đó", "[Tên Người Chơi]");
        $this->setPermission("tool.kill.use");
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
        if(!$sender instanceof Player && count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("tool.kill.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }
            if(!($player = $this->getAPI()->getPlayer($args[0])) instanceof Player){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        if($this->getAPI()->isGod($player)){
            $sender->sendMessage(TextFormat::RED . "[Cảnh Báo Đặc Biệt] Người chơi§b " . $args[0] . "§c không thể bị giết!");
            return false;
        }
        $sender->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, ($player->getHealth())));
        if($ev->isCancelled()){
            return true;
        }

        $player->setLastDamageCause($ev);
        $player->setHealth(0);
        $player->sendMessage("§b→§aBạn mới bị giết bởi ma thuật á đù.");
        return true;
    }
} 