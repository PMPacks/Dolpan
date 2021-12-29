<?php
namespace Tool\Commands\PowerTool;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PowerToolToggle extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "powertooltoggle", "Xóa tất cả lệnh hoặc tin nhắn khỏi tất cả vật phẩm", null, false, ["ptt", "pttoggle"]);
        $this->setPermission("tool.powertooltoggle");
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
        if($this->getAPI()->getToolPlugin()->getConfig()->get("powertool") !== true) {
            $sender->sendMessage(TextFormat::RED . "Lệnh này đã bị tắt!!");
            return false;
        }
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $this->getAPI()->disablePowerTool($sender);
        $sender->sendMessage(TextFormat::YELLOW . "§b→§aĐã xóa tất cả lệnh và tin nhắn khỏi tất cả vật phẩm!");
        return true;
    }
} 