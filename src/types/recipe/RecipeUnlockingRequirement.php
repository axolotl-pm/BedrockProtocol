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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class RecipeUnlockingRequirement{

	/**
	 * @param RecipeIngredient[]|null $unlockingIngredients
	 * @phpstan-param list<RecipeIngredient>|null $unlockingIngredients
	 */
	public function __construct(
		private RecipeUnlockingContext $unlockingContext,
		private ?array $unlockingIngredients
	){}

	public function getUnlockingContext() : RecipeUnlockingContext{ return $this->unlockingContext; }

	/**
	 * @return RecipeIngredient[]|null
	 * @phpstan-return list<RecipeIngredient>|null
	 */
	public function getUnlockingIngredients() : ?array{ return $this->unlockingIngredients; }

	public static function read(ByteBufferReader $in) : self{
		$unlockingContext = RecipeUnlockingContext::fromPacket(VarInt::readSignedInt($in));
		$unlockingIngredients = CommonTypes::readOptional($in, static function(ByteBufferReader $in) : array{
			$result = [];
			for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
				$result[] = CommonTypes::getRecipeIngredient($in);
			}
			return $result;
		});

		return new self($unlockingContext, $unlockingIngredients);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->unlockingContext->value);
		CommonTypes::writeOptional($out, $this->unlockingIngredients, static function(ByteBufferWriter $out, array $unlockingIngredients) : void{
			VarInt::writeUnsignedInt($out, count($unlockingIngredients));
			foreach($unlockingIngredients as $ingredient){
				CommonTypes::putRecipeIngredient($out, $ingredient);
			}
		});
	}
}
