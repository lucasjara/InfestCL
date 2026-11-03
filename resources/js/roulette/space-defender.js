export function createSpaceDefenderGame(width = 760, height = 440) {
    return {
        width,
        height,
        score: 0,
        stage: 1,
        ship: { x: width / 2, y: height / 2, radius: 14, speed: 220 },
        meteors: [],
        spheres: [],
        particles: [],
        keys: {},
        finished: false,
        lastMeteorAt: 0,
        lastSphereAt: 0,
        elapsed: 0,
    };
}

export function getSpaceDifficulty(stage) {
    if (stage === 1) {
        return { meteorInterval: 950, meteorSpeed: 95, meteorCount: 1 };
    }
    if (stage === 2) {
        return { meteorInterval: 650, meteorSpeed: 135, meteorCount: 1 };
    }
    return { meteorInterval: 420, meteorSpeed: 175, meteorCount: 2 };
}

export function updateSpaceStage(game) {
    if (game.score >= 14) {
        game.stage = 3;
    } else if (game.score >= 7) {
        game.stage = 2;
    } else {
        game.stage = 1;
    }
}

export function addSurvivalScore(game, deltaSeconds) {
    game.score = Math.min(20, game.score + Math.max(0, deltaSeconds) * 0.5);
    updateSpaceStage(game);
    return game.score;
}

export function circleCollision(first, second) {
    const dx = first.x - second.x;
    const dy = first.y - second.y;
    const radius = first.radius + second.radius;
    return dx * dx + dy * dy <= radius * radius;
}
