<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use function count;

class SubChunkPacketHeightMapInfo{

	private const ROW_LENGTH = 16;
	private const TOTAL_LENGTH = self::ROW_LENGTH ** 2;

	/**
	 * @param int[] $heights ZZZZXXXX key bit order
	 * @phpstan-param list<int> $heights
	 */
	public function __construct(private array $heights){
		if(count($heights) !== self::TOTAL_LENGTH){
			throw new \InvalidArgumentException("Expected exactly " . self::TOTAL_LENGTH . " heightmap values");
		}
	}

	/** @return int[] */
	public function getHeights() : array{ return $this->heights; }

	public function getHeight(int $x, int $z) : int{
		return $this->heights[(($z & 0xf) << 4) | ($x & 0xf)];
	}

	public static function read(ByteBufferReader $in) : self{
		$heights = [];
		for($i = 0; $i < self::TOTAL_LENGTH; ++$i){
			if(($i & (self::ROW_LENGTH - 1)) === 0){ //start of a new row
				$rowLength = VarInt::readUnsignedInt($in);
				if($rowLength !== self::ROW_LENGTH){
					throw new PacketDecodeException("Expected height map row to hold exactly " . self::ROW_LENGTH . " heights, got $rowLength");
				}
			}
			$heights[] = Byte::readSigned($in);
		}
		return new self($heights);
	}

	public function write(ByteBufferWriter $out) : void{
		foreach($this->heights as $i => $height){
			if(($i & (self::ROW_LENGTH - 1)) === 0){ //start of a new row
				VarInt::writeUnsignedInt($out, self::ROW_LENGTH);
			}
			Byte::writeSigned($out, $height);
		}
	}
}
