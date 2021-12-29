<?php
namespace Tool\Commands\Teleport;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPDeny extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpdeny", "Decline a Teleport Request", "[player]", false, ["tpno"]);
        $this->setPermission("tool.tpdeny");
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
            $sender->sendMessage(TextFormat::RED . "This command has been disabled!");
            return false;
        }
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($request = $this->getAPI()->hasARequest($sender))){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have any request yet");
            return false;
        }
        switch(count($args)){
            case 0:
                if(!($player = $this->getAPI()->getPlayer(($name = $this->getAPI()->getLatestRequest($sender))))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Request unavailable");
                    return false;
                }
                break;
            case 1:
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                if(!($request = $this->getAPI()->hasARequestFrom($sender, $player))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You don't have any requests from " . TextFormat::AQUA . $player->getName());
                    return false;
                }
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        $player->sendMessage(TextFormat::AQUA . $sender->getDisplayName() . TextFormat::RED . " đã không cho bạn đến chỗ của người chơi này");
        $sender->sendMessage(TextFormat::GREEN . "Đã từ chối " . TextFormat::AQUA . $player->getDisplayName() . (substr($player->getDisplayName(), -1, 1) === "s" ? "'" : "'s") . TextFormat::RED . " được đến chỗ của bạn");
        $this->getAPI()->removeTPRequest($player, $sender);
        return true;
    }
} 