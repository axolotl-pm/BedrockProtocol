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

class ScorePacketEntry{
	public const TYPE_REMOVE = ScorePacketEntryAction::REMOVE;
	public const TYPE_PLAYER = ScorePacketEntryAction::CHANGE_PLAYER;
	public const TYPE_ENTITY = ScorePacketEntryAction::CHANGE_ENTITY;
	public const TYPE_FAKE_PLAYER = ScorePacketEntryAction::CHANGE_FAKE_PLAYER;

	public int $scoreboardId;
	public ?string $objectiveName;
	public int $score;
	public ScorePacketEntryAction $type;
	/** @var int|null (if type entity or player) */
	public ?int $actorUniqueId;
	/** @var string|null (if type fake player) */
	public ?string $customName;
}
