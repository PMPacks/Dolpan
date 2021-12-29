<?php

namespace _64FF00\PureChat\groups;

use pocketmine\Player;
use pocketmine\Server;

class PocketPerm implements GroupsInterface
{
    /*
        PureChat by 64FF00 (Twitter: @64FF00)

          888  888    .d8888b.      d8888  8888888888 8888888888 .d8888b.   .d8888b.
          888  888   d88P  Y88b    d8P888  888        888       d88P  Y88b d88P  Y88b
        888888888888 888          d8P 888  888        888       888    888 888    888
          888  888   888d888b.   d8P  888  8888888    8888888   888    888 888    888
          888  888   888P "Y88b d88   888  888        888       888    888 888    888
        888888888888 888    888 8888888888 888        888       888    888 888    888
          888  888   Y88b  d88P       888  888        888       Y88b  d88P Y88b  d88P
          888  888    "Y8888P"        888  888        888        "Y8888P"   "Y8888P"
    */

    /**
     * @return null|\pocketmine\plugin\Plugin
     */
    public function getAPI()
    {
        return Server::getInstance()->getPluginManager()->getPlugin("PocketPerm");
    }

    /**
     * @param Player $player
     * @return mixed
     */
    public function getPlayerGroup(Player $player)
    {
        return $this->getAPI()->getProvider()->getPlayerGroup($player->getName());
    }

    /**
     * @param Player $player
     * @return string
     */
    public function getPlayerRank(Player $player)
    {
        // TODO
    }
}