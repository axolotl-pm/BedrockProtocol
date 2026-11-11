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
use pocketmine\network\mcpe\protocol\types\chat\ChatFormatting;
use pocketmine\network\mcpe\protocol\types\chat\SignedChat;

final class ChatPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CHAT_PACKET;

	private SignedChat $signedChat;
	private ?string $roomId = null;
	private ?ChatFormatting $formatting = null;

	/**
	 * @generate-create-func
	 */
	public static function create(SignedChat $signedChat, ?string $roomId = null, ?ChatFormatting $formatting = null) : self{
		$result = new self;
		$result->signedChat = $signedChat;
		$result->roomId = $roomId;
		$result->formatting = $formatting;
		return $result;
	}

	public function encodePayload(ByteBufferWriter $out) : void{
		$this->signedChat->write($out);
		CommonTypes::writeOptional($out, $this->roomId, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->formatting, static fn(ByteBufferWriter $out, ChatFormatting $formatting) => $formatting->write($out));
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->signedChat = SignedChat::read($in);
		$this->roomId = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$this->formatting = CommonTypes::readOptional($in, static fn(ByteBufferReader $in) => ChatFormatting::read($in));
	}

	public function getSignedChat() : SignedChat{
		return $this->signedChat;
	}

	public function getRoomId() : ?string{
		return $this->roomId;
	}

	public function getFormatting() : ?ChatFormatting{
		return $this->formatting;
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleChat($this);
	}
}
