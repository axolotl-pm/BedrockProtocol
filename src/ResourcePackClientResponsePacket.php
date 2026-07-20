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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

class ResourcePackClientResponsePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CLIENT_RESPONSE_PACKET;

	public const STATUS_REFUSED = 0;
	public const STATUS_SEND_PACKS = 1;
	public const STATUS_HAVE_ALL_PACKS = 2;
	public const STATUS_COMPLETED = 3;

	public const STATUS_ID_REFUSED = "cancel";
	public const STATUS_ID_SEND_PACKS = "downloading";
	public const STATUS_ID_HAVE_ALL_PACKS = "downloadingfinished";
	public const STATUS_ID_COMPLETED = "resourcepackstackfinished";

	public int $status;
	public string $statusId = "";
	/** @var string[] */
	public array $packIds = [];

	/**
	 * @generate-create-func
	 * @param string[] $packIds
	 */
	public static function create(int $status, string $statusId, array $packIds) : self{
		$result = new self;
		$result->status = $status;
		$result->statusId = $statusId;
		$result->packIds = $packIds;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->status = Byte::readUnsigned($in);
		$this->statusId = CommonTypes::getString($in);
		$this->packIds = [];
		if($this->status === self::STATUS_SEND_PACKS){
			$entryCount = VarInt::readUnsignedInt($in);
			while($entryCount-- > 0){
				$this->packIds[] = CommonTypes::getString($in);
			}
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->status);
		CommonTypes::putString($out, $this->statusId);
		if($this->status === self::STATUS_SEND_PACKS){
			VarInt::writeUnsignedInt($out, count($this->packIds));
			foreach($this->packIds as $id){
				CommonTypes::putString($out, $id);
			}
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePackClientResponse($this);
	}
}
