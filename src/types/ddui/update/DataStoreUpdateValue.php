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

namespace pocketmine\network\mcpe\protocol\types\ddui\update;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;

abstract class DataStoreUpdateValue{

	abstract public function getTypeId() : int;

	abstract public function write(ByteBufferWriter $out) : void;

	public static function readWithType(ByteBufferReader $in) : DataStoreUpdateValue{
		return match(VarInt::readUnsignedInt($in)){
			DataStoreUpdateValueType::DOUBLE => DoubleDataStoreUpdateValue::read($in),
			DataStoreUpdateValueType::BOOL => BoolDataStoreUpdateValue::read($in),
			DataStoreUpdateValueType::STRING => StringDataStoreUpdateValue::read($in),
			default => throw new PacketDecodeException("Unknown DataStoreValueType"),
		};
	}

	public function writeWithType(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->getTypeId());
		$this->write($out);
	}
}
