import assert from 'node:assert/strict';
import test from 'node:test';

import {
    formatTime,
    getChallengeInstructions,
    getResultCopy,
} from './ui.js';

test('formatTime renders monotonic seconds as a padded timer', () => {
    assert.equal(formatTime(0), '00:00');
    assert.equal(formatTime(65.9), '01:05');
});

test('challenge instructions include the correct individual time limit', () => {
    assert.equal(getChallengeInstructions('trivia').time, 45);
    assert.equal(getChallengeInstructions('maze').time, 30);
});

test('result copy distinguishes victory, defeat and abandonment', () => {
    assert.equal(getResultCopy('victory').title, '¡Victoria!');
    assert.equal(getResultCopy('defeat').title, 'Derrota');
    assert.equal(getResultCopy('abandonment').title, 'Desafío abandonado');
});
