<?php

namespace Roussel\Crft\RoussGenerator\object;

use pocketmine\block\Block;
use pocketmine\block\Leaves;
use pocketmine\block\Wood;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use Roussel\Crft\object\PlusTree;

class OakTree extends PlusTree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->leafType = Leaves::OAK;
		$this->type = Wood::OAK;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random){
		$this->treeHeight = $random->nextBoundedInt(3) + 4;
		parent::placeObject($level, $x, $y, $z, $random);
	}
}
