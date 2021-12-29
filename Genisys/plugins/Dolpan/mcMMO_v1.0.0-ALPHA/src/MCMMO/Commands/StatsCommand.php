<?php
namespace MCMMO\Commands;

use MCMMO\MCMMO;

use pocketmine\Player;

use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, April 2017
 */

class StatsCommand extends BaseCommand{

    private $plugin;

    public function __construct(MCMMO $plugin){
        parent::__construct("mcstats", $plugin);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) 
    {
        if(!$sender instanceof Player)
        {
            $sender->sendMessage(TextFormat::RED . "  You must be a player to run this command!");
            return false;
        }
        if(!isset($args[0]))
        {
            $stats = $this->plugin->players[strtolower($sender->getName())];
            $sender->sendMessage("§l§aMCMMO §r§aStatus Do(a):§a " . strtoupper($sender->getName()));
            foreach($stats as $name => $data)
            {
                $sender->sendMessage("§a" . ucfirst($name) . " §7:§e " . $data["level"] . " §aXP(". $data["exp"] . "/" . $data["level"] * 100 . ")");
            }
            return;
        }

        $player = $this->plugin->getOfflinePlayer($args[0]);
        
        $stats = $this->plugin->getStats($player);

        $sender->sendMessage("§l§aMCMMO §r§aStatus Do(a):§a " . strtoupper($args[0]));
        foreach($stats as $name => $data)
        {
            $sender->sendMessage("§a" . ucfirst($name) . " §7:§e " . $data["level"] . " §aXP(". $data["exp"] . "/" . $data["level"] * 100 . ")");
        }
        return;
    }
}