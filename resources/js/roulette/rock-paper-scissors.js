export const rpsChoices = ['rock', 'paper', 'scissors'];

export function createRockPaperScissorsGame() {
    return {
        playerWins: 0,
        computerWins: 0,
        decisiveRounds: 0,
        locked: false,
        finished: false,
        lastResult: null,
    };
}

export function getComputerChoice(random = Math.random) {
    return rpsChoices[Math.min(rpsChoices.length - 1, Math.floor(random() * rpsChoices.length))];
}

export function resolveRockPaperScissors(playerChoice, computerChoice) {
    if (playerChoice === computerChoice) {
        return 'tie';
    }

    const winsAgainst = {
        rock: 'scissors',
        paper: 'rock',
        scissors: 'paper',
    };

    return winsAgainst[playerChoice] === computerChoice ? 'victory' : 'defeat';
}

export function playRockPaperScissorsRound(game, playerChoice, computerChoice = getComputerChoice()) {
    const roundResult = resolveRockPaperScissors(playerChoice, computerChoice);

    if (game.finished || game.locked) {
        return { roundResult: null, playerChoice, computerChoice, ...game };
    }

    game.locked = true;

    if (roundResult === 'tie') {
        game.lastResult = 'tie';
        return { roundResult, playerChoice, computerChoice, ...game };
    }

    game.decisiveRounds += 1;
    if (roundResult === 'victory') {
        game.playerWins += 1;
    } else {
        game.computerWins += 1;
    }

    game.lastResult = roundResult;

    if (game.playerWins >= 2) {
        game.finished = true;
        game.outcome = 'victory';
    } else if (game.computerWins >= 2 || game.decisiveRounds >= 3) {
        game.finished = true;
        game.outcome = 'defeat';
    }

    return { roundResult, playerChoice, computerChoice, ...game };
}
