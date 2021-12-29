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

class TopCommand extends BaseCommand{

    private $plugin;

    public function __construct(MCMMO $plugin){
        parent::__construct("mctop", $plugin);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) 
    {
        $player = $this->plugin->players;

        $array = [];

        foreach($player as $nm => $data)
        {
            $lvl = 0;
            foreach($data as $skill => $dt)
            {
                $lvl = $lvl + $dt["level"];
            }
            $array[$nm] = $lvl;
        }

        arsort($array);

        $ret = [];
        $n = 0;

        foreach($array as $name => $lvl)
        {
            $n++;
            $ret[$n] = [$name, $lvl];
            if($n <= 10)
            {
                break;
            }
        }

        $n = 0;
        foreach($ret as $key => $value)
        {
            $n++;
            if(!is_int($key))
            {
                continue; //weird;
            }

            if($n > 10)
            {
                return true; //completed
            }
            $sender->sendMessage("§f*" . $key . " §7" . $value[0] . " §8: §a" . $value[1] . TextFormat::RESET);
        }
    }
}