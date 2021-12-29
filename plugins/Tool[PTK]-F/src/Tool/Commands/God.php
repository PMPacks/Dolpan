<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class God extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "god", "Bật hoặc tắt chế độ bất tử", "[player]", true, ["godmode", "tgm"]);
        $this->setPermission("tool.god.use");
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
            if(!$sender->hasPermission("tool.god.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        $this->getAPI()->switchGodMode($player);
        $player->sendMessage("§b→§aNgười chơi " . ($m = TextFormat::AQUA . $this->getAPI()->isGod($player) ? "§ađã được bật chế độ bất tử!" : "§ađã được tắt chế độ bất tử!"));
        if($player !== $sender){
            $sender->sendMessage(TextFormat::AQUA . "§b→§aBạn $m!");
        }
        return true;
    }
}
