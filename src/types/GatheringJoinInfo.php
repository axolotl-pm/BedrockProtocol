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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use Ramsey\Uuid\UuidInterface;

/**
 * Spec name: gatheringsConfig
 */
final class GatheringJoinInfo{

	public function __construct(
		private ?UuidInterface $experienceId,
		private ?string $experienceName,
		private ?UuidInterface $experienceWorldId,
		private ?string $experienceWorldName,
		private ?string $creatorId,
		private ?UuidInterface $targetId,
		private ?string $scenarioId,
		private ?string $serverId,
	){}

	public function getExperienceId() : ?UuidInterface{ return $this->experienceId; }

	public function getExperienceName() : ?string{ return $this->experienceName; }

	public function getExperienceWorldId() : ?UuidInterface{ return $this->experienceWorldId; }

	public function getExperienceWorldName() : ?string{ return $this->experienceWorldName; }

	public function getCreatorId() : ?string{ return $this->creatorId; }

	public function getTargetId() : ?UuidInterface{ return $this->targetId; }

	public function getScenarioId() : ?string{ return $this->scenarioId; }

	public function getServerId() : ?string{ return $this->serverId; }

	public static function read(ByteBufferReader $in) : self{
		$experienceId = CommonTypes::readOptional($in, CommonTypes::getUUID(...));
		$experienceName = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$experienceWorldId = CommonTypes::readOptional($in, CommonTypes::getUUID(...));
		$experienceWorldName = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$creatorId = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$targetId = CommonTypes::readOptional($in, CommonTypes::getUUID(...));
		$scenarioId = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$serverId = CommonTypes::readOptional($in, CommonTypes::getString(...));

		return new self(
			$experienceId,
			$experienceName,
			$experienceWorldId,
			$experienceWorldName,
			$creatorId,
			$targetId,
			$scenarioId,
			$serverId,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->experienceId, CommonTypes::putUUID(...));
		CommonTypes::writeOptional($out, $this->experienceName, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->experienceWorldId, CommonTypes::putUUID(...));
		CommonTypes::writeOptional($out, $this->experienceWorldName, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->creatorId, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->targetId, CommonTypes::putUUID(...));
		CommonTypes::writeOptional($out, $this->scenarioId, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->serverId, CommonTypes::putString(...));
	}
}
