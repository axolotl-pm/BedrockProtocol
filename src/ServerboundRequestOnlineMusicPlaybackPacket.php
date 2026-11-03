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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class ServerboundRequestOnlineMusicPlaybackPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_REQUEST_ONLINE_MUSIC_PLAYBACK_PACKET;

	private int $requestId;
	private string $soundEventName;

	/**
	 * @generate-create-func
	 */
	public static function create(int $requestId, string $soundEventName) : self{
		$result = new self;
		$result->requestId = $requestId;
		$result->soundEventName = $soundEventName;
		return $result;
	}

	public function getRequestId() : int{
		return $this->requestId;
	}

	public function getSoundEventName() : string{
		return $this->soundEventName;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->requestId);
		CommonTypes::putString($out, $this->soundEventName);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->requestId = LE::readUnsignedInt($in);
		$this->soundEventName = CommonTypes::getString($in);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundRequestOnlineMusicPlayback($this);
	}
}
