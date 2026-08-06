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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\MovePlayerTeleportData;

class MovePlayerPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVE_PLAYER_PACKET;

	public const MODE_NORMAL = 0;
	public const MODE_RESET = 1;
	public const MODE_TELEPORT = 2;
	public const MODE_PITCH = 3; //facepalm Mojang

	public int $actorRuntimeId;
	public Vector3 $position;
	public float $pitch;
	public float $yaw;
	public float $headYaw;
	public int $mode = self::MODE_NORMAL;
	public bool $onGround = false;
	public int $ridingActorRuntimeId = 0;
	public ?MovePlayerTeleportData $telemetryData;
	public int $tick = 0;

	/**
	 * @generate-create-func
	 */
	private static function internalCreate(
		int $actorRuntimeId,
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		int $mode,
		bool $onGround,
		int $ridingActorRuntimeId,
		?MovePlayerTeleportData $telemetryData,
		int $tick,
	) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->position = $position;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->mode = $mode;
		$result->onGround = $onGround;
		$result->ridingActorRuntimeId = $ridingActorRuntimeId;
		$result->telemetryData = $telemetryData;
		$result->tick = $tick;
		return $result;
	}

	public static function create(
		int $actorRuntimeId,
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		int $mode,
		bool $onGround,
		int $ridingActorRuntimeId,
		?MovePlayerTeleportData $telemetryData,
		int $tick,
	) : self{
		if($mode === self::MODE_TELEPORT && $telemetryData === null){
			throw new \InvalidArgumentException("telemetryData must be provided when mode is MODE_TELEPORT");
		}
		return self::internalCreate($actorRuntimeId, $position, $pitch, $yaw, $headYaw, $mode, $onGround, $ridingActorRuntimeId, $telemetryData, $tick);
	}

	public static function simple(
		int $actorRuntimeId,
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		int $mode,
		bool $onGround,
		int $ridingActorRuntimeId,
		int $tick,
	) : self{
		return self::create($actorRuntimeId, $position, $pitch, $yaw, $headYaw, $mode, $onGround, $ridingActorRuntimeId, $mode === self::MODE_TELEPORT ? new MovePlayerTeleportData(0, 0) : null, $tick);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->position = CommonTypes::getVector3($in);
		$this->pitch = LE::readFloat($in);
		$this->yaw = LE::readFloat($in);
		$this->headYaw = LE::readFloat($in);
		$this->mode = Byte::readUnsigned($in);
		$this->onGround = CommonTypes::getBool($in);
		$this->ridingActorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->telemetryData = CommonTypes::readOptional($in, MovePlayerTeleportData::read(...));
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putVector3($out, $this->position);
		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);
		LE::writeFloat($out, $this->headYaw);
		Byte::writeUnsigned($out, $this->mode);
		CommonTypes::putBool($out, $this->onGround);
		CommonTypes::putActorRuntimeId($out, $this->ridingActorRuntimeId);
		CommonTypes::writeOptional($out, $this->telemetryData, static fn(ByteBufferWriter $out, MovePlayerTeleportData $data) => $data->write($out));
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMovePlayer($this);
	}
}
