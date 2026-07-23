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

namespace pocketmine\network\mcpe\protocol\types\skin;

use pocketmine\network\mcpe\protocol\types\PacketOrdinalEnumTrait;

enum PieceType : string{
	use PacketOrdinalEnumTrait;

	case SKELETON = "persona_skeleton";
	case BODY = "persona_body";
	case SKIN = "persona_skin";
	case BOTTOM = "persona_bottom";
	case FEET = "persona_feet";
	case DRESS = "persona_dress";
	case TOP = "persona_top";
	case HIGH_PANTS = "persona_high_pants";
	case HANDS = "persona_hand";
	case OUTERWEAR = "persona_outerwear";
	case FACIAL_HAIR = "persona_facial_hair";
	case MOUTH = "persona_mouth";
	case EYES = "persona_eyes";
	case HAIR = "persona_hair";
	case HOOD = "persona_hood";
	case BACK = "persona_back";
	case FACE_ACCESSORY = "persona_face_accessory";
	case HEAD = "persona_head";
	case LEGS = "persona_legs";
	case LEFT_LEG = "persona_left_leg";
	case RIGHT_LEG = "persona_right_leg";
	case ARMS = "persona_arms";
	case LEFT_ARM = "persona_left_arm";
	case RIGHT_ARM = "persona_right_arm";
	case CAPES = "persona_capes";
	case CLASSIC_SKIN = "persona_classic_skin";
	case EMOTE = "persona_emote";
}
