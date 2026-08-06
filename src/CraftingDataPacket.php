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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\recipe\MaterialReducerRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\MultiRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\PotionContainerChangeRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\PotionTypeRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\ShapedRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\ShapelessRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\SmithingTransformRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\SmithingTrimRecipe;

class CraftingDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CRAFTING_DATA_PACKET;

	/**
	 * @var ShapedRecipe[]
	 * @phpstan-var list<ShapedRecipe>
	 */
	public array $shapedRecipes = [];
	/**
	 * @var ShapelessRecipe[]
	 * @phpstan-var list<ShapelessRecipe>
	 */
	public array $shapelessRecipes = [];
	/**
	 * @var MultiRecipe[]
	 * @phpstan-var list<MultiRecipe>
	 */
	public array $multiRecipes = [];
	/**
	 * @var ShapelessRecipe[]
	 * @phpstan-var list<ShapelessRecipe>
	 */
	public array $userDataShapelessRecipes = [];
	/**
	 * @var ShapelessRecipe[]
	 * @phpstan-var list<ShapelessRecipe>
	 */
	public array $shapelessChemistryRecipes = [];
	/**
	 * @var ShapedRecipe[]
	 * @phpstan-var list<ShapedRecipe>
	 */
	public array $shapedChemistryRecipes = [];
	/**
	 * @var SmithingTransformRecipe[]
	 * @phpstan-var list<SmithingTransformRecipe>
	 */
	public array $smithingTransformRecipes = [];
	/**
	 * @var SmithingTrimRecipe[]
	 * @phpstan-var list<SmithingTrimRecipe>
	 */
	public array $smithingTrimRecipes = [];

	/**
	 * @var PotionTypeRecipe[]
	 * @phpstan-var list<PotionTypeRecipe>
	 */
	public array $potionTypeRecipes = [];
	/**
	 * @var PotionContainerChangeRecipe[]
	 * @phpstan-var list<PotionContainerChangeRecipe>
	 */
	public array $potionContainerRecipes = [];
	/**
	 * @var MaterialReducerRecipe[]
	 * @phpstan-var list<MaterialReducerRecipe>
	 */
	public array $materialReducerRecipes = [];
	public bool $cleanRecipes = false;

	/**
	 * @generate-create-func
	 * @param ShapedRecipe[]                $shapedRecipes
	 * @param ShapelessRecipe[]             $shapelessRecipes
	 * @param MultiRecipe[]                 $multiRecipes
	 * @param ShapelessRecipe[]             $userDataShapelessRecipes
	 * @param ShapelessRecipe[]             $shapelessChemistryRecipes
	 * @param ShapedRecipe[]                $shapedChemistryRecipes
	 * @param SmithingTransformRecipe[]     $smithingTransformRecipes
	 * @param SmithingTrimRecipe[]          $smithingTrimRecipes
	 * @param PotionTypeRecipe[]            $potionTypeRecipes
	 * @param PotionContainerChangeRecipe[] $potionContainerRecipes
	 * @param MaterialReducerRecipe[]       $materialReducerRecipes
	 * @phpstan-param list<ShapedRecipe>                $shapedRecipes
	 * @phpstan-param list<ShapelessRecipe>             $shapelessRecipes
	 * @phpstan-param list<MultiRecipe>                 $multiRecipes
	 * @phpstan-param list<ShapelessRecipe>             $userDataShapelessRecipes
	 * @phpstan-param list<ShapelessRecipe>             $shapelessChemistryRecipes
	 * @phpstan-param list<ShapedRecipe>                $shapedChemistryRecipes
	 * @phpstan-param list<SmithingTransformRecipe>     $smithingTransformRecipes
	 * @phpstan-param list<SmithingTrimRecipe>          $smithingTrimRecipes
	 * @phpstan-param list<PotionTypeRecipe>            $potionTypeRecipes
	 * @phpstan-param list<PotionContainerChangeRecipe> $potionContainerRecipes
	 * @phpstan-param list<MaterialReducerRecipe>       $materialReducerRecipes
	 */
	public static function create(
		array $shapedRecipes,
		array $shapelessRecipes,
		array $multiRecipes,
		array $userDataShapelessRecipes,
		array $shapelessChemistryRecipes,
		array $shapedChemistryRecipes,
		array $smithingTransformRecipes,
		array $smithingTrimRecipes,
		array $potionTypeRecipes,
		array $potionContainerRecipes,
		array $materialReducerRecipes,
		bool $cleanRecipes,
	) : self{
		$result = new self;
		$result->shapedRecipes = $shapedRecipes;
		$result->shapelessRecipes = $shapelessRecipes;
		$result->multiRecipes = $multiRecipes;
		$result->userDataShapelessRecipes = $userDataShapelessRecipes;
		$result->shapelessChemistryRecipes = $shapelessChemistryRecipes;
		$result->shapedChemistryRecipes = $shapedChemistryRecipes;
		$result->smithingTransformRecipes = $smithingTransformRecipes;
		$result->smithingTrimRecipes = $smithingTrimRecipes;
		$result->potionTypeRecipes = $potionTypeRecipes;
		$result->potionContainerRecipes = $potionContainerRecipes;
		$result->materialReducerRecipes = $materialReducerRecipes;
		$result->cleanRecipes = $cleanRecipes;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->shapedRecipes = CommonTypes::readList($in, ShapedRecipe::decode(...));
		$this->shapelessRecipes = CommonTypes::readList($in, ShapelessRecipe::decode(...));
		$this->multiRecipes = CommonTypes::readList($in, MultiRecipe::decode(...));
		$this->userDataShapelessRecipes = CommonTypes::readList($in, ShapelessRecipe::decode(...));
		$this->shapelessChemistryRecipes = CommonTypes::readList($in, ShapelessRecipe::decode(...));
		$this->shapedChemistryRecipes = CommonTypes::readList($in, ShapedRecipe::decode(...));
		$this->smithingTransformRecipes = CommonTypes::readList($in, SmithingTransformRecipe::decode(...));
		$this->smithingTrimRecipes = CommonTypes::readList($in, SmithingTrimRecipe::decode(...));
		$this->potionTypeRecipes = CommonTypes::readList($in, PotionTypeRecipe::decode(...));
		$this->potionContainerRecipes = CommonTypes::readList($in, PotionContainerChangeRecipe::decode(...));
		$this->materialReducerRecipes = CommonTypes::readList($in, MaterialReducerRecipe::decode(...));
		$this->cleanRecipes = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeList($out, $this->shapedRecipes, fn(ByteBufferWriter $out, ShapedRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->shapelessRecipes, fn(ByteBufferWriter $out, ShapelessRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->multiRecipes, fn(ByteBufferWriter $out, MultiRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->userDataShapelessRecipes, fn(ByteBufferWriter $out, ShapelessRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->shapelessChemistryRecipes, fn(ByteBufferWriter $out, ShapelessRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->shapedChemistryRecipes, fn(ByteBufferWriter $out, ShapedRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->smithingTransformRecipes, fn(ByteBufferWriter $out, SmithingTransformRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->smithingTrimRecipes, fn(ByteBufferWriter $out, SmithingTrimRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->potionTypeRecipes, fn(ByteBufferWriter $out, PotionTypeRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->potionContainerRecipes, fn(ByteBufferWriter $out, PotionContainerChangeRecipe $recipe) => $recipe->encode($out));
		CommonTypes::writeList($out, $this->materialReducerRecipes, fn(ByteBufferWriter $out, MaterialReducerRecipe $recipe) => $recipe->encode($out));
		CommonTypes::putBool($out, $this->cleanRecipes);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCraftingData($this);
	}
}
