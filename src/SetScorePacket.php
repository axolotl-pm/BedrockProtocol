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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntryAction;
use function count;

class SetScorePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_SCORE_PACKET;

	public const TYPE_CHANGE = 0;
	public const TYPE_REMOVE = 1;

	/** @var ScorePacketEntry[] */
	public array $entries = [];

	/**
	 * @generate-create-func
	 * @param ScorePacketEntry[] $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		for($i = 0, $i2 = VarInt::readUnsignedInt($in); $i < $i2; ++$i){
			$entry = new ScorePacketEntry();
			$entry->type = ScorePacketEntryAction::fromOrdinal(VarInt::readUnsignedInt($in));
			$type = ScorePacketEntryAction::fromPacket(CommonTypes::getString($in));
			if($entry->type->value !== $type->value){
				throw new PacketDecodeException("Expected Type {$entry->type->value}, got {$type->value}");
			}
			switch($entry->type){
				case ScorePacketEntryAction::REMOVE:
					$entry->scoreboardId = VarInt::readSignedLong($in);
					$entry->objectiveName = CommonTypes::readOptional($in, CommonTypes::getString(...));
					break;
				case ScorePacketEntryAction::CHANGE_PLAYER:
				case ScorePacketEntryAction::CHANGE_ENTITY:
					$entry->scoreboardId = VarInt::readSignedLong($in);
					$entry->objectiveName = CommonTypes::getString($in);
					$entry->score = LE::readSignedInt($in);
					$entry->actorUniqueId = CommonTypes::getActorUniqueId($in);
					break;
				case ScorePacketEntryAction::CHANGE_FAKE_PLAYER:
					$entry->scoreboardId = VarInt::readSignedLong($in);
					$entry->objectiveName = CommonTypes::getString($in);
					$entry->score = LE::readSignedInt($in);
					$entry->customName = CommonTypes::getString($in);
					break;
				default:
					throw new PacketDecodeException("Unknown entry type " . $entry->type->value);
			}
			$this->entries[] = $entry;
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->entries));
		foreach($this->entries as $entry){
			VarInt::writeUnsignedInt($out, $entry->type->ordinal());
			CommonTypes::putString($out, $entry->type->value);
			switch($entry->type){
				case ScorePacketEntryAction::REMOVE:
					VarInt::writeSignedLong($out, $entry->scoreboardId);
					CommonTypes::writeOptional($out, $entry->objectiveName, CommonTypes::putString(...));
					break;
				case ScorePacketEntryAction::CHANGE_PLAYER:
				case ScorePacketEntryAction::CHANGE_ENTITY:
					VarInt::writeSignedLong($out, $entry->scoreboardId);
					CommonTypes::putString($out, $entry->objectiveName ?? throw new \InvalidArgumentException("ObjectiveName must be set for this entry type"));
					LE::writeSignedInt($out, $entry->score);
					CommonTypes::putActorUniqueId($out, $entry->actorUniqueId);
					break;
				case ScorePacketEntryAction::CHANGE_FAKE_PLAYER:
					VarInt::writeSignedLong($out, $entry->scoreboardId);
					CommonTypes::putString($out, $entry->objectiveName ?? throw new \InvalidArgumentException("ObjectiveName must be set for this entry type"));
					LE::writeSignedInt($out, $entry->score);
					CommonTypes::putString($out, $entry->customName);
					break;
			}
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetScore($this);
	}
}
