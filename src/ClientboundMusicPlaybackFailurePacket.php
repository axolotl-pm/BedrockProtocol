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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\MusicPlaybackFailureEntry;
use function count;

final class ClientboundMusicPlaybackFailurePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_MUSIC_PLAYBACK_FAILURE_PACKET;

	/** @var MusicPlaybackFailureEntry[] */
	private array $failures;

	/**
	 * @generate-create-func
	 * @param MusicPlaybackFailureEntry[] $failures
	 */
	public static function create(array $failures) : self{
		$result = new self;
		$result->failures = $failures;
		return $result;
	}

	/** @return MusicPlaybackFailureEntry[] */
	public function getFailures() : array{
		return $this->failures;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->failures));
		foreach($this->failures as $failure){
			$failure->write($out);
		}
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->failures = [];
		$failureCount = VarInt::readUnsignedInt($in);
		if($failureCount > 28){
			throw new PacketDecodeException("Too many failures in ClientboundMusicPlaybackFailurePacket");
		}
		for($i = 0, $len = $failureCount; $i < $len; ++$i){
			$this->failures[] = MusicPlaybackFailureEntry::read($in);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundMusicPlaybackFailure($this);
	}
}
