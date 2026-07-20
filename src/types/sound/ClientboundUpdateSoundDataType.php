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

namespace pocketmine\network\mcpe\protocol\types\sound;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum ClientboundUpdateSoundDataType : int{
	use PacketIntEnumTrait;

	case STOP = 0;
	case SET_VOLUME = 1;
	case SET_PITCH = 2;
	case FADE = 3;
	case SEEK_TO = 4;
	case PAUSE = 5;
	case RESUME = 6;

	public const PAYLOAD_TYPE_STOP = 0;
	public const PAYLOAD_TYPE_SET_VOLUME = 1;
	public const PAYLOAD_TYPE_SET_PITCH = 2;
	public const PAYLOAD_TYPE_FADE = 3;
	public const PAYLOAD_TYPE_SEEK_TO = 4;
	public const PAYLOAD_TYPE_PAUSE = 5;
	public const PAYLOAD_TYPE_RESUME = 6;

	/**
	 * UGH
	 */
	public function getPayloadType() : int{
		return match($this){
			self::STOP => self::PAYLOAD_TYPE_STOP,
			self::SET_VOLUME => self::PAYLOAD_TYPE_SET_VOLUME,
			self::SET_PITCH => self::PAYLOAD_TYPE_SET_PITCH,
			self::FADE => self::PAYLOAD_TYPE_FADE,
			self::SEEK_TO => self::PAYLOAD_TYPE_SEEK_TO,
			self::PAUSE => self::PAYLOAD_TYPE_PAUSE,
			self::RESUME => self::PAYLOAD_TYPE_RESUME,
		};
	}
}
