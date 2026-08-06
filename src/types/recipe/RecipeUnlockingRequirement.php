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

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class RecipeUnlockingRequirement{

	/**
	 * @param RecipeIngredient[]|null $unlockingIngredients
	 * @phpstan-param list<RecipeIngredient>|null $unlockingIngredients
	 */
	public function __construct(
		private RecipeUnlockingContext $unlockingContext,
		private ?array $unlockingIngredients
	){
		if($unlockingContext !== RecipeUnlockingContext::NONE && $unlockingIngredients !== null){
			throw new \InvalidArgumentException("Unlocking ingredients can only be set when unlocking context is NONE");
		}
	}

	public function getUnlockingContext() : RecipeUnlockingContext{ return $this->unlockingContext; }

	/**
	 * @return RecipeIngredient[]|null
	 * @phpstan-return list<RecipeIngredient>|null
	 */
	public function getUnlockingIngredients() : ?array{ return $this->unlockingIngredients; }

	public static function read(ByteBufferReader $in) : self{
		//I don't know what the point of this structure is. It could easily have been a list<RecipeIngredient> instead.
		//It's basically just an optional list, which could have been done by an empty list wherever it's not needed.
		$unlockingContext = RecipeUnlockingContext::fromPacket(VarInt::readSignedInt($in));
		$unlockingIngredients = CommonTypes::readOptional($in, static fn($in) => CommonTypes::readList($in, CommonTypes::getRecipeIngredient(...)));
		if($unlockingContext !== RecipeUnlockingContext::NONE && $unlockingIngredients !== null){
			//this is a runtime error, make sure the correct exception type is thrown
			throw new PacketDecodeException("Unlocking ingredients should only be set when context is CONTEXT_NONE");
		}

		return new self($unlockingContext, $unlockingIngredients);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->unlockingContext->value);
		CommonTypes::writeOptional($out, $this->unlockingIngredients, static fn($out, $v) => CommonTypes::writeList($out, $v, CommonTypes::putRecipeIngredient(...)));
	}
}
