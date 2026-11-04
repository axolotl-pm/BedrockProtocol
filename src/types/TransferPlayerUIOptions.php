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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class TransferPlayerUIOptions{

	public function __construct(
		private readonly ?string $titleText,
		private readonly ?string $bodyText,
		private readonly ?bool $titleVisible,
		private readonly ?bool $bodyVisible,
		private readonly ?string $cancelButtonText,
		private readonly ?bool $cancelButtonVisible,
		private readonly ?float $cancelButtonDelaySeconds
	){}

	public function getTitleText() : ?string{ return $this->titleText; }

	public function getBodyText() : ?string{ return $this->bodyText; }

	public function getTitleVisible() : ?bool{ return $this->titleVisible; }

	public function getBodyVisible() : ?bool{ return $this->bodyVisible; }

	public function getCancelButtonText() : ?string{ return $this->cancelButtonText; }

	public function getCancelButtonVisible() : ?bool{ return $this->cancelButtonVisible; }

	public function getCancelButtonDelaySeconds() : ?float{ return $this->cancelButtonDelaySeconds; }

	public static function read(ByteBufferReader $in) : self{
		$titleText = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$bodyText = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$titleVisible = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$bodyVisible = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$cancelButtonText = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$cancelButtonVisible = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$cancelButtonDelaySeconds = CommonTypes::readOptional($in, LE::readFloat(...));
		return new self(
			$titleText,
			$bodyText,
			$titleVisible,
			$bodyVisible,
			$cancelButtonText,
			$cancelButtonVisible,
			$cancelButtonDelaySeconds
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->titleText, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->bodyText, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->titleVisible, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->bodyVisible, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->cancelButtonText, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->cancelButtonVisible, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->cancelButtonDelaySeconds, LE::writeFloat(...));
	}
}
