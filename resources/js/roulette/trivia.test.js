import assert from 'node:assert/strict';
import test from 'node:test';

import {
    createTriviaGame,
    fetchTriviaQuestions,
    getProgressiveFallbackQuestions,
    resolveTriviaAnswer,
    selectFiveProgressiveQuestions,
} from './trivia.js';

test('trivia fallback provides five progressive questions', () => {
    const questions = getProgressiveFallbackQuestions();

    assert.equal(questions.length, 5);
    assert.equal(questions[0].difficulty, 'easy');
    assert.equal(questions[4].difficulty, 'hard');
});

test('trivia game wins with three correct answers', () => {
    const game = createTriviaGame();
    game.questions = getProgressiveFallbackQuestions();
    game.answered = false;

    resolveTriviaAnswer(game, game.questions[0].correctAnswer);
    game.answered = false;
    game.currentIndex = 1;
    resolveTriviaAnswer(game, game.questions[1].correctAnswer);
    game.answered = false;
    game.currentIndex = 2;
    resolveTriviaAnswer(game, game.questions[2].correctAnswer);

    assert.equal(game.correctAnswers, 3);
});

test('trivia selection always returns five questions', () => {
    const questions = getProgressiveFallbackQuestions();
    const selected = selectFiveProgressiveQuestions(questions);

    assert.equal(selected.length, 5);
});

test('trivia API failure uses the local fallback', async () => {
    const originalFetch = globalThis.fetch;
    globalThis.fetch = async () => {
        throw new Error('network unavailable');
    };

    try {
        const questions = await fetchTriviaQuestions();
        assert.equal(questions.length, 5);
        assert.equal(questions.every((question) => question.correctAnswer), true);
    } finally {
        globalThis.fetch = originalFetch;
    }
});

test('trivia API uses the configured site URL', async () => {
    const originalFetch = globalThis.fetch;
    let requestedUrl = null;
    globalThis.fetch = async (url) => {
        requestedUrl = url;
        throw new Error('network unavailable');
    };

    try {
        await fetchTriviaQuestions('https://example.test/infest/api/questions');
        assert.equal(requestedUrl, 'https://example.test/infest/api/questions');
    } finally {
        globalThis.fetch = originalFetch;
    }
});
