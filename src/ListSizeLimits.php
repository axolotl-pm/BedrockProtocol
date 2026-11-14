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

final class ListSizeLimits{

	private function __construct(){
		//NOOP
	}

	public const ATTRIBUTE_LAYER_ATTRIBUTES = 1024;
	public const ATTRIBUTE_REMOVE_ENVIRONMENT_ATTRIBUTES = 1024;
	public const ATTRIBUTE_UPDATE_ENVIRONMENT_ATTRIBUTES = 1024;
	public const ATTRIBUTE_UPDATE_LAYERS = 512;
	public const AVAILABLE_COMMANDS_CHAINED_SUBCOMMAND_DATA = 16;
	public const CLIENT_CACHE_HIT_HASHES = 4095;
	public const CLIENT_CACHE_MISS_HASHES = 4095;
	public const CLIENT_CACHE_MISS_RESPONSE_BLOBS = 4095;
	public const CLIENTBOUND_MAP_DECORATIONS = 65535;
	public const CLIENTBOUND_MAP_PARENT_MAP_IDS = 65535;
	public const CLIENTBOUND_MAP_TRACKED_ENTITIES = 65535;
	public const COMMAND_CHAINED_SUBCOMMAND_INDEXES = 250;
	public const COMMAND_CHAINED_SUBCOMMAND_VALUES = 32;
	public const COMMAND_ENUM_CONSTRAINTS = 250;
	public const COMMAND_OVERLOADS = 250;
	public const CONTAINER_REGISTRY_CLEANUP_CONTAINERS = 1000;
	public const DATA_STORE_UPDATES = 500;
	public const DIAGNOSTICS_MEMORY_CATEGORY_VALUES = 111;
	public const DIAGNOSTICS_WHISKER_SCOPES = 33;
	public const EMOTE_LIST_EMOTE_IDS = 6;
	public const ITEM_STACK_REQUEST_ACTIONS = 100;
	public const ITEM_STACK_REQUEST_PACKET_REQUESTS = 100;
	public const LEVEL_CHUNK_BLOB_HASHES = 65;
	public const LOCATOR_BAR_WAYPOINTS = 40000;
	public const MAP_INFO_REQUEST_CLIENT_PIXELS = 16384;
	public const NETWORK_CHUNK_PUBLISHER_SAVED_CHUNKS = 9216;
	public const NOISE_GRADIENT_AMPLITUDES = 100;
	public const PLAYER_ARMOR_DAMAGE_PAIRS = 5;
	public const PLAYER_AUTH_INPUT_BLOCK_ACTIONS = 100;
	public const PLAYER_ENCHANT_OPTIONS = 3;
	public const PLAYER_FOG_LAYERS = 16;
	public const PLAYER_LIST_ENTRIES = 1000;
	public const PRIMITIVE_SHAPES = 1048576;
	public const PURCHASE_RECEIPT_ENTRIES = 10000;
	public const RECIPE_UNLOCKING_INGREDIENTS = 128;
	public const REGISTER_AUDIO_CONTENT_REGISTRATIONS = 64;
	public const RESOURCE_PACK_STACK_ENTRIES = 65535;
	public const RESOURCE_PACKS_INFO_ENTRIES = 65535;
	public const SHAPELESS_RECIPE_INGREDIENTS = 128;
	public const SUB_CHUNK_ENTRIES = 8192;
	public const SUB_CHUNK_REQUEST_ENTRIES = 8192;
	public const TEXT_PARAMETERS = 4;
	public const VOXEL_CELLS_STORAGE = 256048;
	public const VOXEL_SHAPE_X_COORDINATES = 128;
	public const VOXEL_SHAPE_Y_COORDINATES = 128;
	public const VOXEL_SHAPE_Z_COORDINATES = 128;
	public const WORLD_CLOCK_ADD_TIME_MARKERS = 256;
	public const WORLD_CLOCK_INITIALIZE_REGISTRY_CLOCKS = 256;
	public const WORLD_CLOCK_REMOVE_TIME_MARKER_IDS = 256;
	public const WORLD_CLOCK_SYNC_STATE_CLOCKS = 256;
	public const WORLD_CLOCK_TIME_MARKERS = 256;
}
