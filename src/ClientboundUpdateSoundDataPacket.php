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
use pocketmine\network\mcpe\protocol\types\sound\SoundDataEvent;

class ClientboundUpdateSoundDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_UPDATE_SOUND_DATA_PACKET;

	private int $serverSoundHandle;
	private ?SoundDataEvent $stop = null;
	private ?SoundDataEvent $setVolume = null;
	private ?SoundDataEvent $setPitch = null;
	private ?SoundDataEvent $fade = null;
	private ?SoundDataEvent $seekTo = null;
	private ?SoundDataEvent $pause = null;
	private ?SoundDataEvent $resume = null;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $serverSoundHandle,
		?SoundDataEvent $stop,
		?SoundDataEvent $setVolume,
		?SoundDataEvent $setPitch,
		?SoundDataEvent $fade,
		?SoundDataEvent $seekTo,
		?SoundDataEvent $pause,
		?SoundDataEvent $resume,
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

	public function getStop() : ?SoundDataEvent{ return $this->stop; }

	public function getSetVolume() : ?SoundDataEvent{ return $this->setVolume; }

	public function getSetPitch() : ?SoundDataEvent{ return $this->setPitch; }

	public function getFade() : ?SoundDataEvent{ return $this->fade; }

	public function getSeekTo() : ?SoundDataEvent{ return $this->seekTo; }

	public function getPause() : ?SoundDataEvent{ return $this->pause; }

	public function getResume() : ?SoundDataEvent{ return $this->resume; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->serverSoundHandle = LE::readUnsignedLong($in);
		$this->stop = CommonTypes::readOptional($in, SoundDataEvent::read(...));
		$this->setVolume = CommonTypes::readOptional($in, SoundDataEvent::read(...));
		$this->setPitch = CommonTypes::readOptional($in, SoundDataEvent::read(...));
		$this->fade = CommonTypes::readOptional($in, SoundDataEvent::read(...));
		$this->seekTo = CommonTypes::readOptional($in, SoundDataEvent::read(...));
		$this->pause = CommonTypes::readOptional($in, SoundDataEvent::read(...));
		$this->resume = CommonTypes::readOptional($in, SoundDataEvent::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedLong($out, $this->serverSoundHandle);
		CommonTypes::writeOptional($out, $this->stop, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->setVolume, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->setPitch, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->fade, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->seekTo, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->pause, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
		CommonTypes::writeOptional($out, $this->resume, fn(ByteBufferWriter $out, SoundDataEvent $data) => $data->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundUpdateSoundData($this);
	}
}
