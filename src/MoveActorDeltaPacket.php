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

class MoveActorDeltaPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVE_ACTOR_DELTA_PACKET;

	public int $actorRuntimeId;
	public int $flags;
	public ?float $xPos = null;
	public ?float $yPos = null;
	public ?float $zPos = null;
	public ?float $pitch = null;
	public ?float $yaw = null;
	public ?float $headYaw = null;
	public bool $onGround = false;
	public bool $forceMove = false;
	public bool $forceMoveLocalEntity = false;
	public bool $forceCompletion = false;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->xPos = CommonTypes::readOptional($in, LE::readFloat(...));
		$this->yPos = CommonTypes::readOptional($in, LE::readFloat(...));
		$this->zPos = CommonTypes::readOptional($in, LE::readFloat(...));
		$this->pitch = CommonTypes::readOptional($in, CommonTypes::getRotationByte(...));
		$this->yaw = CommonTypes::readOptional($in, CommonTypes::getRotationByte(...));
		$this->headYaw = CommonTypes::readOptional($in, CommonTypes::getRotationByte(...));
		$this->onGround = CommonTypes::getBool($in);
		$this->forceMove = CommonTypes::getBool($in);
		$this->forceMoveLocalEntity = CommonTypes::getBool($in);
		$this->forceCompletion = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::writeOptional($out, $this->xPos, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->yPos, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->zPos, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->pitch, CommonTypes::putRotationByte(...));
		CommonTypes::writeOptional($out, $this->yaw, CommonTypes::putRotationByte(...));
		CommonTypes::writeOptional($out, $this->headYaw, CommonTypes::putRotationByte(...));
		CommonTypes::putBool($out, $this->onGround);
		CommonTypes::putBool($out, $this->forceMove);
		CommonTypes::putBool($out, $this->forceMoveLocalEntity);
		CommonTypes::putBool($out, $this->forceCompletion);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMoveActorDelta($this);
	}
}
