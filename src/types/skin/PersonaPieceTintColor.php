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

namespace pocketmine\network\mcpe\protocol\types\skin;

use pocketmine\color\Color;
use function count;

final class PersonaPieceTintColor{

	public const PIECE_TYPE_PERSONA_EYES = PieceType::EYES;
	public const PIECE_TYPE_PERSONA_HAIR = PieceType::HAIR;
	public const PIECE_TYPE_PERSONA_MOUTH = PieceType::MOUTH;
	public const EXPECTED_COLOR_COUNT = 4;

	/**
	 * @param Color[] $colors
	 */
	public function __construct(
		private PieceType $pieceType,
		private array $colors
	){
		if(count($this->colors) !== self::EXPECTED_COLOR_COUNT){
			throw new \InvalidArgumentException("Expected exactly " . self::EXPECTED_COLOR_COUNT . " colors");
		}
	}

	public function getPieceType() : PieceType{
		return $this->pieceType;
	}

	/**
	 * @return Color[]
	 */
	public function getColors() : array{
		return $this->colors;
	}
}
