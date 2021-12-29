<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Back extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "back", "Di chuyển đến vị trí trước đó của bạn", null, false, ["return"]);
        $this->setPermission("tool.back.use");
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
        if(!($pos = $this->getAPI()->getLastPlayerPosition($sender))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Không có vị trí trước đó!");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "§b→§aĐang dịch chuyển...");
        $sender->teleport($pos);
        return true;
    }
} 