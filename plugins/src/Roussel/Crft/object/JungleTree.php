<?php

namespace Roussel\Crft\RoussGenerator\object;

use pocketmine\block\Block;
use pocketmine\block\Leaves;
use pocketmine\block\Wood;
use Roussel\Crft\object\PlusTree;

class JungleTree extends PlusTree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->leafType = Leaves::JUNGLE;
		$this->type = Wood::JUNGLE;
		$this->treeHeight = 8;
	}
}
