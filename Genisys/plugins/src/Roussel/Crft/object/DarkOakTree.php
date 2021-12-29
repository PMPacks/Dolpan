<?php

namespace Roussel\Crft\RoussGenerator\object;

use pocketmine\block\Block;
use pocketmine\block\Leaves2;
use pocketmine\block\Wood2;
use Roussel\Crft\object\PlusTree;

class DarkOakTree extends PlusTree{
	public function __construct(){
		$this->trunkBlock = Block::WOOD2;
		$this->leafBlock = Block::LEAVES2;
		$this->leafType = Leaves2::DARK_OAK;
		$this->type = Wood2::DARK_OAK;
		$this->treeHeight = 8;
	}
}
