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
use pocketmine\network\mcpe\protocol\types\MusicPlaybackState;

final class ClientboundOnlineMusicPlaybackResponsePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_ONLINE_MUSIC_PLAYBACK_RESPONSE_PACKET;

	private int $requestId;
	private MusicPlaybackState $state;
	private string $sharedMetadata; //compact JWT in spec
	private string $playbackContent; //compact JWT in spec

	/**
	 * @generate-create-func
	 */
	public static function create(int $requestId, MusicPlaybackState $state, string $sharedMetadata, string $playbackContent) : self{
		$result = new self;
		$result->requestId = $requestId;
		$result->state = $state;
		$result->sharedMetadata = $sharedMetadata;
		$result->playbackContent = $playbackContent;
		return $result;
	}

	public function getRequestId() : int{
		return $this->requestId;
	}

	public function getState() : MusicPlaybackState{
		return $this->state;
	}

	public function getSharedMetadata() : string{
		return $this->sharedMetadata;
	}

	public function getPlaybackContent() : string{
		return $this->playbackContent;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->requestId);
		CommonTypes::putString($out, $this->state->value);
		CommonTypes::putString($out, $this->sharedMetadata);
		CommonTypes::putString($out, $this->playbackContent);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->requestId = LE::readUnsignedInt($in);
		$this->state = MusicPlaybackState::fromPacket(CommonTypes::getString($in));
		$this->sharedMetadata = CommonTypes::getString($in);
		$this->playbackContent = CommonTypes::getString($in);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundOnlineMusicPlaybackResponse($this);
	}
}
