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
use pocketmine\network\mcpe\protocol\types\sound\ClientboundUpdateSoundData;

class ClientboundUpdateSoundDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_UPDATE_SOUND_DATA_PACKET;

	private int $serverSoundHandle;
	private ?ClientboundUpdateSoundData $stop = null;
	private ?ClientboundUpdateSoundData $setVolume = null;
	private ?ClientboundUpdateSoundData $setPitch = null;
	private ?ClientboundUpdateSoundData $fade = null;
	private ?ClientboundUpdateSoundData $seekTo = null;
	private ?ClientboundUpdateSoundData $pause = null;
	private ?ClientboundUpdateSoundData $resume = null;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $serverSoundHandle,
		?ClientboundUpdateSoundData $stop = null,
		?ClientboundUpdateSoundData $setVolume = null,
		?ClientboundUpdateSoundData $setPitch = null,
		?ClientboundUpdateSoundData $fade = null,
		?ClientboundUpdateSoundData $seekTo = null,
		?ClientboundUpdateSoundData $pause = null,
		?ClientboundUpdateSoundData $resume = null,
	) : self{
		$result = new self;
		$result->serverSoundHandle = $serverSoundHandle;
		$result->stop = $stop;
		$result->setVolume = $setVolume;
		$result->setPitch = $setPitch;
		$result->fade = $fade;
		$result->seekTo = $seekTo;
		$result->pause = $pause;
		$result->resume = $resume;
		return $result;
	}

	public function getServerSoundHandle() : int{ return $this->serverSoundHandle; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->serverSoundHandle = LE::readUnsignedLong($in);
		$this->stop = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
		$this->setVolume = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
		$this->setPitch = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
		$this->fade = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
		$this->seekTo = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
		$this->pause = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
		$this->resume = CommonTypes::readOptional($in, ClientboundUpdateSoundData::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedLong($out, $this->serverSoundHandle);
		CommonTypes::writeOptional($out, $this->stop, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->setVolume, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->setPitch, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->fade, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->seekTo, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->pause, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->resume, fn(ByteBufferWriter $out, ClientboundUpdateSoundData $data) => $data->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundUpdateSoundData($this);
	}
}
