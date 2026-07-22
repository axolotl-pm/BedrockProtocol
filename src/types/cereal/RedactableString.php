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

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class RedactableString{

	public function __construct(
		private string $unredacted,
		private ?string $redacted = null
	){}

	public function getUnredacted() : string{ return $this->unredacted; }

	public function getRedacted() : ?string{ return $this->redacted; }

	public static function read(ByteBufferReader $in) : self{
		$unredacted = CommonTypes::getString($in);
		$redacted = CommonTypes::readOptional($in, CommonTypes::getString(...));
		return new self($unredacted, $redacted);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->unredacted);
		CommonTypes::writeOptional($out, $this->redacted, CommonTypes::putString(...));
	}
}
