export const challengeDefinitions = [
    { id: 'rock-paper-scissors', shortName: 'PPT', name: 'Piedra, papel o tijera' },
    { id: 'space-defender', shortName: 'DEF', name: 'INFEST Space Defender' },
    { id: 'trivia', shortName: 'TRIVIA', name: 'Trivia Express' },
    { id: 'maze', shortName: 'LAB', name: 'Laberinto' },
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
        soundEnabled: true,
        activeScreen: 'roulette',
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
