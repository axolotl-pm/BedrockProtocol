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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient;

final class ItemStackRequestNetworkDescriptor{

	public function __construct(
		private RecipeIngredient $ingredient,
		private int $blockRuntimeId,
		private string $extraData,
	){}

	public function getRecipeIngredient() : RecipeIngredient{ return $this->ingredient; }

	public function getBlockRuntimeId() : int{ return $this->blockRuntimeId; }

	public function getExtraData() : string{ return $this->extraData; }

	public static function read(ByteBufferReader $in) : self{
		$ingredient = CommonTypes::getItemStackIngredient($in);
		$blockRuntimeId = VarInt::readUnsignedInt($in);
		$extraData = CommonTypes::getString($in);
		return new self($ingredient, $blockRuntimeId, $extraData);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putItemStackIngredient($out, $this->ingredient);
		VarInt::writeUnsignedInt($out, $this->blockRuntimeId);
		CommonTypes::putString($out, $this->extraData);
	}
}
