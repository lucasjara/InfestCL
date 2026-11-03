import assert from 'node:assert/strict';
import test from 'node:test';

import {
    createRouletteState,
    getGlobalElapsedSeconds,
    recordChallengeOutcome,
    registerChallengeAttempt,
    resetRouletteState,
    selectPendingChallenge,
    startGlobalTimer,
    stopGlobalTimer,
} from './state.js';

test('selection prioritizes pending challenges that have never been attempted', () => {
    const state = createRouletteState();
    state.challenges[0].attempts = 2;
    const selected = selectPendingChallenge(state.challenges);

    assert.equal(selected.attempts, 0);
    assert.notEqual(selected.id, state.challenges[0].id);
});

test('selection returns null when all challenges are completed', () => {
    const state = createRouletteState();
    state.challenges.forEach((challenge) => {
        challenge.completed = true;
    });

    assert.equal(selectPendingChallenge(state.challenges), null);
});

test('global timer accumulates only active elapsed game time', () => {
    const state = createRouletteState();
    startGlobalTimer(state, 1000);

    assert.equal(stopGlobalTimer(state, 1250), 0.25);
    assert.equal(getGlobalElapsedSeconds(state, 2000), 0.25);
});

test('victory records an attempt and completion once', () => {
    const state = createRouletteState();
    registerChallengeAttempt(state, 'maze');
    const record = recordChallengeOutcome(
        state,
        'maze',
        'victory',
        { attemptNumber: 1, score: 20, timeUsedSeconds: 12 },
        1000,
    );

    assert.equal(record.result, 'victory');
    assert.equal(state.challenges.find((challenge) => challenge.id === 'maze').completed, true);
    assert.equal(state.history.length, 1);
    assert.equal(state.history[0].attemptNumber, 1);
});

test('reload-style reset creates a fresh in-memory state', () => {
    const state = createRouletteState();
    registerChallengeAttempt(state, 'trivia');

    const resetState = resetRouletteState();

    assert.equal(resetState.history.length, 0);
    assert.equal(resetState.globalElapsedSeconds, 0);
    assert.equal(resetState.challenges.every((challenge) => challenge.attempts === 0), true);
});
