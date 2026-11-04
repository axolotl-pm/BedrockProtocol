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

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ddui\update\DataStoreUpdateValue;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * @see ServerboundDataStorePacket
 * @see ClientboundDataStorePacket
 */
final class DataStoreUpdate implements DataStoreOperation{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreOperationType::UPDATE;

	public function __construct(
		private string $name,
		private DataStoreUpdateValue $data,
		private int $updateCount,
	){}

	public function getName() : string{ return $this->name; }

	public function getData() : DataStoreUpdateValue{ return $this->data; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$data = DataStoreUpdateValue::readWithType($in);
		$updateCount = LE::readUnsignedInt($in);

		return new self(
			$name,
			$data,
			$updateCount,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		$this->data->writeWithType($out);
		LE::writeUnsignedInt($out, $this->updateCount);
	}
}
