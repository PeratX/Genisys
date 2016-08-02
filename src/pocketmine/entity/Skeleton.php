<?php

/**
 * OpenGenisys Project
 *
 * @author PeratX
 */


namespace pocketmine\entity;

use pocketmine\item\Dye;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\network\protocol\MobEquipmentPacket;
use pocketmine\item\Item as ItemItem;

class Skeleton extends Monster implements ProjectileSource{
	const NETWORK_ID = 34;

	public $width = 0.6 * 2;
	public $length = 0.6 * 10;
	public $height = 1.8 * 2;

	public $dropExp = [50, 50];//CM
	
	public function getName() : string{
		return "Skeleton";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Skeleton::NETWORK_ID;
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
		
		$pk = new MobEquipmentPacket();
		$pk->eid = $this->getId();
		$pk->item = new ItemItem(ItemItem::BOW);
		$pk->slot = 0;
		$pk->selectedSlot = 0;

		$player->dataPacket($pk);
	}

	public function getCoinDrop() :int{
		return 80;
	}

	public function getDrops(){
		$drops = [
			ItemItem::get(ItemItem::DYE, Dye::LAPIS_LAZULI, 3)//CM
		];
		return $drops;
	}
}
