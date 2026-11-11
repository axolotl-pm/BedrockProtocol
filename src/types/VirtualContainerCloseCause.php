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

enum VirtualContainerCloseCause : int{
	use PacketIntEnumTrait;

	case UNKNOWN = 0;
	case USER_CLOSED = 1;
	case SERVER_REQUESTED = 2;
	case USER_BUSY = 3;
	case CLIENT_NOT_READY = 4;
	case INVALID_CONTENTS = 5;
	case UI_CREATION_FAILED = 6;
	case UI_CANCELLED = 7;
}
