<?php

/**
 * OpenGenisys Project
 *
 * @author PeratX
 */


namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;
use pocketmine\event\entity\CreeperPowerEvent;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class Creeper extends Monster{
	const NETWORK_ID = 33;

	const DATA_SWELL_DIRECTION = 16;
	const DATA_SWELL = 17;
	const DATA_SWELL_OLD = 18;
	const DATA_POWERED = 19;

	public $width = 0.6 * 20;
	public $length = 0.6 * 50;
	public $height = 1.8 * 20;

	public $dropExp = [300, 300];//CM
	
	public function getName() : string{
		return "Creeper";
	}

	public function initEntity(){
		parent::initEntity();

		if(!isset($this->namedtag->powered)){
			$this->setPowered(false);
		}
		$this->setDataProperty(self::DATA_POWERED, self::DATA_TYPE_BYTE, $this->isPowered() ? 1 : 0);
	}

	public function setPowered(bool $powered, Lightning $lightning = null){
		if($lightning != null){
			$powered = true;
			$cause = CreeperPowerEvent::CAUSE_LIGHTNING;
		}else $cause = $powered ? CreeperPowerEvent::CAUSE_SET_ON : CreeperPowerEvent::CAUSE_SET_OFF;

		$this->getLevel()->getServer()->getPluginManager()->callEvent($ev = new CreeperPowerEvent($this, $lightning, $cause));

		if(!$ev->isCancelled()){
			$this->namedtag->powered = new ByteTag("powered", $powered ? 1 : 0);
			$this->setDataProperty(self::DATA_POWERED, self::DATA_TYPE_BYTE, $powered ? 1 : 0);
		}
	}

	public function isPowered() : bool{
		return $this->namedtag["powered"] == 0 ? false : true;
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Creeper::NETWORK_ID;
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
		return 400;
	}

	public function getDrops(){
		$drops = [
			ItemItem::get(ItemItem::LAPIS_BLOCK, 0, 3)
		];
		return $drops;
	}
}