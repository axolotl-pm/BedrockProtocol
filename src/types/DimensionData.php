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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use Ramsey\Uuid\UuidInterface;

/**
 * Spec name: DimensionDefinition
 */
final class DimensionData{

	public function __construct(
		private int $maxHeight,
		private int $minHeight,
		private int $generator,
		private int $dimensionType,
		private UuidInterface $packId
	){}

	public function getMaxHeight() : int{ return $this->maxHeight; }

	public function getMinHeight() : int{ return $this->minHeight; }

	public function getGenerator() : int{ return $this->generator; }

	public function getDimensionType() : int{ return $this->dimensionType; }

	public function getPackId() : UuidInterface{ return $this->packId; }

	public static function read(ByteBufferReader $in) : self{
		$maxHeight = VarInt::readSignedInt($in);
		$minHeight = VarInt::readSignedInt($in);
		$generator = VarInt::readSignedInt($in);
		$dimensionType = VarInt::readSignedInt($in);
		$packId = CommonTypes::getUUID($in);

		return new self($maxHeight, $minHeight, $generator, $dimensionType, $packId);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->maxHeight);
		VarInt::writeSignedInt($out, $this->minHeight);
		VarInt::writeSignedInt($out, $this->generator);
		VarInt::writeSignedInt($out, $this->dimensionType);
		CommonTypes::putUUID($out, $this->packId);
	}
}
