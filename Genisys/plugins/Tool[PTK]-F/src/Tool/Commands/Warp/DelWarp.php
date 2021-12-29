<?php
namespace Tool\Commands\Warp;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DelWarp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "delwarp", "Delete a warp", "<name>", true, ["remwarp", "removewarp", "closewarp"]);
        $this->setPermission("tool.delwarp");
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
        if($this->getAPI()->getToolPlugin()->getConfig()->get("warps") !== true) {
            $sender->sendMessage(TextFormat::RED . "Lệnh này đã bị tắt!!");
            return false;
        }
        if(count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->warpExists($args[0])){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Warp doesn't exists");
            return false;
        }
        if(!$sender->hasPermission("tool.warp.override.*") && !$sender->hasPermission("tool.warp.override.$args[0]")){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You can't delete this warp");
            return false;
        }
        $this->getAPI()->removeWarp($args[0]);
        $sender->sendMessage(TextFormat::GREEN . "Warp successfully removed!");
        return true;
    }
} 