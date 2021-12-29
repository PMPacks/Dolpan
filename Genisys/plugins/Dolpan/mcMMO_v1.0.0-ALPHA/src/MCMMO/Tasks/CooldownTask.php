<?php
namespace MCMMO\Tasks;

use pocketmine\Player;

use pocketmine\scheduler\PluginTask;

use MCMMO\MCMMO;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, April 2017
 */

class CooldownTask extends PluginTask{

  private $plugin;
  private $type;
  private $player;

  public function __construct(MCMMO $plugin, String $type, Player $player)
  {
    $this->plugin = $plugin;
    $this->type = $type;
    $this->player = $player;
    parent::__construct($plugin);
  }

  public function onRun($currentTick)
  {
    unset($this->plugin->cooldown[$this->player->getName()][$this->type]);
  }
}