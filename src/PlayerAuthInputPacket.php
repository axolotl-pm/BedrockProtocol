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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\BitSet;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\InteractionMode;
use pocketmine\network\mcpe\protocol\types\inventory\stackrequest\ItemStackRequest;
use pocketmine\network\mcpe\protocol\types\ItemInteractionData;
use pocketmine\network\mcpe\protocol\types\PlayerAuthInputFlags;
use pocketmine\network\mcpe\protocol\types\PlayerBlockAction;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionWithBlockInfo;
use pocketmine\network\mcpe\protocol\types\PlayMode;
use function count;

class PlayerAuthInputPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_AUTH_INPUT_PACKET;

	private Vector3 $position;
	private float $pitch;
	private float $yaw;
	private float $headYaw;
	private float $moveVecX;
	private float $moveVecZ;
	private BitSet $inputFlags;
	private int $inputMode;
	private int $playMode;
	private int $interactionMode;
	private Vector2 $interactRotation;
	private int $tick;
	private Vector3 $delta;
	private ?ItemInteractionData $itemInteractionData = null;
	private ?ItemStackRequest $itemStackRequest = null;
	/** @var PlayerBlockAction[]|null */
	private ?array $blockActions = null;
	private ?Vector2 $vehicleRotation = null;
	private ?int $predictedVehicleActorUniqueId = null;
	private float $analogMoveVecX;
	private float $analogMoveVecZ;
	private Vector3 $cameraOrientation;
	private Vector2 $rawMove;

	/**
	 * @generate-create-func
	 * @param PlayerBlockAction[] $blockActions
	 */
	private static function internalCreate(
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		float $moveVecX,
		float $moveVecZ,
		BitSet $inputFlags,
		int $inputMode,
		int $playMode,
		int $interactionMode,
		Vector2 $interactRotation,
		int $tick,
		Vector3 $delta,
		?ItemInteractionData $itemInteractionData,
		?ItemStackRequest $itemStackRequest,
		?array $blockActions,
		?Vector2 $vehicleRotation,
		?int $predictedVehicleActorUniqueId,
		float $analogMoveVecX,
		float $analogMoveVecZ,
		Vector3 $cameraOrientation,
		Vector2 $rawMove,
	) : self{
		$result = new self;
		$result->position = $position;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->moveVecX = $moveVecX;
		$result->moveVecZ = $moveVecZ;
		$result->inputFlags = $inputFlags;
		$result->inputMode = $inputMode;
		$result->playMode = $playMode;
		$result->interactionMode = $interactionMode;
		$result->interactRotation = $interactRotation;
		$result->tick = $tick;
		$result->delta = $delta;
		$result->itemInteractionData = $itemInteractionData;
		$result->itemStackRequest = $itemStackRequest;
		$result->blockActions = $blockActions;
		$result->vehicleRotation = $vehicleRotation;
		$result->predictedVehicleActorUniqueId = $predictedVehicleActorUniqueId;
		$result->analogMoveVecX = $analogMoveVecX;
		$result->analogMoveVecZ = $analogMoveVecZ;
		$result->cameraOrientation = $cameraOrientation;
		$result->rawMove = $rawMove;
		return $result;
	}

	/**
	 * @param BitSet                   $inputFlags @see PlayerAuthInputFlags
	 * @param int                      $inputMode @see InputMode
	 * @param int                      $playMode @see PlayMode
	 * @param int                      $interactionMode @see InteractionMode
	 * @param PlayerBlockAction[]|null $blockActions Blocks that the client has interacted with
	 */
	public static function create(
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		float $moveVecX,
		float $moveVecZ,
		BitSet $inputFlags,
		int $inputMode,
		int $playMode,
		int $interactionMode,
		Vector2 $interactRotation,
		int $tick,
		Vector3 $delta,
		?ItemInteractionData $itemInteractionData,
		?ItemStackRequest $itemStackRequest,
		?array $blockActions,
		?Vector2 $vehicleRotation,
		?int $predictedVehicleActorUniqueId,
		float $analogMoveVecX,
		float $analogMoveVecZ,
		Vector3 $cameraOrientation,
		Vector2 $rawMove
	) : self{
		if($inputFlags->getLength() !== PlayerAuthInputFlags::NUMBER_OF_FLAGS){
			throw new \InvalidArgumentException("Input flags must be " . PlayerAuthInputFlags::NUMBER_OF_FLAGS . " bits long");
		}

		$inputFlags->set(PlayerAuthInputFlags::PERFORM_ITEM_STACK_REQUEST, $itemStackRequest !== null);
		$inputFlags->set(PlayerAuthInputFlags::PERFORM_ITEM_INTERACTION, $itemInteractionData !== null);
		$inputFlags->set(PlayerAuthInputFlags::PERFORM_BLOCK_ACTIONS, $blockActions !== null);
		$inputFlags->set(PlayerAuthInputFlags::IN_CLIENT_PREDICTED_VEHICLE, $predictedVehicleActorUniqueId !== null);

		return self::internalCreate(
			$position,
			$pitch,
			$yaw,
			$headYaw,
			$moveVecX,
			$moveVecZ,
			$inputFlags,
			$inputMode,
			$playMode,
			$interactionMode,
			$interactRotation,
			$tick,
			$delta,
			$itemInteractionData,
			$itemStackRequest,
			$blockActions,
			$vehicleRotation,
			$predictedVehicleActorUniqueId,
			$analogMoveVecX,
			$analogMoveVecZ,
			$cameraOrientation,
			$rawMove
		);
	}

	public function getPosition() : Vector3{
		return $this->position;
	}

	public function getPitch() : float{
		return $this->pitch;
	}

	public function getYaw() : float{
		return $this->yaw;
	}

	public function getHeadYaw() : float{
		return $this->headYaw;
	}

	public function getMoveVecX() : float{
		return $this->moveVecX;
	}

	public function getMoveVecZ() : float{
		return $this->moveVecZ;
	}

	/**
	 * @see PlayerAuthInputFlags
	 */
	public function getInputFlags() : BitSet{
		return $this->inputFlags;
	}

	/**
	 * @see InputMode
	 */
	public function getInputMode() : int{
		return $this->inputMode;
	}

	/**
	 * @see PlayMode
	 */
	public function getPlayMode() : int{
		return $this->playMode;
	}

	/**
	 * @see InteractionMode
	 */
	public function getInteractionMode() : int{
		return $this->interactionMode;
	}

	public function getInteractRotation() : Vector2{ return $this->interactRotation; }

	public function getTick() : int{
		return $this->tick;
	}

	public function getDelta() : Vector3{
		return $this->delta;
	}

	public function getItemInteractionData() : ?ItemInteractionData{
		return $this->itemInteractionData;
	}

	public function getItemStackRequest() : ?ItemStackRequest{
		return $this->itemStackRequest;
	}

	/**
	 * @return PlayerBlockAction[]|null
	 */
	public function getBlockActions() : ?array{
		return $this->blockActions;
	}

	public function getVehicleRotation() : ?Vector2{ return $this->vehicleRotation; }

	public function getPredictedVehicleActorUniqueId() : ?int{ return $this->predictedVehicleActorUniqueId; }

	public function getAnalogMoveVecX() : float{ return $this->analogMoveVecX; }

	public function getAnalogMoveVecZ() : float{ return $this->analogMoveVecZ; }

	public function getCameraOrientation() : Vector3{ return $this->cameraOrientation; }

	public function getRawMove() : Vector2{ return $this->rawMove; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->pitch = LE::readFloat($in);
		$this->yaw = LE::readFloat($in);
		$this->position = CommonTypes::getVector3($in);
		$this->moveVecX = LE::readFloat($in);
		$this->moveVecZ = LE::readFloat($in);
		$this->headYaw = LE::readFloat($in);

		$this->inputFlags = new BitSet(PlayerAuthInputFlags::NUMBER_OF_FLAGS);
		CommonTypes::readOptional($in, function(ByteBufferReader $in) : void{
			$count = VarInt::readUnsignedInt($in);
			for($i = 0; $i < $count; ++$i){
				$flag = VarInt::readSignedInt($in);
				if($flag < 0 || $flag >= PlayerAuthInputFlags::NUMBER_OF_FLAGS){
					throw new PacketDecodeException("Unknown input flag $flag");
				}
				$this->inputFlags->set($flag, true);
			}
		});

		$this->inputMode = VarInt::readUnsignedInt($in);
		$this->playMode = VarInt::readUnsignedInt($in);
		$this->interactionMode = VarInt::readSignedInt($in);
		$this->interactRotation = CommonTypes::getVector2($in);
		$this->tick = VarInt::readUnsignedLong($in);
		$this->delta = CommonTypes::getVector3($in);

		$hasInteractionData = CommonTypes::getBool($in);
		if($hasInteractionData){
			$this->itemInteractionData = CommonTypes::readOptional($in, ItemInteractionData::read(...));
		}

		$hasItemStackRequest = CommonTypes::getBool($in);
		if($hasItemStackRequest){
			$this->itemStackRequest = CommonTypes::readOptional($in, ItemStackRequest::read(...));
		}

		$hasBlockActions = CommonTypes::getBool($in);
		if($hasBlockActions){
			$this->blockActions = CommonTypes::readOptional($in, static function(ByteBufferReader $in) : array{
				$blockActions = [];
				$max = VarInt::readUnsignedInt($in);
				for($i = 0; $i < $max; ++$i){
					$actionType = VarInt::readSignedInt($in);
					$blockActions[] = match(true){
						PlayerBlockActionWithBlockInfo::isValidActionType($actionType) => PlayerBlockActionWithBlockInfo::read($in, $actionType),
						default => throw new PacketDecodeException("Unexpected block action type $actionType")
					};
				}
				return $blockActions;
			});
		}

		$hasVehicleRotation = CommonTypes::getBool($in);
		if($hasVehicleRotation){
			$this->vehicleRotation = CommonTypes::readOptional($in, CommonTypes::getVector2(...));
		}
		$hasPredictedVehicleActorUniqueId = CommonTypes::getBool($in);
		if($hasPredictedVehicleActorUniqueId){
			$this->predictedVehicleActorUniqueId = CommonTypes::readOptional($in, CommonTypes::getActorUniqueId(...));
		}

		$this->analogMoveVecX = LE::readFloat($in);
		$this->analogMoveVecZ = LE::readFloat($in);
		$this->cameraOrientation = CommonTypes::getVector3($in);
		$this->rawMove = CommonTypes::getVector2($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);
		CommonTypes::putVector3($out, $this->position);
		LE::writeFloat($out, $this->moveVecX);
		LE::writeFloat($out, $this->moveVecZ);
		LE::writeFloat($out, $this->headYaw);

		CommonTypes::putBool($out, true);
		$inputFlags = [];
		for($i = 0; $i < PlayerAuthInputFlags::NUMBER_OF_FLAGS; ++$i){
			if($this->inputFlags->get($i)){
				$inputFlags[] = $i;
			}
		}
		VarInt::writeUnsignedInt($out, count($inputFlags));
		foreach($inputFlags as $flag){
			VarInt::writeSignedInt($out, $flag);
		}

		VarInt::writeUnsignedInt($out, $this->inputMode);
		VarInt::writeUnsignedInt($out, $this->playMode);
		VarInt::writeSignedInt($out, $this->interactionMode);
		CommonTypes::putVector2($out, $this->interactRotation);
		VarInt::writeUnsignedLong($out, $this->tick);
		CommonTypes::putVector3($out, $this->delta);

		CommonTypes::putBool($out, true);
		CommonTypes::writeOptional($out, $this->itemInteractionData, static function(ByteBufferWriter $out, ItemInteractionData $data) : void{
			$data->write($out);
		});

		CommonTypes::putBool($out, true);
		CommonTypes::writeOptional($out, $this->itemStackRequest, static function(ByteBufferWriter $out, ItemStackRequest $request) : void{
			$request->write($out);
		});

		CommonTypes::putBool($out, true);
		CommonTypes::writeOptional($out, $this->blockActions, static function(ByteBufferWriter $out, array $blockActions) : void{
			VarInt::writeUnsignedInt($out, count($blockActions));
			foreach($blockActions as $blockAction){
				VarInt::writeSignedInt($out, $blockAction->getActionType());
				$blockAction->write($out);
			}
		});

		CommonTypes::putBool($out, true);
		CommonTypes::writeOptional($out, $this->vehicleRotation, CommonTypes::putVector2(...));
		CommonTypes::putBool($out, true);
		CommonTypes::writeOptional($out, $this->predictedVehicleActorUniqueId, CommonTypes::putActorUniqueId(...));

		LE::writeFloat($out, $this->analogMoveVecX);
		LE::writeFloat($out, $this->analogMoveVecZ);
		CommonTypes::putVector3($out, $this->cameraOrientation);
		CommonTypes::putVector2($out, $this->rawMove);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerAuthInput($this);
	}
}
