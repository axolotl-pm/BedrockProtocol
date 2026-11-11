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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\VirtualContainerCloseCause;
use pocketmine\network\mcpe\protocol\types\VirtualContainerCloseRequestKind;

final class VirtualContainerClosePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::VIRTUAL_CONTAINER_CLOSE_PACKET;

	private int $sessionId;

	private VirtualContainerCloseRequestKind $kind;

	private VirtualContainerCloseCause $cause;

	/**
	 * @generate-create-func
	 */
	public static function create(int $sessionId, VirtualContainerCloseRequestKind $kind, VirtualContainerCloseCause $cause) : self{
		$result = new self;
		$result->sessionId = $sessionId;
		$result->kind = $kind;
		$result->cause = $cause;
		return $result;
	}

	public function getSessionId() : int{
		return $this->sessionId;
	}

	public function getKind() : VirtualContainerCloseRequestKind{
		return $this->kind;
	}

	public function getCause() : VirtualContainerCloseCause{
		return $this->cause;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->sessionId);
		Byte::writeUnsigned($out, $this->kind->value);
		Byte::writeUnsigned($out, $this->cause->value);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->sessionId = VarInt::readUnsignedLong($in);
		$this->kind = VirtualContainerCloseRequestKind::fromPacket(Byte::readUnsigned($in));
		$this->cause = VirtualContainerCloseCause::fromPacket(Byte::readUnsigned($in));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleVirtualContainerClose($this);
	}
}
