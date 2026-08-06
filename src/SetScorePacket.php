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

class SetScorePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_SCORE_PACKET;

	/**
	 * @var ScorePacketEntry[]
	 * @phpstan-var list<ScorePacketEntry>
	 */
	private array $entries = [];

	/**
	 * @generate-create-func
	 * @param ScorePacketEntry[] $entries
	 * @phpstan-param list<ScorePacketEntry> $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	/**
	 * @return ScorePacketEntry[]
	 * @phpstan-return list<ScorePacketEntry>
	 */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->entries = CommonTypes::readList($in, function(ByteBufferReader $in) : ScorePacketEntry{
			$action = ScorePacketEntryAction::fromOrdinal(VarInt::readUnsignedInt($in));
			$innerType = CommonTypes::getString($in);
			if($action !== ScorePacketEntryAction::fromPacket($innerType)){
				throw new PacketDecodeException("Expected inner type {$action->value} for score packet entry ordinal {$action->ordinal()}, got $innerType");
			}

			$entry = new ScorePacketEntry();
			$entry->type = $action;

			//same for all types
			$entry->scoreboardId = VarInt::readSignedLong($in);
			$entry->objectiveName = CommonTypes::getString($in);

			if($action === ScorePacketEntryAction::REMOVE){
				//NOOP
			}elseif($action === ScorePacketEntryAction::CHANGE_PLAYER || $action === ScorePacketEntryAction::CHANGE_ENTITY){
				$entry->score = LE::readSignedInt($in);
				$entry->actorUniqueId = CommonTypes::getActorUniqueId($in);
			}elseif($action === ScorePacketEntryAction::CHANGE_FAKE_PLAYER){
				$entry->score = LE::readSignedInt($in);
				$entry->customName = CommonTypes::getString($in);
			}
			return $entry;
		});
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeList($out, $this->entries, function(ByteBufferWriter $out, ScorePacketEntry $entry) : void{
			VarInt::writeUnsignedInt($out, $entry->type->ordinal());
			CommonTypes::putString($out, $entry->type->value);

			//same for all types
			VarInt::writeSignedLong($out, $entry->scoreboardId);
			CommonTypes::putString($out, $entry->objectiveName ?? throw new \InvalidArgumentException("ObjectiveName must be set for this entry type"));

			if($entry->type === ScorePacketEntryAction::REMOVE){
				//NOOP
			}elseif($entry->type === ScorePacketEntryAction::CHANGE_PLAYER || $entry->type === ScorePacketEntryAction::CHANGE_ENTITY){
				LE::writeSignedInt($out, $entry->score);
				CommonTypes::putActorUniqueId($out, $entry->actorUniqueId);
			}elseif($entry->type === ScorePacketEntryAction::CHANGE_FAKE_PLAYER){
				LE::writeSignedInt($out, $entry->score);
				CommonTypes::putString($out, $entry->customName ?? throw new \InvalidArgumentException("CustomName must be set for this entry type"));
			}
		});
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetScore($this);
	}
}
