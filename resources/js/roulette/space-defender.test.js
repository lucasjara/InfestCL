import assert from 'node:assert/strict';
import test from 'node:test';

import {
    addSurvivalScore,
    circleCollision,
    createSpaceDefenderGame,
    getShipRotation,
    getSpaceDifficulty,
    updateSpaceStage,
} from './space-defender.js';

test('space defender starts with score zero in stage one', () => {
    const game = createSpaceDefenderGame();

    assert.equal(game.score, 0);
    assert.equal(game.stage, 1);
    assert.equal(game.finished, false);
});

test('survival adds half a point per second', () => {
    const game = createSpaceDefenderGame();

    addSurvivalScore(game, 2);

    assert.equal(game.score, 1);
});

test('space defender advances through three difficulty stages by score', () => {
    const game = createSpaceDefenderGame();

    game.score = 7;
    updateSpaceStage(game);
    assert.equal(game.stage, 2);

    game.score = 14;
    updateSpaceStage(game);
    assert.equal(game.stage, 3);
    assert.equal(getSpaceDifficulty(game.stage).meteorInterval < getSpaceDifficulty(1).meteorInterval, true);
});

test('circle collision detects overlapping ship and collectible', () => {
    assert.equal(circleCollision(
        { x: 10, y: 10, radius: 10 },
        { x: 15, y: 15, radius: 10 },
    ), true);
    assert.equal(circleCollision(
        { x: 10, y: 10, radius: 5 },
        { x: 100, y: 100, radius: 5 },
    ), false);
});

test('ship rotation points toward each movement direction', () => {
    assert.equal(getShipRotation(1, 0), 0);
    assert.equal(getShipRotation(-1, 0), Math.PI);
    assert.equal(getShipRotation(0, 1), Math.PI / 2);
    assert.equal(getShipRotation(0, -1), -Math.PI / 2);
    assert.equal(getShipRotation(0, 0), null);
});
