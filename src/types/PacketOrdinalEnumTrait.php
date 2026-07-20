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

use pocketmine\network\mcpe\protocol\PacketDecodeException;

/**
 * Trait for enums serialized in packets. Provides a convenient helper method to read, validate and properly bail on
 * invalid values.
 */
trait PacketOrdinalEnumTrait{

	/**
	 * @throws PacketDecodeException
	 */
	public static function fromPacket(string $value) : self{
		$enum = self::tryFrom($value);
		if($enum === null){
			throw new PacketDecodeException("Invalid raw value $value for " . static::class);
		}

		return $enum;
	}

	public function ordinal() : int{
		/** @var array<string, int>|null $ordinals */
		static $ordinals = null;

		if($ordinals === null){
			foreach(self::cases() as $i => $case){
				$ordinals[$case->name] = $i;
			}
		}

		return $ordinals[$this->name];
	}

	public static function fromOrdinal(int $ordinal) : self{
		/** @var list<self>|null $cases */
		static $cases = null;

		$cases ??= self::cases();

		return $cases[$ordinal] ?? throw new PacketDecodeException("Invalid ordinal $ordinal");
	}
}
