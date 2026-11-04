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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PlayerToggleArmorVisibilityRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_TOGGLE_ARMOR_VISIBILITY_REQUEST_PACKET;

	private bool $hideArmor;

	/**
	 * @generate-create-func
	 */
	public static function create(bool $hideArmor) : self{
		$result = new self;
		$result->hideArmor = $hideArmor;
		return $result;
	}

	public function getHideArmor() : bool{
		return $this->hideArmor;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->hideArmor);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->hideArmor = CommonTypes::getBool($in);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerToggleArmorVisibilityRequest($this);
	}
}
