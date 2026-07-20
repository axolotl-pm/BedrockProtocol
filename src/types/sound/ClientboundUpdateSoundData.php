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

namespace pocketmine\network\mcpe\protocol\types\sound;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\ClientboundUpdateSoundDataPacket;

/**
 * @see ClientboundUpdateSoundDataPacket
 */
final class ClientboundUpdateSoundData{

	public function __construct(
		private ClientboundUpdateSoundDataType $type,
		private ?float $volume,
		private ?float $pitch,
		private ?float $duration,
		private ?float $targetVolume,
		private ?float $seconds,
	){}

	public static function stop() : self{
		return new self(
			ClientboundUpdateSoundDataType::STOP,
			null,
			null,
			null,
			null,
			null
		);
	}

	public static function setVolume(float $volume) : self{
		return new self(
			ClientboundUpdateSoundDataType::SET_VOLUME,
			$volume,
			null,
			null,
			null,
			null
		);
	}

	public static function setPitch(float $pitch) : self{
		return new self(
			ClientboundUpdateSoundDataType::SET_PITCH,
			null,
			$pitch,
			null,
			null,
			null
		);
	}

	public static function fade(float $duration, float $targetVolume) : self{
		return new self(
			ClientboundUpdateSoundDataType::FADE,
			null,
			null,
			$duration,
			$targetVolume,
			null
		);
	}

	public static function seekTo(float $seconds) : self{
		return new self(
			ClientboundUpdateSoundDataType::SEEK_TO,
			null,
			null,
			null,
			null,
			$seconds
		);
	}

	public static function pause() : self{
		return new self(
			ClientboundUpdateSoundDataType::PAUSE,
			null,
			null,
			null,
			null,
			null
		);
	}

	public static function resume() : self{
		return new self(
			ClientboundUpdateSoundDataType::RESUME,
			null,
			null,
			null,
			null,
			null
		);
	}

	public static function read(ByteBufferReader $in) : self{
		$type = ClientboundUpdateSoundDataType::fromPacket(LE::readUnsignedInt($in));
		return match($type){
			ClientboundUpdateSoundDataType::STOP => self::stop(),
			ClientboundUpdateSoundDataType::SET_VOLUME => self::setVolume(
				volume: LE::readFloat($in)
			),
			ClientboundUpdateSoundDataType::SET_PITCH => self::setPitch(
				pitch: LE::readFloat($in)
			),
			ClientboundUpdateSoundDataType::FADE => self::fade(
				duration: LE::readFloat($in),
				targetVolume: LE::readFloat($in)
			),
			ClientboundUpdateSoundDataType::SEEK_TO => self::seekTo(
				seconds: LE::readFloat($in)
			),
			ClientboundUpdateSoundDataType::PAUSE => self::pause(),
			ClientboundUpdateSoundDataType::RESUME => self::resume(),
		};
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->type->value);
		switch($this->type){
			case ClientboundUpdateSoundDataType::STOP:
			case ClientboundUpdateSoundDataType::PAUSE:
			case ClientboundUpdateSoundDataType::RESUME:
				break;
			case ClientboundUpdateSoundDataType::SET_VOLUME:
				if($this->volume === null){
					throw new \LogicException("ClientboundUpdateSoundData with type SET_VOLUME requires volume");
				}
				LE::writeFloat($out, $this->volume);
				break;
			case ClientboundUpdateSoundDataType::SET_PITCH:
				if($this->pitch === null){
					throw new \LogicException("ClientboundUpdateSoundData with type SET_PITCH requires pitch");
				}
				LE::writeFloat($out, $this->pitch);
				break;
			case ClientboundUpdateSoundDataType::FADE:
				if($this->duration === null || $this->targetVolume === null){
					throw new \LogicException("ClientboundUpdateSoundData with type FADE requires duration and targetVolume");
				}
				LE::writeFloat($out, $this->duration);
				LE::writeFloat($out, $this->targetVolume);
				break;
			case ClientboundUpdateSoundDataType::SEEK_TO:
				if($this->seconds === null){
					throw new \LogicException("ClientboundUpdateSoundData with type SEEK_TO requires seconds");
				}
				LE::writeFloat($out, $this->seconds);
				break;
		}
	}
}
