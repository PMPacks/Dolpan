<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Tool extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tool", "Xem phiên bản của Tool", "[update <check|install>]", true, ["toolpe", "tl", "toolptk"]);
        $this->setPermission("tool.tool");
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
        switch(count($args)){
            case 0:
                $sender->sendMessage(TextFormat::YELLOW . "§b→§aMáy chủ đang sử dụng plugin " . TextFormat::AQUA . "Tool " . TextFormat::YELLOW . "§aphiên bản:" . TextFormat::GREEN . $sender->getServer()->getPluginManager()->getPlugin("Tool")->getDescription()->getVersion());
                break;
            case 1:
            case 2:
                switch(strtolower($args[0])){
                    case "update":
                    case "u":
                        if(!$sender->hasPermission("tool.update.use")){
                            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                            return false;
                        }
                        if(isset($args[1]) && (($a = strtolower($args[1])) === "check" || $a === "c" || $a === "install" || $a === "i")){
                            if(!$this->getAPI()->fetchToolUpdate($a[0] === "i")){
                                $sender->sendMessage(TextFormat::YELLOW . "The updater is already working... Please wait a few moments and try again");
                            }
                            return true;
                        }
                        $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Sử dụng: ") . "/tl update <check|install>");
                        break;
                    case "version":
                    case "v":
                        $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "Tool " . TextFormat::YELLOW . "v" . TextFormat::GREEN . $sender->getServer()->getPluginManager()->getPlugin("Tool")->getDescription()->getVersion());
                        break;
                    default:
                        $this->sendUsage($sender, $alias);
                        return false;
                        break;
                }
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
