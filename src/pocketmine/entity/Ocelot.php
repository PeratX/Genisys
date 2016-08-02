<?php

/**
 * OpenGenisys Project
 *
 * @author PeratX
 */

namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;

class Ocelot extends Animal{
	const NETWORK_ID = 22;

	const DATA_CAT_TYPE = 18;

	const TYPE_WILD = 0;
	const TYPE_TUXEDO = 1;
	const TYPE_TABBY = 2;
	const TYPE_SIAMESE = 3;

	public $width = 0.312;
	public $length = 2.188;
	public $height = 0.75;

	public $dropExp = [5, 5];//CM

	public function getName() : string{
		return "Ocelot";
	}

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		if(!isset($nbt->CatType)){
			$nbt->CatType = new ByteTag("CatType", 0);//CM
		}
		parent::__construct($chunk, $nbt);

		$this->setDataProperty(self::DATA_CAT_TYPE, self::DATA_TYPE_BYTE, $this->getCatType());
	}

	public function setCatType(int $type){
		$this->namedtag->CatType = new ByteTag("CatType", $type);
	}

	public function getCatType() : int{
		return (int) $this->namedtag["CatType"];
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = self::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}

	public function getCoinDrop() :int{//CM
		return 10;
	}

	public function getDrops(){
		$drops = [
			ItemItem::get(ItemItem::RAW_CHICKEN, 0, 1)//CM
		];
		return $drops;
	}
}