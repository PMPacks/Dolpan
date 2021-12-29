<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Reply extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "reply", "Trả lời lại người mới nói chuyện với bạn", "<Tin Nhắn...>", true, ["r"]);
        $this->setPermission("tool.reply");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($t = $this->getAPI()->getQuickReply($sender))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] No target available for QuickReply");
            return false;
        }
        if(strtolower($t) !== "console" && strtolower($t) !== "rcon"){
            if(!($t = $this->getAPI()->getPlayer($t))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn chưa nói chuyện riêng với ai cả!");
                $this->getAPI()->removeQuickReply($sender);
                return false;
            }
        }
        $sender->sendMessage(TextFormat::YELLOW . "§6♦§aTin nhắn từ §btôi §e→§b " . ($t instanceof Player ? $t->getDisplayName() : $t) . "§6♦" . TextFormat::GREEN . " " . implode(" ", $args));
        $m = TextFormat::YELLOW . "§6♦§aTin nhắn từ§b " . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " §e→§b tôi]" . TextFormat::GREEN . " " . implode(" ", $args);
        if($t instanceof Player){
            $t->sendMessage($m);
        }else{
            $this->getPlugin()->getLogger()->info($m);
        }
        $this->getAPI()->setQuickReply(($t instanceof Player ? $t : ($t === "console" ? new ConsoleCommandSender() : new RemoteConsoleCommandSender())), $sender);
        return true;
    }
}