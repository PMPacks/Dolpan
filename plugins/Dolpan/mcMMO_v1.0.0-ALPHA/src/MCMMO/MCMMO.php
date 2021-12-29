<?php
namespace MCMMO;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;

use MCMMO\Commands\StatsCommand;
use MCMMO\Commands\TopCommand;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, April 2017
 */

class MCMMO extends PluginBase{

  public $players;

  public $base;
  public $config;
  public $cooldown;

  public function onEnable()
  {
    @mkdir($this->getDataFolder());

    $this->saveResource("/config.yml");

    $this->config = (new Config($this->getDataFolder() . "/config.yml", Config::YAML))->getAll();
    
    $this->players = [];

    /*
      SKILLS:

      combat //done
      unarmed //done
      mining //done
      excavation //done
      woodcutting //done
      crafting //done
      archery //done
      fishing //done
      harvesting //done
      planting //done
      alchemy //not fully implemented
      smelting //done
    */

    $this->base = ["força" => ["level" => 1, "exp" => 0], "mineraçao" => ["level" => 1, "exp" => 0], "escavaçao" => ["level" => 1, "exp" => 0], "lenhador" => ["level" => 1, "exp" => 0], "craft" => ["level" => 1, "exp" => 0], "arcos" => ["level" => 1, "exp" => 0], "pescaria" => ["level" => 1, "exp" => 0], "colheita" => ["level" => 1, "exp" => 0], "alquimia" => ["level" => 1, "exp" => 0], "machados" => ["level" => 1, "exp" => 0], "espadas" => ["level" => 1, "exp" => 0], "proteçao" => ["level" => 1, "exp" => 0], "forja" => ["level" => 1, "exp" => 0], "plantaçao" => ["exp" => 0, "level" => 1]];

    $this->players = (new Config($this->getDataFolder() . "/data.json", Config::JSON))->getAll();

    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

    $this->getServer()->getCommandMap()->register("mcstats", new StatsCommand($this));
    $this->getServer()->getCommandMap()->register("mctop", new TopCommand($this));
  }

  public function onDisable()
  {
    @unlink($this->getDataFolder() . "/data.json");

    $d = new Config($this->getDataFolder() . "/data.json", Config::JSON);

    foreach($this->players as $player => $stats)
    {
      $d->set($player, $stats);
      $d->save();
      $d->reload();
    }
  }

  public function getOfflinePlayer(String $player)
  {
    return isset($this->players[$player]) ? $this->players[$player] : $this->base;
  }

  public function getStats(Player $player)
  {
    return isset($this->players[$player->getName()]) ? $this->players[$player->getName()] : $this->base;
  }
}