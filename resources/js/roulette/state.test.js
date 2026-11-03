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

test('selection includes attempted challenges while they remain incomplete', () => {
    const state = createRouletteState();
    state.challenges[0].attempts = 2;
    state.challenges[1].completed = true;

    const selected = selectPendingChallenge(state.challenges);

    assert.equal(selected.completed, false);
    assert.notEqual(selected.id, state.challenges[1].id);
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

test('defeat and abandonment do not complete challenges', () => {
    const state = createRouletteState();

    registerChallengeAttempt(state, 'maze');
    recordChallengeOutcome(state, 'maze', 'defeat', { attemptNumber: 1 });
    assert.equal(state.challenges.find((challenge) => challenge.id === 'maze').completed, false);

    recordChallengeOutcome(state, 'maze', 'abandonment', { attemptNumber: 2 });
    assert.equal(state.challenges.find((challenge) => challenge.id === 'maze').completed, false);

    state.challenges
        .filter((challenge) => challenge.id !== 'maze')
        .forEach((challenge) => {
            challenge.completed = true;
        });
    assert.equal(selectPendingChallenge(state.challenges).id, 'maze');
});

test('reload-style reset creates a fresh in-memory state', () => {
    const state = createRouletteState();
    registerChallengeAttempt(state, 'trivia');

    const resetState = resetRouletteState();

    assert.equal(resetState.history.length, 0);
    assert.equal(resetState.globalElapsedSeconds, 0);
    assert.equal(resetState.challenges.every((challenge) => challenge.attempts === 0), true);
});
