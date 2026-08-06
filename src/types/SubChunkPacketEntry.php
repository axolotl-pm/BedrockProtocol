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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * Spec name: SubChunkPacketData
 */
final class SubChunkPacketEntry{

	public function __construct(
		private SubChunkPositionOffset $offset,
		private int $requestResult,
		private ?string $terrainData,
		private int $heightMapType,
		private ?SubChunkPacketHeightMapInfo $heightMapData,
		private int $renderHeightMapType,
		private ?SubChunkPacketHeightMapInfo $renderHeightMapData,
		private ?int $usedBlobHash
	){
		if($heightMapType === SubChunkPacketHeightMapType::DATA && $heightMapData === null){
			throw new \InvalidArgumentException("Heightmap data type is DATA but no heightmap data was provided");
		}
		if($renderHeightMapType === SubChunkPacketHeightMapType::DATA && $renderHeightMapData === null){
			throw new \InvalidArgumentException("Render heightmap data type is DATA but no render heightmap data was provided");
		}
	}

	public function getOffset() : SubChunkPositionOffset{ return $this->offset; }

	public function getRequestResult() : int{ return $this->requestResult; }

	public function getTerrainData() : ?string{ return $this->terrainData; }

	public function getHeightMapType() : int{ return $this->heightMapType; }

	public function getHeightMapData() : ?SubChunkPacketHeightMapInfo{ return $this->heightMapData; }

	public function getRenderHeightMapType() : int{ return $this->renderHeightMapType; }

	public function getRenderHeightMapData() : ?SubChunkPacketHeightMapInfo{ return $this->renderHeightMapData; }

	public function getUsedBlobHash() : ?int{ return $this->usedBlobHash; }

	public static function read(ByteBufferReader $in) : self{
		$offset = SubChunkPositionOffset::read($in);

		$requestResult = Byte::readUnsigned($in);

		$data = CommonTypes::readOptional($in, CommonTypes::getString(...));

		$heightMapType = Byte::readUnsigned($in);
		$heightMapData = CommonTypes::readOptional($in, SubChunkPacketHeightMapInfo::read(...));
		if($heightMapType === SubChunkPacketHeightMapType::DATA && $heightMapData === null){
			throw new PacketDecodeException("Heightmap data type is DATA but no heightmap data was provided");
		}

		$renderHeightMapType = Byte::readUnsigned($in);
		$renderHeightMapData = CommonTypes::readOptional($in, SubChunkPacketHeightMapInfo::read(...));
		if($renderHeightMapType === SubChunkPacketHeightMapType::DATA && $renderHeightMapData === null){
			//TODO: probably COPIED should bail if the first heightmap isn't provided somehow?
			throw new PacketDecodeException("Render heightmap data type is DATA but no render heightmap data was provided");
		}

		$blobHash = CommonTypes::readOptional($in, LE::readUnsignedLong(...));

		return new self(
			$offset,
			$requestResult,
			$data,
			$heightMapType,
			$heightMapData,
			$renderHeightMapType,
			$renderHeightMapData,
			$blobHash
		);
	}

	public function write(ByteBufferWriter $out) : void{
		$this->offset->write($out);

		Byte::writeUnsigned($out, $this->requestResult);

		CommonTypes::writeOptional($out, $this->terrainData, CommonTypes::putString(...));

		Byte::writeUnsigned($out, $this->heightMapType);
		CommonTypes::writeOptional($out, $this->heightMapData, static fn($out, $v) => $v->write($out));

		Byte::writeUnsigned($out, $this->renderHeightMapType);
		CommonTypes::writeOptional($out, $this->renderHeightMapData, static fn($out, $v) => $v->write($out));

		CommonTypes::writeOptional($out, $this->usedBlobHash, LE::writeUnsignedLong(...));
	}
}
