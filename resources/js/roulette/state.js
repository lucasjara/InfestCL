export const challengeDefinitions = [
    {
        id: 'rock-paper-scissors',
        shortName: 'PPT',
        name: 'Piedra, papel o tijera',
        description: 'Consigue dos victorias antes de que se agoten las rondas o los 30 segundos.',
        image: '/images/challenges/rock-paper-scissors.svg',
    },
    {
        id: 'space-defender',
        shortName: 'DEF',
        name: 'INFEST Space Defender',
        description: 'Pilota la nave, esquiva meteoritos y reúne esferas hasta alcanzar 20 puntos.',
        image: '/images/challenges/space-defender.svg',
    },
    {
        id: 'trivia',
        shortName: 'TRIVIA',
        name: 'Trivia Express',
        description: 'Responde al menos tres de cinco preguntas gamer y tecnológicas en 45 segundos.',
        image: '/images/challenges/trivia.svg',
    },
    {
        id: 'maze',
        shortName: 'LAB',
        name: 'Laberinto',
        description: 'Recorre el laberinto generado y encuentra la salida antes de que termine el tiempo.',
        image: '/images/challenges/maze.svg',
    },
];

export function createRouletteState() {
    return {
        challenges: challengeDefinitions.map((challenge) => ({
            ...challenge,
            attempts: 0,
            completed: false,
        })),
        selectedId: null,
        globalElapsedSeconds: 0,
        globalTimerStartedAt: null,
        globalTimerRunning: false,
        history: [],
        soundEnabled: true,
        activeScreen: 'roulette',
        speedMasterUnlocked: false,
    };
}

export function selectPendingChallenge(challenges) {
    const pending = challenges.filter((challenge) => !challenge.completed);
    if (pending.length === 0) {
        return null;
    }

    const unattempted = pending.filter((challenge) => challenge.attempts === 0);
    const candidates = unattempted.length > 0 ? unattempted : pending;

    return candidates[Math.floor(Math.random() * candidates.length)];
}

export function startGlobalTimer(state, now = performance.now()) {
    if (!state.globalTimerRunning) {
        state.globalTimerRunning = true;
        state.globalTimerStartedAt = now;
    }
}

export function stopGlobalTimer(state, now = performance.now()) {
    if (!state.globalTimerRunning) {
        return state.globalElapsedSeconds;
    }

    state.globalElapsedSeconds += Math.max(
        0,
        (now - state.globalTimerStartedAt) / 1000,
    );
    state.globalTimerStartedAt = null;
    state.globalTimerRunning = false;

    return state.globalElapsedSeconds;
}

export function registerChallengeAttempt(state, challengeId) {
    const challenge = state.challenges.find((item) => item.id === challengeId);
    if (!challenge) {
        return null;
    }

    challenge.attempts += 1;
    return challenge.attempts;
}

export function getGlobalElapsedSeconds(state, now = performance.now()) {
    if (!state.globalTimerRunning) {
        return state.globalElapsedSeconds;
    }

    return state.globalElapsedSeconds + Math.max(
        0,
        (now - state.globalTimerStartedAt) / 1000,
    );
}

export function recordChallengeOutcome(state, challengeId, result, details = {}, now = Date.now()) {
    const challenge = state.challenges.find((item) => item.id === challengeId);
    if (!challenge) {
        return null;
    }

    if (!['victory', 'defeat', 'abandonment'].includes(result)) {
        throw new Error('El resultado del intento no es válido.');
    }

    const attemptNumber = details.attemptNumber ?? challenge.attempts + 1;
    challenge.attempts = Math.max(challenge.attempts, attemptNumber);

    if (result === 'victory') {
        challenge.completed = true;
    }

    const completedCount = state.challenges.filter((item) => item.completed).length;
    if (completedCount === 4 && !state.speedMasterUnlocked) {
        state.speedMasterUnlocked = getGlobalElapsedSeconds(state, now) <= 120;
    }

    const record = {
        challengeId,
        attemptNumber,
        result,
        timeUsedSeconds: details.timeUsedSeconds ?? 0,
        score: details.score ?? null,
        completedAt: new Date(now).toISOString(),
    };
    state.history.push(record);

    return record;
}

export function resetRouletteState() {
    return createRouletteState();
}
