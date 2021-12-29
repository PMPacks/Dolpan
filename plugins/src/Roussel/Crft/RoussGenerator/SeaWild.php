<?php

namespace Roussel\Crft\RoussGenerator;

use pocketmine\block\Block;
use pocketmine\block\CoalOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\Dirt;
use pocketmine\block\GoldOre;
use pocketmine\block\Gravel;
use pocketmine\block\IronOre;
use pocketmine\block\LapisOre;
use pocketmine\block\NetherQuartzOre;
use pocketmine\block\EmeraldOre;
use pocketmine\block\RedstoneOre;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\populator\Ore;
use pocketmine\level\generator\populator\Populator;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Tree;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\utils\Random;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use Roussel\Crft\Populators\HGTree;

class SeaWild extends Generator{

	private $populators = [];
	private $level;
	private $random;
	private $worldHeight = 64;
	private $waterHeight = 74;
	private $noiseHills;
	private $noiseBase;

	public function __construct(array $options = []){
		$this->temperature = 0.8;
		$this->rainfall = 0.9;
	}
	
	public function getName() : string{
		return "Swamp";
	}

	public function getSettings() : array{
		return [];
	}

	public function init(ChunkManager $level, Random $random){
		$this->level = $level;
		$this->random = $random;
		$this->random->setSeed($this->level->getSeed());
		$this->noiseHills = new Simplex($this->random, 2, 1, 1 / 512);
		$this->noiseBase = new Simplex($this->random, 4, 1 / 4, 1 / 64);


		$ores = new Ore();
		$ores->setOreTypes([
			new OreType(new CoalOre(), 20, 16, 0, 128),
			new OreType(new IronOre(), 20, 8, 0, 64),
			new OreType(new RedstoneOre(), 8, 7, 0, 16),
			new OreType(new LapisOre(), 2, 8, 0, 64),
			new OreType(new GoldOre(), 2, 8, 0, 32),
			new OreType(new DiamondOre(), 1, 7, 0, 16),
			new OreType(new Dirt(), 20, 32, 0, 128),
			new OreType(new Gravel(), 10, 16, 0, 128),
		]);
		$this->populators[] = $ores;

		$trees = new HGTree ();
		//$trees = new Tree();
		$trees->setBaseAmount(2);
		$trees->setRandomAmount(1);
		$this->populators[] = $trees;

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(5);
		$tallGrass->setRandomAmount(3);
		$this->populators[] = $tallGrass;
		
	}
	
	public function getColor(){
		return 0x6a7039;
	}

	public function generateChunk($chunkX, $chunkZ){
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		$hills = [];
		$base = [];
		$color = [0x6a7039];
		for($z = 0; $z < 16; ++$z){
			for($x = 0; $x < 16; ++$x){
				$i = ($z << 4) + $x;
				$hills[$i] = $this->noiseHills->noise2D($x + ($chunkX << 4), $z + ($chunkZ << 4), true);
				$base[$i] = $this->noiseBase->noise2D($x + ($chunkX << 4), $z + ($chunkZ << 4), true);

				if($base[$i] < 0){
					$base[$i] *= 0.5;
				}
			}
		}

		$chunk = $this->level->getChunk($chunkX, $chunkZ);

		for($z = 0; $z < 16; ++$z){
			for($x = 0; $x < 16; ++$x){
				$i = ($z << 4) + $x;
				$height = $this->worldHeight + $hills[$i] * 14 + $base[$i] * 7;
				$height = (int) $height;
				
				for($y = 0; $y < 128; ++$y){
					$diff = $height - $y;
					if($y <= 4 and ($y === 0 or $this->random->nextFloat() < 0.75)){
						$chunk->setBlockId($x, $y, $z, Block::BEDROCK);
					}elseif($diff > 2){
						$chunk->setBlockId($x, $y, $z, Block::STONE);
					}elseif($diff > 0){
						$chunk->setBlockId($x, $y, $z, Block::DIRT);
					}elseif($y <= $this->waterHeight){
						if(($this->waterHeight - $y) <= 1 and $diff === 0){
							$i = rand(0, 20);
							if ($i==0) {
								$chunk->setBlockId($x, $y, $z, Block::SANDSTONE);								
							} else {
								$chunk->setBlockId($x, $y, $z, Block::SAND);
							}
						}elseif($diff === 0){
							$chunk->setBlockId($x, $y, $z, Block::GRASS);
						}else{
							$chunk->setBlockId($x, $y, $z, Block::STILL_WATER);
						}
					}elseif($diff === 0){
						$i = rand ( 0, 15 );
						if ($i == 0) {
							$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							$chunk->setBlockId ( $x, $y + 1, $z, Block::TALL_GRASS );
						} elseif ($i == 1) {
							$k = rand ( 0, 10 );
							if ($k == 0) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$chunk->setBlockId ( $x, $y + 1, $z, Block::DANDELION );
							} elseif ($k == 1) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							}
						} elseif ($i == 2) {
							$k = rand ( 0, 18 );
							if ($k == 0) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$chunk->setBlockId ( $x, $y + 1, $z, Block::POPPY );
							} elseif ($k == 1) {
								$k = rand ( 0, 5 );
								if ($k == 1) {
									$chunk->setBlockId ( $x, $y, $z, Block::WATER );
								} else {
									$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								}
							} elseif ($k == 2) {
								$k = rand ( 0, 5 );
								if ($k == 1) {
									$chunk->setBlockId ( $x, $y, $z, Block::LAVA );
								} else {
									$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								}
							} elseif ($k == 5 || $k == 6) {
								$chunk->setBlockId ( $x, $y, $z, Block::SANDSTONE );
								// $chunk->setBlockId ( $x, $y+1, $z, Block::TALL_GRASS );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$chunk->setBlockId ( $x, $y + 1, $z, Block::TALL_GRASS );
							}
						} elseif ($i == 3) {
							$k = rand ( 0, 18 );
							if ($k == 0) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								//$chunk->setBlockId ( $x, $y + 1, $z, Block::COBBLESTONE_WALL );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$w = rand ( 0, 10 );
								if ($w == 1) {
									$chunk->setBlockId ( $x, $y + 1, $z, Block::BROWN_MUSHROOM );
								} elseif ($w == 2) {
									$chunk->setBlockId ( $x, $y + 1, $z, Block::RED_MUSHROOM );
								} else {
									$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								}
							}
						} elseif ($i == 4) {
							$k = rand ( 0, 10 );
							if ($k == 0) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$chunk->setBlockId ( $x, $y + 1, $z, Block::DANDELION );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							}
						} elseif ($i == 5) {
							$k = rand ( 0, 15 );
							if ($k < 5) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$chunk->setBlockId ( $x, $y + 1, $z, Block::TALL_GRASS );
							} elseif ($k == 6) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$chunk->setBlockId ( $x, $y+1, $z, Block::MELON_BLOCK );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							}
						} elseif ($i == 6) {
							$k = rand ( 0, 15 );
							if ($k == 0) {
								//$chunk->setBlockId ( $x, $y, $z, Block::DIRT );
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							} elseif ($k == 1) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								//$chunk->setBlockId ( $x, $y + 1, $z, Block::STONE_WALL );
							} elseif ($k >1 && $k<5) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							}
						} elseif ($i == 8) {
							$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
						} elseif ($i == 9) {
							$k = rand ( 0, 15 );
							$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							if ($k == 0) {
								$h = rand ( 0, 5 );
								if ($h==0) {
									$chunk->setBlockId ( $x, $y + 1, $z, Block::LOG2 );
								}elseif ($h==2) {
									$chunk->setBlockId ( $x, $y + 1, $z, Block::JACK_O_LANTERN );
									//replace with item
									//$this->level->setBlockIdAt( $x, $y + 1, $z, Block::CHEST);
								} else {
									$chunk->setBlockId ( $x, $y + 1, $z, Block::LOG );
								}
							}
						} elseif ($i == 10 || $i == 11) {
							$k = rand ( 0, 15 );
							if ($k == 0) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							} elseif ($k == 3) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
								$h = rand ( 0, 5 );
								if ($h==0) {
									$chunk->setBlockId ( $x, $y+1, $z, Block::DEAD_BUSH );
								}
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
							}
						} else {
							$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
						}
						
					}
				}
			$chunk->setBiomeId($x, $z, Biome::SWAMP);
			}
		}

	}

	public function populateChunk( $chunkX, $chunkZ){
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		foreach($this->populators as $populator){
			$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
			$populator->populate($this->level, $chunkX, $chunkZ, $this->random);
		}
	}

	public function getSpawn() : Vector3{
		return $this->level->getSafeSpawn(new Vector3(127.5, 128, 127.5));
	}

}
