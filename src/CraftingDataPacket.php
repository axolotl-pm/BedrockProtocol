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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\recipe\MaterialReducerRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\MaterialReducerRecipeOutput;
use pocketmine\network\mcpe\protocol\types\recipe\MultiRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\PotionContainerChangeRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\PotionTypeRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\ShapedRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\ShapelessRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\SmithingTransformRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\SmithingTrimRecipe;
use function count;

class CraftingDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CRAFTING_DATA_PACKET;

	/** @var ShapedRecipe[] */
	public array $shapedRecipes = [];
	/** @var ShapelessRecipe[] */
	public array $shapelessRecipes = [];
	/** @var MultiRecipe[] */
	public array $multiRecipes = [];
	/** @var ShapelessRecipe[] */
	public array $userDataShapelessRecipes = [];
	/** @var ShapelessRecipe[] */
	public array $shapelessChemistryRecipes = [];
	/** @var ShapedRecipe[] */
	public array $shapedChemistryRecipes = [];
	/** @var SmithingTransformRecipe[] */
	public array $smithingTransformRecipes = [];
	/** @var SmithingTrimRecipe[] */
	public array $smithingTrimRecipes = [];

	/** @var PotionTypeRecipe[] */
	public array $potionTypeRecipes = [];
	/** @var PotionContainerChangeRecipe[] */
	public array $potionContainerRecipes = [];
	/** @var MaterialReducerRecipe[] */
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
		$this->shapedRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->shapedRecipes[] = ShapedRecipe::decode($in);
		}
		$this->shapelessRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->shapelessRecipes[] = ShapelessRecipe::decode($in);
		}
		$this->multiRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->multiRecipes[] = MultiRecipe::decode($in);
		}
		$this->userDataShapelessRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->userDataShapelessRecipes[] = ShapelessRecipe::decode($in);
		}
		$this->shapelessChemistryRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->shapelessChemistryRecipes[] = ShapelessRecipe::decode($in);
		}
		$this->shapedChemistryRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->shapedChemistryRecipes[] = ShapedRecipe::decode($in);
		}
		$this->smithingTransformRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->smithingTransformRecipes[] = SmithingTransformRecipe::decode($in);
		}
		$this->smithingTrimRecipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->smithingTrimRecipes[] = SmithingTrimRecipe::decode($in);
		}
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$inputId = VarInt::readSignedInt($in);
			$inputMeta = VarInt::readSignedInt($in);
			$ingredientId = VarInt::readSignedInt($in);
			$ingredientMeta = VarInt::readSignedInt($in);
			$outputId = VarInt::readSignedInt($in);
			$outputMeta = VarInt::readSignedInt($in);
			$this->potionTypeRecipes[] = new PotionTypeRecipe($inputId, $inputMeta, $ingredientId, $ingredientMeta, $outputId, $outputMeta);
		}
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$input = VarInt::readSignedInt($in);
			$ingredient = VarInt::readSignedInt($in);
			$output = VarInt::readSignedInt($in);
			$this->potionContainerRecipes[] = new PotionContainerChangeRecipe($input, $ingredient, $output);
		}
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$inputIdAndData = VarInt::readSignedInt($in);
			[$inputId, $inputMeta] = [$inputIdAndData >> 16, $inputIdAndData & 0x7fff];
			$outputs = [];
			for($j = 0, $outputCount = VarInt::readUnsignedInt($in); $j < $outputCount; ++$j){
				$outputItemId = VarInt::readSignedInt($in);
				$outputItemCount = VarInt::readSignedInt($in);
				$outputs[] = new MaterialReducerRecipeOutput($outputItemId, $outputItemCount);
			}
			$this->materialReducerRecipes[] = new MaterialReducerRecipe($inputId, $inputMeta, $outputs);
		}
		$this->cleanRecipes = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->shapedRecipes));
		foreach($this->shapedRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->shapelessRecipes));
		foreach($this->shapelessRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->multiRecipes));
		foreach($this->multiRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->userDataShapelessRecipes));
		foreach($this->userDataShapelessRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->shapelessChemistryRecipes));
		foreach($this->shapelessChemistryRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->shapedChemistryRecipes));
		foreach($this->shapedChemistryRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->smithingTransformRecipes));
		foreach($this->smithingTransformRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->smithingTrimRecipes));
		foreach($this->smithingTrimRecipes as $recipe){
			$recipe->encode($out);
		}
		VarInt::writeUnsignedInt($out, count($this->potionTypeRecipes));
		foreach($this->potionTypeRecipes as $recipe){
			VarInt::writeSignedInt($out, $recipe->getInputItemId());
			VarInt::writeSignedInt($out, $recipe->getInputItemMeta());
			VarInt::writeSignedInt($out, $recipe->getIngredientItemId());
			VarInt::writeSignedInt($out, $recipe->getIngredientItemMeta());
			VarInt::writeSignedInt($out, $recipe->getOutputItemId());
			VarInt::writeSignedInt($out, $recipe->getOutputItemMeta());
		}
		VarInt::writeUnsignedInt($out, count($this->potionContainerRecipes));
		foreach($this->potionContainerRecipes as $recipe){
			VarInt::writeSignedInt($out, $recipe->getInputItemId());
			VarInt::writeSignedInt($out, $recipe->getIngredientItemId());
			VarInt::writeSignedInt($out, $recipe->getOutputItemId());
		}
		VarInt::writeUnsignedInt($out, count($this->materialReducerRecipes));
		foreach($this->materialReducerRecipes as $recipe){
			VarInt::writeSignedInt($out, ($recipe->getInputItemId() << 16) | $recipe->getInputItemMeta());
			VarInt::writeUnsignedInt($out, count($recipe->getOutputs()));
			foreach($recipe->getOutputs() as $output){
				VarInt::writeSignedInt($out, $output->getItemId());
				VarInt::writeSignedInt($out, $output->getCount());
			}
		}
		CommonTypes::putBool($out, $this->cleanRecipes);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCraftingData($this);
	}
}
