<?php
namespace Tool\Commands\Teleport;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPHere extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tphere", "Dịch chuyển ai đó đến bạn", "<Tên Người Chơi>", false, ["h"]);
        $this->setPermission("tool.tphere");
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
        if($this->getAPI()->getToolPlugin()->getConfig()->get("teleporting") !== true) {
            $sender->sendMessage(TextFormat::RED . "Lệnh này đã bị tắt!!");
            return false;
        }
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        $player->teleport($sender);
        $player->sendMessage("§b→§aĐang dịch chuyển bạn tới người chơi " . TextFormat::AQUA . $sender->getDisplayName() . "§a...");
        $sender->sendMessage("§b→§aĐang dịch chuyển người chơi " . TextFormat::AQUA . $player->getDisplayName() . " §ađến chỗ của bạn...");
        return true;
    }
} 