import { albumStorageAdapter } from '../storageConfig.js';
import { CardService } from './cardService.js';
import { CollectionService } from './collectionService.js';
import { InventoryService } from './inventoryService.js';

export const collectionService = new CollectionService(albumStorageAdapter);
export const cardService = new CardService(albumStorageAdapter);
export const inventoryService = new InventoryService(albumStorageAdapter);
