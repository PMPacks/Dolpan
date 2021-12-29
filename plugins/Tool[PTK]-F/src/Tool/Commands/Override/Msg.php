<?php
namespace Tool\Commands\Override;

use Tool\BaseFiles\BaseAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Msg extends BaseOverrideCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tell", "Nói chuyện riêng với ai đó", "<Tên Người Chơi> <Tin Nhắn ...>", true, ["t", "w", "chat"]);
        $this->setPermission("tool.msg");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $t = array_shift($args);
        if(strtolower($t) !== "console" && strtolower($t) !== "rcon"){
            $t = $this->getAPI()->getPlayer($t);
            if(!$t){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
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