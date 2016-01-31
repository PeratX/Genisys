<?php
/**
 * Author: PeratX
 * QQ: 1215714524
 * Time: 2016/1/31 16:01
 * Copyright(C) 2011-2016 iTX Technologies LLC.
 * All rights reserved.
 *
 * OpenGenisys Project
 */
namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;
use pocketmine\tile\ItemFrame as ItemFrameTile;
use pocketmine\Player;

class ItemFrame extends Transparent{
	protected $id = self::ITEM_FRAME_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Item Frame";
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		$tile = $this->getLevel()->getTile($this);
		if($tile instanceof ItemFrameTile){
			if($tile->getItem()->getId() === 0){
				$tile->setItem($item);
				$item->count--;
			}else{
				$itemRot = $tile->getItemRotation();
				if($itemRot === 7) $itemRot = 0;
				else $itemRot++;
				$tile->setItemRotation($itemRot);
			}
		}
		return true;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($target->isTransparent() === false and $face > 1 and $block->isSolid() === false){
			$faces = [
				2 => 1,
				3 => 3,
				4 => 0,
				5 => 2,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);
			$nbt = new CompoundTag("", [
				new StringTag("id", Tile::ITEM_FRAME),
				new IntTag("x", $block->x),
				new IntTag("y", $block->y),
				new IntTag("z", $block->z),
				new ByteTag("ItemRotation", 0),
			]);
			Tile::createTile(Tile::ITEM_FRAME, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			return true;
		}
		return false;
	}
}