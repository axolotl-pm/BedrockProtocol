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
use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use Ramsey\Uuid\UuidInterface;
use function count;

class PlayerListPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_LIST_PACKET;

	public const TYPE_REMOVE = 0;
	public const TYPE_ADD = 1;

	private const INNER_TYPES = [
		self::TYPE_ADD => 0,
		self::TYPE_REMOVE => 1,
	];

	/** @var PlayerListEntry[]|UuidInterface[] */
	private array $entries = [];

	/**
	 * @generate-create-func
	 * @param PlayerListEntry[]|UuidInterface[] $entries
	 */
	private static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	/**
	 * @param PlayerListEntry[] $entries
	 */
	public static function add(array $entries) : self{
		return self::create($entries);
	}

	/**
	 * @param UuidInterface[] $entries
	 */
	public static function remove(array $entries) : self{
		return self::create($entries);
	}

	/**
	 * @return PlayerListEntry[]|UuidInterface[]
	 */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$count = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $count; ++$i){

			$type = VarInt::readUnsignedInt($in);
			$innerType = Byte::readUnsigned($in);
			$expectedInnerType = self::INNER_TYPES[$type] ?? "unknown";
			if($innerType !== $expectedInnerType){
				throw new PacketDecodeException("Unexpected inner type $innerType for player list entry type $type, expected $expectedInnerType");
			}
			if($type === self::TYPE_REMOVE){
				$this->entries[] = CommonTypes::getUUID($in);
			}elseif($type === self::TYPE_ADD){
				$entry = new PlayerListEntry();
				$entry->uuid = CommonTypes::getUUID($in);
				$entry->actorUniqueId = CommonTypes::getActorUniqueId($in);
				$entry->username = CommonTypes::getString($in);
				$entry->xboxUserId = CommonTypes::getString($in);
				$entry->platformChatId = CommonTypes::getString($in);
				$entry->buildPlatform = LE::readSignedInt($in);
				$entry->skinData = CommonTypes::getSkin($in);
				$entry->isTeacher = CommonTypes::getBool($in);
				$entry->isHost = CommonTypes::getBool($in);
				$entry->isSubClient = CommonTypes::getBool($in);
				$entry->color = CommonTypes::readColor($in);

				$this->entries[] = $entry;
			}else{
				throw new PacketDecodeException("Unknown player list entry type $type");
			}
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->entries));
		foreach($this->entries as $entry){
			$type = $entry instanceof UuidInterface ? self::TYPE_REMOVE : self::TYPE_ADD;
			VarInt::writeUnsignedInt($out, $type);
			Byte::writeUnsigned($out, self::INNER_TYPES[$type]);
			if($entry instanceof UuidInterface){
				CommonTypes::putUUID($out, $entry);
			}else{
				CommonTypes::putUUID($out, $entry->uuid);
				CommonTypes::putActorUniqueId($out, $entry->actorUniqueId);
				CommonTypes::putString($out, $entry->username);
				CommonTypes::putString($out, $entry->xboxUserId);
				CommonTypes::putString($out, $entry->platformChatId);
				LE::writeSignedInt($out, $entry->buildPlatform);
				CommonTypes::putSkin($out, $entry->skinData);
				CommonTypes::putBool($out, $entry->isTeacher);
				CommonTypes::putBool($out, $entry->isHost);
				CommonTypes::putBool($out, $entry->isSubClient);
				CommonTypes::writeColor($out, $entry->color ?? new Color(255, 255, 255));
			}
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerList($this);
	}
}
