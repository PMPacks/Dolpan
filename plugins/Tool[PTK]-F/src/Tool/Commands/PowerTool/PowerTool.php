<?php
namespace Tool\Commands\PowerTool;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PowerTool extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "powertool", "Thêm 1 câu lệnh hoặc thêm 1 tin nhắn vào vật phẩm bạn đang cầm", "<Câu Lệnh|c:Tin Nhắn> <L|D>", false, ["pt"]);
        $this->setPermission("tool.powertool.use");
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->getId() === Item::AIR){
            $sender->sendMessage(TextFormat::RED . "[Lỗi] Bạn phải cầm 1 vật phẩm vào đó.");
            return false;
        }

        if(count($args) === 0){
            if(!$this->getAPI()->getPowerToolItemCommand($sender, $item) && !$this->getAPI()->getPowerToolItemCommands($sender, $item) && !$this->getAPI()->getPowerToolItemChatMacro($sender, $item)){
                $this->sendUsage($sender, $alias);
                return false;
            }
            if($this->getAPI()->getPowerToolItemCommand($sender, $item) !== false){
                $sender->sendMessage(TextFormat::GREEN . "§b→§aCâu lệnh đã bị xóa khỏi vật phẩm.");
            }elseif($this->getAPI()->getPowerToolItemCommands($sender, $item) !== false){
                $sender->sendMessage(TextFormat::GREEN . "§b→§aCác câu lệnh đã bị xóa khỏi vật phẩm.");
            }
            if($this->getAPI()->getPowerToolItemChatMacro($sender, $item) !== false){
                $sender->sendMessage(TextFormat::GREEN . "§b→§aTin nhắn đã bị xóa khỏi vật phẩm.");
            }
            $this->getAPI()->disablePowerToolItem($sender, $item);
        }else{
            if($args[0] === "pt" || $args[0] === "ptt" || $args[0] === "powertool" || $args[0] === "powertooltoggle"){
                $sender->sendMessage(TextFormat::RED . "§b→§aBạn không thể thêm lệnh này!");
                return false;
            }
            $command = implode(" ", $args);
            if(stripos($command, "c:") !== false){ //Create a chat macro
                $c = substr($command, 2);
                $this->getAPI()->setPowerToolItemChatMacro($sender, $item, $c);
                $sender->sendMessage(TextFormat::GREEN . "§b→§aBạn không thể thêm tin nhắn này!");
            }elseif(stripos($command, "a:") !== false){
                if(!$sender->hasPermission("tool.powertool.append")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $commands = substr($command, 2);
                $commands = explode(";", $commands);
                $this->getAPI()->setPowerToolItemCommands($sender, $item, $commands);
                $sender->sendMessage(TextFormat::GREEN . "§b→§aBạn đã thêm câu lệnh thành công!");
            }elseif(stripos($command, "r:") !== false){
                if(!$sender->hasPermission("tool.powertool.append")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $command = substr($command, 2);
                $this->getAPI()->removePowerToolItemCommand($sender, $item, $command);
                $sender->sendMessage(TextFormat::YELLOW . "§b→§aBạn đã xóa câu lệnh thành công!");
            }elseif(count($args) === 1 && (($a = strtolower($args[0])) === "l" || $a === "d")){
                switch($a){
                    case "l":
                        $commands = false;
                        if($this->getAPI()->getPowerToolItemCommand($sender, $item) !== false){
                            $commands = $this->getAPI()->getPowerToolItemCommand($sender, $item);
                        }elseif($this->getAPI()->getPowerToolItemCommands($sender, $item) !== false){
                            $commands = $this->getAPI()->getPowerToolItemCommand($sender, $item);
                        }
                        $list = "§b=====»§aCác Câu Lệnh§b«=====";
                        if($commands === false){
                            $list .= "\n" . TextFormat::ITALIC . "§c=====»§aKhông có câu lệnh nào ở vật phẩm này§c«=====";
                        }else{
                            if(!is_array($commands)){
                                $list .= "\n§b♦§a /$commands";
                            }else{
                                foreach($commands as $c){
                                    $list .= "\n§b♦§a /$c";
                                }
                            }
                        }
                        $chat_macro = $this->getAPI()->getPowerToolItemChatMacro($sender, $item);
                        $list .= "\n§b=====»§aCác Tin Nhắn§b«=====";
                        if($chat_macro === false){
                            $list .= "\n" . TextFormat::ITALIC . "§c=====»§aKhông có tin nhắn nào ở vật phẩm này§c«=====";
                        }else{
                            $list .= "\n§b♦§a $chat_macro";
                        }
                        $list .= "\n§b=====»§aHết Danh Sách§b«=====";
                        $sender->sendMessage($list);
                        return true;
                        break;
                    case "d":
                        if(!$this->getAPI()->getPowerToolItemCommand($sender, $item)){
                            $this->sendUsage($sender, $alias);
                            return false;
                        }
                        $this->getAPI()->disablePowerToolItem($sender, $item);
                        $sender->sendMessage(TextFormat::GREEN . "§b→§aĐã xóa thành công lệnh ở vật phẩm.");
                        return true;
                        break;
                }
            }else{
                $this->getAPI()->setPowerToolItemCommand($sender, $item, $command);
                $sender->sendMessage(TextFormat::GREEN . "§b→§aĐã thêm thành công lệnh vào vật phẩm!");
            }
        }
        return true;
    }
} 
