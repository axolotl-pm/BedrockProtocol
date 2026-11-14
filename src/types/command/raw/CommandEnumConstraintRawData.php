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

namespace pocketmine\network\mcpe\protocol\types\command\raw;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\ListSizeLimits;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CommandEnumConstraintRawData{

	/**
	 * @param int[] $constraints
	 * @phpstan-param list<int> $constraints
	 */
	public function __construct(
		private int $affectedValueIndex,
		private int $enumIndex,
		private array $constraints
	){}

	public function getAffectedValueIndex() : int{ return $this->affectedValueIndex; }

	public function getEnumIndex() : int{ return $this->enumIndex; }

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getConstraints() : array{ return $this->constraints; }

	public static function read(ByteBufferReader $in) : self{
		$affectedValueIndex = LE::readUnsignedInt($in);
		$enumIndex = LE::readUnsignedInt($in);

		$constraints = CommonTypes::readList($in, Byte::readUnsigned(...), ListSizeLimits::COMMAND_ENUM_CONSTRAINTS);

		return new self(
			affectedValueIndex: $affectedValueIndex,
			enumIndex: $enumIndex,
			constraints: $constraints
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->affectedValueIndex);
		LE::writeUnsignedInt($out, $this->enumIndex);

		VarInt::writeUnsignedInt($out, count($this->constraints));
		foreach($this->constraints as $constraint){
			Byte::writeUnsigned($out, $constraint);
		}
	}
}
