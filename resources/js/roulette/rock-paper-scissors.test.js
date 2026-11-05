import assert from 'node:assert/strict';
import test from 'node:test';

import {
    createRockPaperScissorsGame,
    getComputerChoice,
    playRockPaperScissorsRound,
    resolveRockPaperScissors,
} from './rock-paper-scissors.js';

test('rock beats scissors, paper beats rock and scissors beats paper', () => {
    assert.equal(resolveRockPaperScissors('rock', 'scissors'), 'victory');
    assert.equal(resolveRockPaperScissors('paper', 'rock'), 'victory');
    assert.equal(resolveRockPaperScissors('scissors', 'paper'), 'victory');
});

test('equal choices are ties', () => {
    assert.equal(resolveRockPaperScissors('paper', 'paper'), 'tie');
});

test('computer choice stays within the supported choices', () => {
    assert.equal(getComputerChoice(() => 0), 'rock');
    assert.equal(getComputerChoice(() => 0.99), 'scissors');
});

test('two player victories finish the game as victory', () => {
    const game = createRockPaperScissorsGame();
    playRockPaperScissorsRound(game, 'rock', 'scissors');
    game.locked = false;
    const result = playRockPaperScissorsRound(game, 'paper', 'rock');

    assert.equal(result.outcome, 'victory');
    assert.equal(game.playerWins, 2);
});

test('two computer victories finish the game as defeat', () => {
    const game = createRockPaperScissorsGame();
    playRockPaperScissorsRound(game, 'rock', 'paper');
    game.locked = false;
    const result = playRockPaperScissorsRound(game, 'scissors', 'rock');

    assert.equal(result.outcome, 'defeat');
    assert.equal(game.computerWins, 2);
});

test('ties do not consume decisive rounds and can be repeated', () => {
    const game = createRockPaperScissorsGame();
    const result = playRockPaperScissorsRound(game, 'rock', 'rock');

    assert.equal(result.roundResult, 'tie');
    assert.equal(game.decisiveRounds, 0);
});
