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

enum DeviceOS : int{
	use PacketIntEnumTrait;

	case UNKNOWN = -1;
	case ANDROID = 1;
	case IOS = 2;
	case OSX = 3;
	case AMAZON = 4;
	case GEAR_VR = 5;
	case HOLOLENS = 6;
	case WINDOWS_10 = 7;
	case WIN32 = 8;
	case DEDICATED = 9;
	case TVOS = 10;
	case PLAYSTATION = 11;
	case NINTENDO = 12;
	case XBOX = 13;
	case WINDOWS_PHONE = 14;
	case LINUX = 15;
}
