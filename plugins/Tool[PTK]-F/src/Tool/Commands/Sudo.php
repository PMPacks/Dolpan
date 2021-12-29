<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class Sudo extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sudo", "Run a command as another player", "<player> <command line|c:<chat message>");
        $this->setPermission("tool.sudo.use");
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
        if(!($player = $this->getAPI()->getPlayer(array_shift($args)))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }elseif($player->hasPermission("tool.sudo.exempt")){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] " . $player->getName() . " cannot be sudo'ed");
            return false;
        }

        $v = implode(" ", $args);
        if(substr($v, 0, 2) === "c:"){
            $sender->sendMessage(TextFormat::GREEN . "Sending message as " .  $player->getDisplayName());
            $this->getAPI()->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, substr($v, 2)));
            if(!$ev->isCancelled()){
                $this->getAPI()->getServer()->broadcastMessage(\sprintf($ev->getFormat(), $ev->getPlayer()->getDisplayName(), $ev->getMessage()), $ev->getRecipients());
            }
        }else{
            $sender->sendMessage(TextFormat::AQUA . "Command ran as " .  $player->getDisplayName());
            $this->getAPI()->getServer()->dispatchCommand($player, $v);
        }
        return true;
    }
} 
