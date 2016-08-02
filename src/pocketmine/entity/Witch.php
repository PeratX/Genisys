<?php

namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;
use pocketmine\Player;
use pocketmine\network\protocol\AddEntityPacket;

class Witch extends Monster{
	const NETWORK_ID = 45;
	
	public $dropExp = [1, 1];//CM
	
	public function getName() : string{
		return "Witch";
	}
	
	public function initEntity(){
		$this->setMaxHealth(26);
		parent::initEntity();
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Witch::NETWORK_ID;
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

	public function getCoinDrop() :int{
		return 1;
	}

	public function getDrops(){
		if(mt_rand(0, 99) < 2){
			$drops = [
				ItemItem::get(ItemItem::DIAMOND_SWORD, 0, 1)
			];
		}else{
			$drops = [
				ItemItem::get(ItemItem::GOLD_SWORD, 0, 1)
			];
		}
		return $drops;
	}
}