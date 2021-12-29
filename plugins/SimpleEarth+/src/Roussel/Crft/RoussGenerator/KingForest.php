<?php

namespace Roussel\Crft\RoussGenerator;

use pocketmine\block\Block;
use pocketmine\item\ItemBlock;
use pocketmine\block\CoalOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\Dirt;
use pocketmine\block\GoldOre;
use pocketmine\block\Gravel;
use pocketmine\block\IronOre;
use pocketmine\block\LapisOre;
use pocketmine\block\RedstoneOre;
use pocketmine\block\NetherQuartzOre;
use pocketmine\block\EmeraldOre;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\generator\populator\Ore;
use pocketmine\level\generator\populator\Cave;
use pocketmine\level\generator\populator\Populator;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Tree;
use Roussel\Crft\BPopulator\populator\MineshaftPopulator;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\utils\Random;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\block\Stone;
use pocketmine\block\Sandstone;
use Roussel\Crft\Populators\HGTree;

class KingForest extends Generator {

	private $populators = [ ];
	private $level;
	private $random;
	private $worldHeight = 64;
	private $waterHeight = 48;
	private $noiseHills;
	private $noisePatches;
	private $noisePatchesSmall;
	private $noiseBase;
	public function __construct(array $options = []) {
	}
	public function getName() : string{
		return "forest";
	}
	public function getSettings() : array{
		return [];
	}
	public function init(ChunkManager $level, Random $random) {
		$this->level = $level;
		$this->random = $random;
		$this->random->setSeed ( $this->level->getSeed () );
		$this->noiseHills = new Simplex ($this->random, 2, 1 / 8, 1 / 512);
		$this->noisePatches = new Simplex ($this->random, 4, 1, 1 / 500);
		$this->noisePatchesSmall = new Simplex ($this->random, 2, 1, 1 / 512);
		$this->noiseBase = new Simplex ($this->random, 4, 1 / 4, 1 / 64);
		
		$ores = new Ore ();
		$ores->setOreTypes ( [ 
				new OreType(new CoalOre (), 20, 16, 0, 128 ),
				new OreType(new NetherQuartzOre(), 20, 8, 0, 64),
				new OreType(new EmeraldOre(), 20, 9, 0, 32),
				new OreType(new IronOre (), 20, 8, 0, 64 ),
				new OreType(new RedstoneOre (), 8, 7, 0, 16 ),
				new OreType(new LapisOre (), 1, 6, 0, 32 ),
				new OreType(new GoldOre (), 2, 8, 0, 32 ),
				new OreType(new DiamondOre (), 20, 7, 0, 64 ),
				new OreType(new Dirt (), 20, 32, 0, 128 ),
				new OreType(new Stone (), 20, 16, 0, 64 ),
				new OreType(new Sandstone (), 20, 16, 0, 64 ),
				new OreType(new Gravel (), 10, 16, 0, 128 ) 
		] );
		$this->populators [] = $ores;
		
		$trees = new HGTree ();
		$trees->setBaseAmount ( 10 );
		$trees->setRandomAmount ( 7 );
		$this->populators [] = $trees;
		
		$tallGrass = new TallGrass ();
		$tallGrass->setBaseAmount ( 5 );
		$tallGrass->setRandomAmount ( 5 );
		$this->populators [] = $tallGrass;
		
		$cave = new Cave();
		$this->populators[] = $cave;
		
		$mineshaft = new MineshaftPopulator ();
		$mineshaft->setBaseAmount(0);
		$mineshaft->setRandomAmount(102);
		$this->populators[] = $mineshaft;
	}
//endfirstpart

//basegeneratorxD
	public function generateChunk($chunkX, $chunkZ) {
		$this->random->setSeed ( 0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed () );
		$hills = [ ];
		$patches = [ ];
		$patchesSmall = [ ];
		$base = [ ];
		for($z = 0; $z < 16; ++ $z) {
			for($x = 0; $x < 16; ++ $x) {
				$i = ($z << 4) + $x;
				$hills [$i] = $this->noiseHills->noise2D ( $x + ($chunkX << 4), $z + ($chunkZ << 4), true);
				$patches [$i] = $this->noisePatches->noise2D ( $x + ($chunkX << 4), $z + ($chunkZ << 4), true);
				$patchesSmall [$i] = $this->noisePatchesSmall->noise2D ( $x + ($chunkX << 4), $z + ($chunkZ << 4), true);
				$base [$i] = $this->noiseBase->noise2D ( $x + ($chunkX << 4), $z + ($chunkZ << 4), true);
				
				if ($base [$i] < 0) {
					$base [$i] *= 0.5;
				}
			}
		}
		
		$chunk = $this->level->getChunk ( $chunkX, $chunkZ );
		
		for($chunkY = 0; $chunkY < 8; ++ $chunkY) {
			$startY = $chunkY << 4;
			$endY = $startY + 16;
			for($z = 0; $z < 16; ++ $z) {
				for($x = 0; $x < 16; ++ $x) {
					$i = ($z << 4) + $x;
					$height = $this->worldHeight + $hills [$i] * 7 + $base [$i] * 5;
					$height = ( int ) $height;
					
					for($y = $startY; $y < $endY; ++ $y) {
						$diff = $height - $y;
						if ($y <= 4 and ($y === 0 or $this->random->nextFloat () < 0.75)) {
							$chunk->setBlockId ( $x, $y, $z, Block::BEDROCK );
						} elseif ($diff > 2) {
							$chunk->setBlockId ( $x, $y, $z, Block::STONE );
						} elseif ($diff > 0) {
							if ($patches [$i] > 0.7) {
								$chunk->setBlockId ( $x, $y, $z, Block::STONE );
							} elseif ($patches [$i] < - 0.8) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRAVEL );
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::DIRT );
							}
						} elseif ($y <= $this->waterHeight) {
							if (($this->waterHeight - $y) <= 1 and $diff === 0) {
								$chunk->setBlockId ( $x, $y, $z, Block::SAND );
							} elseif ($diff === 0) {
								if ($patchesSmall [$i] > 0.6) {
									$chunk->setBlockId ( $x, $y, $z, Block::GRAVEL );
								} elseif ($patchesSmall [$i] < - 0.45) {
									$chunk->setBlockId ( $x, $y, $z, Block::SAND );
								} else {
									$chunk->setBlockId ( $x, $y, $z, Block::DIRT );
								}
							} else {
								$chunk->setBlockId ( $x, $y, $z, Block::STILL_WATER );
							}
						} elseif ($diff === 0) {
							if ($patches [$i] > 0.7) {
								$chunk->setBlockId ( $x, $y, $z, Block::STONE );
							} elseif ($patches [$i] < - 0.8) {
								$chunk->setBlockId ( $x, $y, $z, Block::GRAVEL );
							} elseif ($patches [$i] > - 0.9 && $patches [$i] < - 1.0) {
								$chunk->setBlockId ( $x, $y, $z, Block::TALL_GRASS );
							} else {
								// $chunk->setBlockId($x, $y, $z, Block::GRASS);
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
										$chunk->setBlockId ( $x, $y, $z, Block::COBBLESTONE );
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
										$chunk->setBlockId ( $x, $y, $z, Block::MYCELIUM );
										// $chunk->setBlockId ( $x, $y+1, $z, Block::TALL_GRASS );
									} else {
										$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
										$chunk->setBlockId ( $x, $y + 1, $z, Block::TALL_GRASS );
									}
								} elseif ($i == 3) {
									$k = rand ( 0, 18 );
									if ($k == 0) {
										$chunk->setBlockId ( $x, $y, $z, Block::STONE );
										$chunk->setBlockId ( $x, $y + 1, $z, Block::COBBLESTONE_WALL );
									} else {
										$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
										$w = rand ( 0, 10 );
										if ($w == 1) {
											$chunk->setBlockId ( $x, $y + 1, $z, Block::AIR);
										} elseif ($w == 2) {
											$chunk->setBlockId ( $x, $y + 1, $z, Block::MELON_BLOCK);
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
										$chunk->setBlockId ( $x, $y+1, $z, Block::COBWEB );
									} else {
										$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
									}
								} elseif ($i == 6) {
									$k = rand ( 0, 15 );
									if ($k == 0) {
										$chunk->setBlockId ( $x, $y, $z, Block::DIRT );										
										$chunk->setBlockId ( $x, $y+1, $z, Block::STONE_SLAB );
									} elseif ($k == 1) {
										$chunk->setBlockId ( $x, $y, $z, Block::GRASS );
										$chunk->setBlockId ( $x, $y + 1, $z, Block::STONE_WALL );
									} elseif ($k >1 && $k<5) {
										$chunk->setBlockId ( $x, $y, $z, Block::GRASS );																					
									} elseif ($k == 7) {							
										$chunk->setBlockId ( $x, $y, $z, Block::STONE );																						
									} else {
										$chunk->setBlockId ( $x, $y, $z, Block::MOSS_STONE );
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
										$chunk->setBlockId ( $x, $y, $z, Block::PODZOL );
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
					}
				}
				$chunk->setBiomeId($x, $z, Biome::SWAMP);
			}
		}
	}
	public function randomSurfaceBlocks() {
		$i = rand ( 0, 30 );
		if ($i == 0) {
			return Block::STONE;
		}
		if ($i == 1) {
			return Block::MOSS_STONE;
		}
		if ($i == 2) {
			return Block::GRAVEL;
		}
		return Block::DIRT;
	}
	public function populateChunk($chunkX, $chunkZ) {
		$this->random->setSeed ( 0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed () );
		foreach ( $this->populators as $populator ) {
			$this->random->setSeed ( 0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed () );
			$populator->populate ( $this->level, $chunkX, $chunkZ, $this->random );
		}
	}
	public function getSpawn() : Vector3{
		return $this->level->getSafeSpawn ( new Vector3 ( 127.5, 128, 127.5 ) );
	}
}
