<?php
namespace MCMMO;

use pocketmine\event\Listener;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\FurnaceSmeltEvent;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use pocketmine\network\protocol\SetTitlePacket;

use pocketmine\entity\Arrow;
use pocketmine\entity\Projectile;
use pocketmine\entity\Effect;

use pocketmine\block\Block;
use pocketmine\block\Crops;
use pocketmine\block\Flower;

use pocketmine\Player;

use pocketmine\entity\Entity;

use pocketmine\item\Item;

use MCMMO\Tasks\CooldownTask;

use MCMMO\MCMMO;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, April 2017
 */

class EventListener implements Listener{

  private $plugin;

  private $lastbreak;
  private $lasthit;

  public function __construct(MCMMO $plugin)
  {
    $this->plugin = $plugin;
    $this->lastbreak = [];
    $this->lasthit = [];
  }

  public function onJoin(PlayerJoinEvent $ev)
  {
    if(!isset($this->plugin->players[strtolower($ev->getPlayer()->getName())]))
    {
      $this->plugin->players[strtolower($ev->getPlayer()->getName())] = $this->plugin->base;
    }
  }

  public function onDamage(EntityDamageEvent $ev)
  {
    if(!$ev instanceof EntityDamageByEntityEvent)
    {
      return false;
    }

    if(!$this->plugin->getServer()->getPluginManager()->getPlugin("iProtector")->canGetHurt($ev->getEntity()))
    {
      return;
    }

    if($ev->isCancelled())
    {
      return;
    }

    $hit = $ev->getEntity();
    $dm = $ev->getDamager();

    if(!$dm instanceof Player)
    {
      return false;
    }

    $item = $dm->getInventory()->getItemInHand();

    $tm = microtime(true);

    if($item->isSword())
    {
      $type = "espadas";
      $typeI = "proteçao";

      $stats = $this->plugin->players[strtolower($dm->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));

      $dm->sendPopup("§aEspadas XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");
        
    if($hit instanceof Player){
    $statsI = $this->plugin->players[strtolower($hit->getName())];
    if($ev->getDamage() + $stats[$type]["level"] >= -$statsI[$typeI]["level"]){    
      $rand = mt_rand(1,$statsI[$typeI]["level"]);
      $ev->setDamage($ev->getDamage() + $stats[$type]["level"] - ($rand / 2));
     }
     }   
    if(!$hit instanceof Player){
      $ev->setDamage($ev->getDamage() + $stats[$type]["level"]);
     }
    if($hit instanceof Player){
    $statsI = $this->plugin->players[strtolower($hit->getName())];
    if($ev->getDamage() + $stats[$type]["level"] >= +$statsI[$typeI]["level"]){
     $ev->setCancelled(false);
     }
     }
        
      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;    

       
        
        $dm->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);
          
        }
        $this->plugin->players[strtolower($dm->getName())] = $stats;
      }
    
    
      
    if($item->isAxe())
    {
      $type = "machados";
      $typeI = "proteçao";

      $stats = $this->plugin->players[strtolower($dm->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));

      $dm->sendPopup("§aMachados XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");
        
    if($hit instanceof Player){
    $statsI = $this->plugin->players[strtolower($hit->getName())];
    if($ev->getDamage() + $stats[$type]["level"] >= -$statsI[$typeI]["level"]){    
      $rand = mt_rand(1,$statsI[$typeI]["level"]);
      $ev->setDamage($ev->getDamage() + $stats[$type]["level"] - ($rand / 2));
     }
     }   
    if(!$hit instanceof Player){
      $ev->setDamage($ev->getDamage() + $stats[$type]["level"]);
     }
    if($hit instanceof Player){
    $statsI = $this->plugin->players[strtolower($hit->getName())];
    if($ev->getDamage() + $stats[$type]["level"] >= +$statsI[$typeI]["level"]){
     $ev->setCancelled(false);
     }
     }
        
      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        
        ++$stats[$type]["level"];
        $dm->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);

        }
      $this->plugin->players[strtolower($dm->getName())] = $stats;
      }
     
   
      
    if($hit instanceof Player)
    {
      $type = "proteçao";

      $stats = $this->plugin->players[strtolower($hit->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));

      $hit->sendPopup("§aProteção XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");
        
      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        
        ++$stats[$type]["level"];
        $hit->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);

      }
       $this->plugin->players[strtolower($hit->getName())] = $stats;
    }
      
    if($item->getId() === Item::AIR)
    {
      $type = "força";

      $stats = $this->plugin->players[strtolower($dm->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));

      $dm->sendPopup("§aForça XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        
        
      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        
        ++$stats[$type]["level"];
        $dm->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);

      }
     $this->plugin->players[strtolower($dm->getName())] = $stats;
    }
  }

  public function onHit(ProjectileHitEvent $ev)
  {
    $p = $ev->getEntity();

    if(!$p instanceof Arrow)
    {
      return;
    }

    $p = $p->shootingEntity;
    if(!$p instanceof Player)
    {
      return;
    }

    $type = "arcos";

    $stats = $this->plugin->players[strtolower($p->getName())];

    $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));

    $p->sendPopup("§aArcos XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        
      
    if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
    {
      

      ++$stats[$type]["level"];
  
              
      $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
      $p->addXpLevel($this->plugin->config["exp-gain"]);
    }
    $this->plugin->players[strtolower($p->getName())] = $stats;
  }

  public function onBreak(BlockBreakEvent $ev)
  {
    if($ev->isCancelled())
    {
      return;
    }

    $p = $ev->getPlayer();

    $item = $p->getInventory()->getItemInHand();

    $b = $ev->getBlock();

    $tm = microtime(true);

    if(!isset($this->plugin->lastbreak[$p->getName()]))
    {
      $this->plugin->lastbreak[$p->getName()] = 0;
    }

    if($b->getId() === Block::WOOD)
    {
      $type = "lenhador";

      $stats = $this->plugin->players[strtolower($p->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));
        
      $p->sendPopup("§aLenhador XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {

        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;    

        
        
        $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);
      }

      $this->plugin->players[strtolower($p->getName())] = $stats;
    }
    elseif($b->getId() === Block::DIRT or $b->getId() === Block::GRASS or $b->getId() === Block::CAKE_BLOCK)
    {
      $type = "escavaçao";

      if(($tm - $this->plugin->lastbreak[$p->getName()]) <= 0.359 && (!isset($this->plugin->cooldown[$p->getName()]["giga"]) or $this->plugin->cooldown[$p->getName()]["giga"] === false))
      {
        $p->sendTip("§l§aDrill Ativada");

        $haste = Effect::getEffect(Effect::HASTE);
        $haste->setDuration(200);
        $haste->setAmplifier($stats[$type]["level"]);
        
        $p->addEffect($haste);

        $this->plugin->cooldown[$p->getName()]["giga"] = true;
        $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new CooldownTask($this->plugin, "giga", $p), (20 * 30));
      }

      $exp = 1;
     

      $this->plugin->lastbreak[$p->getName()] = $tm;

      $stats = $this->plugin->players[strtolower($p->getName())];

      $stats[$type]["exp"] = $stats[$type]["exp"] + mt_rand(1, 3);
        
      $p->sendPopup("§aEscavação XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;

        
        $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);
      }

      $this->plugin->players[strtolower($p->getName())] = $stats;
    }
    elseif($b instanceof Crops or $b instanceof Flower)
    {
      if($item->isHoe())
      {
        $type = "colheita";

        $stats = $this->plugin->players[strtolower($p->getName())];

        $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));

        $chance = mt_rand(0, 100);

      if(($tm - $this->plugin->lastbreak[$p->getName()]) <= 0.359 && (!isset($this->plugin->cooldown[$p->getName()]["crop"]) or $this->plugin->cooldown[$p->getName()]["crop"] === false))
      {
        $p->sendTip("§l§aColheitas Triplas Ativadas");

          if($chance <= (10 + $stats[$type]["level"]))
          {
            $drops = [];
            foreach($ev->getDrops() as $drop)
            {
              $drop->count = ($drop->count * mt_rand(1, 3));
              $drops[] = $drop;
            }
            $ev->setDrops($drops);
          }

        $p->sendPopup("§aEscavação XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");            
        $this->plugin->cooldown[$p->getName()]["crop"] = true;
        $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new CooldownTask($this->plugin, "crop", $p), 20 * 30);
      }

        if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
        {
          ++$stats[$type]["level"];
          $stats[$type]["exp"] = 0;    

         
        
          $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
          $p->addXpLevel($this->plugin->config["exp-gain"]);
        }
        $this->plugin->players[strtolower($p->getName())] = $stats;
      }
    }
    else
    {
      $type = "mineraçao";

      if(($tm - $this->plugin->lastbreak[$p->getName()]) <= 0.359 && (!isset($this->plugin->cooldown[$p->getName()]["breaker"]) or $this->plugin->cooldown[$p->getName()]["breaker"] === false))
      {
        $p->sendTip("§l§aSuper Destruidor Ativado");

        $haste = Effect::getEffect(Effect::HASTE);
        $haste->setDuration(200);
        $haste->setAmplifier($stats[$type]["level"]);
     

        $p->addEffect($haste);

        $this->plugin->cooldown[$p->getName()]["breaker"] = true;
        $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new CooldownTask($this->plugin, "breaker", $p), 20 * 30);
      }

      $rand = mt_rand(0, 100);

      $stats = $this->plugin->players[strtolower($p->getName())];

      $exp = 1;

      switch($b->getId())
      {
        case Block::NETHERRACK:
        case Block::MOSS_STONE:
        case Block::STONE:
        case Block::SANDSTONE:
        case Block::GLOWSTONE:
          $exp = 1;
        break;
        case Block::COAL_ORE:
        case Block::REDSTONE_ORE:
        case Block::OBSIDIAN:
          $exp = 1;
        break;
        case Block::IRON_ORE:
        case Block::GOLD_ORE:
          $exp = 2;
        break;
        case Block::DIAMOND_ORE:
          $exp = 3;
        break;
        default:
          $exp = 1;
        break;
      }

      if(($tm - $this->plugin->lastbreak[$p->getName()]) <= 0.359 && (!isset($this->plugin->cooldown[$p->getName()]["double"]) or $this->plugin->cooldown[$p->getName()]["double"] === false))
      {
        $p->sendTip("§l§aDobro De Drops Ativado");
        $drops = [];
        foreach($ev->getDrops() as $drop)
        {
          $drop->count = $drop->count * 2;
          $drops[] = $drop;
        }
        $ev->setDrops($drops);

        $this->plugin->cooldown[$p->getName()]["double"] = true;
        $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new CooldownTask($this->plugin, "double", $p), 20 * 30);
      }

      $this->plugin->lastbreak[$p->getName()] = $tm;

      $stats[$type]["exp"] = ($stats[$type]["exp"] + $exp);
        
      $p->sendPopup("§aMineração XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;    

       
        
        $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);

      }
      $this->plugin->players[strtolower($p->getName())] = $stats;
    }

    $this->lastbreak[$p->getName()] = microtime(true);
  }

  public function onCraft(CraftItemEvent $ev)
  {
    $p = $ev->getPlayer();

    $recipe = $ev->getRecipe();
    $item = $recipe->getResult();

    if($item->isSword())
    {
      $type = "craft";

      $stats = $this->plugin->players[strtolower($p->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + 5);
        
      $p->sendPopup("§aCraft XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;    

        
        
        $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);
      }
      $this->plugin->players[strtolower($p->getName())] = $stats;
    }
    elseif($item->isArmor())
    {
      $type = "craft";

      $stats = $this->plugin->players[strtolower($p->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + 5);
        
      $p->sendPopup("§aCraft XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;    

        
        $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);
      }
      $this->plugin->players[strtolower($p->getName())] = $stats;
    }
    else
    {
      $type = "craft";

      $stats = $this->plugin->players[strtolower($p->getName())];

      $stats[$type]["exp"] = ($stats[$type]["exp"] + 3);
        
      $p->sendPopup("§aCraft XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

      if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
      {
        ++$stats[$type]["level"];
        $stats[$type]["exp"] = 0;    

        

        
        $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
        $p->addXpLevel($this->plugin->config["exp-gain"]);
      }
      $this->plugin->players[strtolower($p->getName())] = $stats;
    }
  }

  public function onSmelt(FurnaceSmeltEvent $ev)
  {
  	return;
    $p = $ev->getPlayer();

    $recipe = $ev->getRecipe();
    $item = $recipe->getResult();

    $type = "forja";

    $stats = $this->plugin->players[strtolower($p->getName())];

    $stats[$type]["exp"] = ($stats[$type]["exp"] + mt_rand(1, 3));
    
    $p->sendPopup("§aForja XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        

    if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
    {
      ++$stats[$type]["level"];
      $stats[$type]["exp"] = 0;    

     
      $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
      $p->addXpLevel($this->plugin->config["exp-gain"]);
    }
    $this->plugin->players[strtolower($p->getName())] = $stats;
  }

  public function onFish(PlayerFishEvent $ev)
  {
    if($ev->isCancelled())
    {
      return;
    }

    $p = $ev->getPlayer();

    $item = $ev->getItem();

    $type = "pescaria";

    $exp = 1;

    switch($item->getId())
    {
      case Item::RAW_SALMON:
        $exp = 2;
      break;
      case Item::RAW_FISH:
        $exp = 1;
      break;
      case Item::CLOWN_FISH:
      case Item::PUFFER_FISH:
        $exp = 3;
      default:
        $exp = 1;
      break;
    }

    $stats = $this->plugin->players[strtolower($p->getName())];

    $stats[$type]["exp"] = ($stats[$type]["exp"] + $exp);

    $p->sendPopup("§aPescaria XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        
      
    if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
    {
      ++$stats[$type]["level"];
      $stats[$type]["exp"] = 0;    

      
      $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
      $p->addXpLevel($this->plugin->config["exp-gain"]);
    }
    $this->plugin->players[strtolower($p->getName())] = $stats;
  }

  public function onInteract(PlayerInteractEvent $ev)
  {
    $p = $ev->getPlayer();

    $item = $ev->getPlayer()->getInventory()->getItemInHand();

    if($ev->isCancelled())
    {
      return;
    }

    $type = "plantaçao";

    $exp = 0;

    if($item->getId() == Item::WHEAT_SEEDS or $item->getId() == Item::MELON_SEEDS or $item->getId() == Item::PUMPKIN_SEEDS or $item->getId() == Item::BEETROOT_SEEDS){
        
    switch($item->getId())
    {
      case Item::WHEAT_SEEDS:
        $exp = 2;
      break;
      case Item::MELON_SEEDS:
      case Item::PUMPKIN_SEEDS:
        $exp = 3;
      break;
      case Item::BEETROOT_SEEDS:
        $exp = 5;
      break;
      default:
        $exp = 0;
      break;
    }
    }

    $stats = $this->plugin->players[strtolower($p->getName())];

    $stats[$type]["exp"] = ($stats[$type]["exp"] + $exp);

    //$p->sendPopup("§aPlantação XP\n§a(".$stats[$type]["exp"]."/".($stats[$type]["level"] * 100).")");        
      
    if($stats[$type]["exp"] >= ($stats[$type]["level"] * 100))
    {
      ++$stats[$type]["level"];
      $stats[$type]["exp"] = 0;    

      
      $p->sendTip(TextFormat::GREEN . TextFormat::BOLD . "§aSkill Upada", TextFormat::RESET . TextFormat::GOLD . ucfirst($type) . " -> " . $stats[$type]["level"]);
      $p->addXpLevel($this->plugin->config["exp-gain"]);
    }
    $this->plugin->players[strtolower($p->getName())] = $stats;
  }
}