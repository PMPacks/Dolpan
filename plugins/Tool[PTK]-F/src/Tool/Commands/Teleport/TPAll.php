<?php
namespace Tool\Commands\Teleport;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAll extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpall", "Dịch chuyển tất cả người chơi đến ai đó", "[player]");
        $this->setPermission("tool.tpall");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0]) && !($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        foreach($this->getAPI()->getServer()->getOnlinePlayers() as $p){
            if($p !== $player){
                $p->teleport($player);
                $p->sendMessage("§b→§aBạn đang được dịch chuyển đến người chơi " . TextFormat::AQUA . $player->getDisplayName() . "§a...");
            }
        }
        $player->sendMessage(TextFormat::YELLOW . "§b→§aĐang dịch chuyển tất cả người chơi đến chỗ bạn...");
        return true;
    }
} 