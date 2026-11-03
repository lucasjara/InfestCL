import { setActiveScreen } from './navigation.js';
import {
    createRouletteState,
    getGlobalElapsedSeconds,
    recordChallengeOutcome,
    registerChallengeAttempt,
    selectPendingChallenge,
    startGlobalTimer,
    stopGlobalTimer,
} from './state.js';
import {
    formatTime,
    getChallengeInstructions,
    getResultCopy,
    playInterfaceSound,
} from './ui.js';
import {
    createRockPaperScissorsGame,
    getComputerChoice,
    playRockPaperScissorsRound,
} from './rock-paper-scissors.js';
import {
    addSurvivalScore,
    circleCollision,
    createSpaceDefenderGame,
    getSpaceDifficulty,
    updateSpaceStage,
} from './space-defender.js';

const state = createRouletteState();

const roulette = document.querySelector('[data-roulette]');
const spinButton = document.querySelector('[data-spin-roulette]');
const selectedPanel = document.querySelector('[data-selected-challenge]');
const selectedName = document.querySelector('[data-selected-name]');
const selectedDescription = document.querySelector('[data-selected-description]');
const selectedImage = document.querySelector('[data-selected-image]');
const startButton = document.querySelector('[data-start-challenge]');
const backButtons = document.querySelectorAll('[data-back-to-roulette]');
const soundButton = document.querySelector('[data-sound-toggle]');
const progress = document.querySelector('[data-roulette-progress]');
const completedCount = document.querySelector('[data-roulette-completed]');
const timer = document.querySelector('[data-global-time]');
const instructionName = document.querySelector('[data-instruction-name]');
const beginButton = document.querySelector('[data-begin-challenge]');
const countdown = document.querySelector('[data-countdown]');
const instructionsScreen = document.querySelector('[data-instructions-panel]');
const countdownPanel = document.querySelector('[data-countdown-panel]');
const instructionObjective = document.querySelector('[data-instruction-objective]');
const instructionControls = document.querySelector('[data-instruction-controls]');
const instructionTime = document.querySelector('[data-instruction-time]');
const individualTimeWrapper = document.querySelector('[data-individual-time-wrapper]');
const resultCard = document.querySelector('[data-result-card]');
const resultEyebrow = document.querySelector('[data-result-eyebrow]');
const resultTitle = document.querySelector('[data-result-title]');
const resultDescription = document.querySelector('[data-result-description]');
const retryButton = document.querySelector('[data-retry-challenge]');
const returnButtons = document.querySelectorAll('[data-return-to-roulette]');
const abandonDialog = document.querySelector('[data-abandon-dialog]');
const rpsScreen = document.querySelector('[data-rps-screen]');
const rpsChoiceButtons = document.querySelectorAll('[data-rps-choice]');
const rpsPlayerChoice = document.querySelector('[data-rps-player-choice]');
const rpsComputerChoice = document.querySelector('[data-rps-computer-choice]');
const rpsRoundResult = document.querySelector('[data-rps-round-result]');
const rpsPlayerWins = document.querySelector('[data-rps-player-wins]');
const rpsComputerWins = document.querySelector('[data-rps-computer-wins]');
const rpsRounds = document.querySelector('[data-rps-rounds]');
const spaceScreen = document.querySelector('[data-space-screen]');
const spaceCanvas = document.querySelector('[data-space-canvas]');
const spaceScore = document.querySelector('[data-space-score]');
const spaceStage = document.querySelector('[data-space-stage]');
const spaceControlButtons = document.querySelectorAll('[data-space-control]');
let countdownInterval = null;
let currentRouletteRotation = 0;
let individualTimerRemaining = null;
let activeGame = false;
let rpsGame = createRockPaperScissorsGame();
let spaceGame = createSpaceDefenderGame();
let spaceAnimationFrame = null;

const challengeColors = {
    'rock-paper-scissors': '#ef4444',
    'space-defender': '#5c36a5',
    trivia: '#22c55e',
    maze: '#eab308',
};

function startIndividualTimer(seconds, onExpire) {
    const startedAt = performance.now();
    const timerId = window.setInterval(() => {
        const elapsed = (performance.now() - startedAt) / 1000;
        const remaining = Math.max(0, seconds - elapsed);

        document.querySelectorAll('[data-individual-time]').forEach((element) => {
            element.textContent = `${Math.ceil(remaining)}s`;
        });

        if (remaining <= 0) {
            window.clearInterval(timerId);
            individualTimerRemaining = null;
            onExpire?.();
        }
    }, 100);

    individualTimerRemaining = timerId;
    return timerId;
}

function stopIndividualTimer() {
    if (individualTimerRemaining) {
        window.clearInterval(individualTimerRemaining);
        individualTimerRemaining = null;
    }
}

function updateGlobalTimerDisplay() {
    const elapsed = Math.floor(getGlobalElapsedSeconds(state));
    timer.textContent = formatTime(elapsed);
}

function renderProgress() {
    const completed = state.challenges.filter((challenge) => challenge.completed).length;
    if (progress) {
        progress.textContent = `${completed}/4`;
        progress.setAttribute('aria-label', `${completed} de 4 experiencias completadas`);
    }
    if (completedCount) {
        completedCount.textContent = `${completed}/4`;
        completedCount.setAttribute('aria-label', `${completed} de 4 desafíos completados`);
    }

    document.querySelectorAll('[data-challenge-status]').forEach((status) => {
        const challenge = state.challenges.find((item) => item.id === status.dataset.challengeStatus);
        if (!challenge) {
            return;
        }

        status.dataset.status = challenge.completed ? 'completed' : 'pending';
        const stateLabel = status.querySelector('[data-challenge-state-label]');
        if (stateLabel) {
            stateLabel.textContent = challenge.completed ? 'Completada' : 'Pendiente';
        }
    });
}

function renderSelection(challenge) {
    if (!challenge) {
        selectedName.textContent = 'Cuatro experiencias completadas';
        selectedDescription.textContent = 'Ya puedes jugar cualquier desafío nuevamente.';
        selectedImage.hidden = true;
        selectedPanel.hidden = false;
        spinButton.disabled = true;
        startButton.hidden = true;
        return;
    }

    state.selectedId = challenge.id;
    selectedName.textContent = challenge.name;
    selectedDescription.textContent = challenge.description;
    selectedImage.src = challenge.image;
    selectedImage.alt = `Imagen representativa de ${challenge.name}`;
    selectedImage.hidden = false;
    selectedPanel.hidden = false;
    selectedImage.style.setProperty(
        '--selected-challenge-color',
        challengeColors[challenge.id] ?? '#c4f05b',
    );
    instructionName.textContent = challenge.name;

    const instructions = getChallengeInstructions(challenge.id);
    instructionObjective.textContent = instructions.objective;
    instructionControls.textContent = instructions.controls;
    instructionTime.textContent = `Dispones de ${instructions.time} segundos después de la cuenta regresiva.`;
}

function spinRoulette() {
    const challenge = selectPendingChallenge(state.challenges);
    if (!challenge) {
        renderSelection(null);
        return;
    }

    spinButton.disabled = true;
    selectedPanel.hidden = true;
    roulette.classList.remove('is-selected');

    const challengeIndex = state.challenges.findIndex((item) => item.id === challenge.id);
    const rotations = 4 + Math.floor(Math.random() * 3);
    const segmentAngle = 360 / state.challenges.length;
    const currentAngle = ((currentRouletteRotation % 360) + 360) % 360;
    const targetAngle = (360 - (challengeIndex * segmentAngle)) % 360;
    const alignmentDelta = (targetAngle - currentAngle + 360) % 360;
    currentRouletteRotation += (rotations * 360) + alignmentDelta;

    roulette.style.transform = `rotate(${currentRouletteRotation}deg)`;
    roulette.style.setProperty('--roulette-counter-rotation', `${-currentRouletteRotation}deg`);

    window.setTimeout(() => {
        roulette.classList.remove('is-spinning');
        roulette.classList.add('is-selected');
        renderSelection(challenge);
        spinButton.disabled = false;
        playInterfaceSound(state.soundEnabled, 'selection');
    }, 1800);
}

function renderSoundButton() {
    if (!soundButton) {
        return;
    }

    soundButton.setAttribute('aria-pressed', String(state.soundEnabled));
    soundButton.textContent = state.soundEnabled ? 'Sonido activo' : 'Sonido silencioso';
}

function resetChallengeView() {
    if (countdownInterval) {
        window.clearInterval(countdownInterval);
        countdownInterval = null;
    }

    stopIndividualTimer();
    individualTimeWrapper.hidden = true;
    instructionsScreen.querySelectorAll('[data-instructions-content]').forEach((element) => {
        element.hidden = false;
    });
    countdown.hidden = true;
    countdownPanel.hidden = true;
    beginButton.hidden = false;
}

const rpsLabels = {
    rock: 'Piedra',
    paper: 'Papel',
    scissors: 'Tijera',
};

const rpsIcons = {
    rock: '✊',
    paper: '✋',
    scissors: '✌️',
};

function renderRockPaperScissors() {
    rpsPlayerWins.textContent = String(rpsGame.playerWins);
    rpsComputerWins.textContent = String(rpsGame.computerWins);
    rpsRounds.textContent = `${rpsGame.decisiveRounds}/3`;
}

function resetRockPaperScissors() {
    rpsGame = createRockPaperScissorsGame();
    rpsPlayerChoice.textContent = '—';
    rpsComputerChoice.textContent = '—';
    rpsRoundResult.textContent = 'Elige tu jugada';
    rpsChoiceButtons.forEach((button) => {
        button.disabled = false;
    });
    renderRockPaperScissors();
}

function resetSpaceDefender() {
    if (spaceAnimationFrame) {
        window.cancelAnimationFrame(spaceAnimationFrame);
        spaceAnimationFrame = null;
    }
    spaceGame = createSpaceDefenderGame(
        spaceCanvas?.width ?? 760,
        spaceCanvas?.height ?? 440,
    );
    if (spaceScore) {
        spaceScore.textContent = '0';
    }
    if (spaceStage) {
        spaceStage.textContent = '1';
    }
}

function resizeSpaceCanvas() {
    if (!spaceCanvas) {
        return;
    }

    const rect = spaceCanvas.getBoundingClientRect();
    if (!rect) {
        return;
    }
    spaceCanvas.width = Math.max(320, Math.round(rect.width));
    spaceCanvas.height = Math.max(280, Math.round(rect.height));
    spaceGame.width = spaceCanvas.width;
    spaceGame.height = spaceCanvas.height;
    spaceGame.ship.x = Math.min(Math.max(spaceGame.ship.radius, spaceGame.ship.x), spaceCanvas.width - spaceGame.ship.radius);
    spaceGame.ship.y = Math.min(Math.max(spaceGame.ship.radius, spaceGame.ship.y), spaceCanvas.height - spaceGame.ship.radius);
}

function spawnSpaceMeteor() {
    const edge = Math.floor(Math.random() * 4);
    const radius = 10 + Math.random() * 9;
    let x;
    let y;

    if (edge === 0) {
        x = Math.random() * spaceGame.width;
        y = -radius;
    } else if (edge === 1) {
        x = spaceGame.width + radius;
        y = Math.random() * spaceGame.height;
    } else if (edge === 2) {
        x = Math.random() * spaceGame.width;
        y = spaceGame.height + radius;
    } else {
        x = -radius;
        y = Math.random() * spaceGame.height;
    }

    const targetX = spaceGame.ship.x + (Math.random() - 0.5) * 100;
    const targetY = spaceGame.ship.y + (Math.random() - 0.5) * 100;
    const angle = Math.atan2(targetY - y, targetX - x);
    const difficulty = getSpaceDifficulty(spaceGame.stage);

    spaceGame.meteors.push({
        x,
        y,
        radius,
        velocityX: Math.cos(angle) * difficulty.meteorSpeed,
        velocityY: Math.sin(angle) * difficulty.meteorSpeed,
        rotation: Math.random() * Math.PI,
    });
}

function spawnSpaceSphere() {
    const radius = 9;
    spaceGame.spheres.push({
        x: radius + Math.random() * (spaceGame.width - radius * 2),
        y: radius + Math.random() * (spaceGame.height - radius * 2),
        radius,
        pulse: Math.random() * Math.PI * 2,
    });
}

function drawSpaceDefender(context) {
    context.clearRect(0, 0, spaceGame.width, spaceGame.height);
    context.fillStyle = '#111827';
    context.fillRect(0, 0, spaceGame.width, spaceGame.height);

    context.fillStyle = 'rgba(196, 240, 91, 0.12)';
    for (let y = 20; y < spaceGame.height; y += 50) {
        context.fillRect(0, y, spaceGame.width, 1);
    }

    context.save();
    context.translate(spaceGame.ship.x, spaceGame.ship.y);
    context.fillStyle = '#c4f05b';
    context.beginPath();
    context.moveTo(18, 0);
    context.lineTo(-12, -12);
    context.lineTo(-7, 0);
    context.lineTo(-12, 12);
    context.closePath();
    context.fill();
    context.restore();

    spaceGame.meteors.forEach((meteor) => {
        context.save();
        context.translate(meteor.x, meteor.y);
        context.rotate(meteor.rotation);
        context.fillStyle = '#ef4444';
        context.beginPath();
        for (let point = 0; point < 8; point += 1) {
            const angle = point * Math.PI / 4;
            const radius = meteor.radius * (point % 2 ? 0.82 : 1.12);
            const x = Math.cos(angle) * radius;
            const y = Math.sin(angle) * radius;
            if (point === 0) context.moveTo(x, y);
            else context.lineTo(x, y);
        }
        context.closePath();
        context.fill();
        context.restore();
    });

    spaceGame.spheres.forEach((sphere) => {
        context.save();
        context.translate(sphere.x, sphere.y);
        context.scale(1 + Math.sin(sphere.pulse) * 0.08, 1 + Math.sin(sphere.pulse) * 0.08);
        context.fillStyle = '#5eead4';
        context.shadowColor = '#5eead4';
        context.shadowBlur = 14;
        context.beginPath();
        context.arc(0, 0, sphere.radius, 0, Math.PI * 2);
        context.fill();
        context.restore();
    });
}

function finishSpaceVictory() {
    if (!activeGame || spaceGame.finished) {
        return;
    }
    spaceGame.finished = true;
    activeGame = false;
    if (spaceAnimationFrame) {
        window.cancelAnimationFrame(spaceAnimationFrame);
        spaceAnimationFrame = null;
    }
    showResult('victory', {
        timeUsedSeconds: getGlobalElapsedSeconds(state),
        score: spaceGame.score,
    });
}

function finishSpaceDefeat() {
    if (!activeGame || spaceGame.finished) {
        return;
    }
    spaceGame.finished = true;
    activeGame = false;
    if (spaceAnimationFrame) {
        window.cancelAnimationFrame(spaceAnimationFrame);
        spaceAnimationFrame = null;
    }
    showResult('defeat', {
        timeUsedSeconds: getGlobalElapsedSeconds(state),
        score: spaceGame.score,
    });
}

function startSpaceDefender() {
    resetSpaceDefender();
    resizeSpaceCanvas();
    spaceGame.lastMeteorAt = performance.now();
    spaceGame.lastSphereAt = performance.now();
    let previousTime = performance.now();

    const frame = (currentTime) => {
        if (!activeGame || spaceGame.finished) {
            return;
        }

        const deltaSeconds = Math.min((currentTime - previousTime) / 1000, 0.05);
        previousTime = currentTime;
        spaceGame.elapsed += deltaSeconds;
        addSurvivalScore(spaceGame, deltaSeconds);
        if (spaceScore) {
            spaceScore.textContent = Number.isInteger(spaceGame.score)
                ? String(spaceGame.score)
                : spaceGame.score.toFixed(1);
        }
        if (spaceStage) {
            spaceStage.textContent = String(spaceGame.stage);
        }

        const ship = spaceGame.ship;
        let horizontal = 0;
        let vertical = 0;
        horizontal += spaceGame.keys.ArrowRight || spaceGame.keys.KeyD ? 1 : 0;
        horizontal -= spaceGame.keys.ArrowLeft || spaceGame.keys.KeyA ? 1 : 0;
        vertical += spaceGame.keys.ArrowDown || spaceGame.keys.KeyS ? 1 : 0;
        vertical -= spaceGame.keys.ArrowUp || spaceGame.keys.KeyW ? 1 : 0;

        if (horizontal !== 0 && vertical !== 0) {
            horizontal *= Math.SQRT1_2;
            vertical *= Math.SQRT1_2;
        }
        ship.x += horizontal * ship.speed * deltaSeconds;
        ship.y += vertical * ship.speed * deltaSeconds;
        ship.x = Math.min(Math.max(ship.radius, ship.x), spaceGame.width - ship.radius);
        ship.y = Math.min(Math.max(ship.radius, ship.y), spaceGame.height - ship.radius);

        const difficulty = getSpaceDifficulty(spaceGame.stage);
        if (currentTime - spaceGame.lastMeteorAt >= difficulty.meteorInterval) {
            const count = difficulty.meteorCount;
            for (let index = 0; index < count; index += 1) {
                spawnSpaceMeteor();
            }
            spaceGame.lastMeteorAt = currentTime;
        }
        if (currentTime - spaceGame.lastSphereAt >= 1800) {
            spawnSpaceSphere();
            spaceGame.lastSphereAt = currentTime;
        }

        spaceGame.meteors.forEach((meteor) => {
            meteor.x += meteor.velocityX * deltaSeconds;
            meteor.y += meteor.velocityY * deltaSeconds;
            meteor.rotation += deltaSeconds * 1.5;
            if (circleCollision(ship, meteor)) {
                finishSpaceDefeat();
            }
        });
        if (!activeGame) {
            return;
        }
        spaceGame.meteors = spaceGame.meteors.filter((meteor) => (
            meteor.x > -meteor.radius * 2
            && meteor.x < spaceGame.width + meteor.radius * 2
            && meteor.y > -meteor.radius * 2
            && meteor.y < spaceGame.height + meteor.radius * 2
        ));

        const collected = [];
        spaceGame.spheres.forEach((sphere, index) => {
            sphere.pulse += deltaSeconds * 4;
            if (circleCollision(ship, sphere)) {
                collected.push(index);
                spaceGame.score += 1;
            }
        });
        if (collected.length > 0) {
            collected.reverse().forEach((index) => spaceGame.spheres.splice(index, 1));
            updateSpaceStage(spaceGame);
            spaceScore.textContent = String(spaceGame.score);
            spaceStage.textContent = String(spaceGame.stage);
            playInterfaceSound(state.soundEnabled, 'selection');
        }

        drawSpaceDefender(spaceCanvas.getContext('2d'));
        if (spaceGame.score >= 20) {
            finishSpaceVictory();
            return;
        }
        spaceAnimationFrame = window.requestAnimationFrame(frame);
    };

    drawSpaceDefender(spaceCanvas.getContext('2d'));
    spawnSpaceSphere();
    spaceAnimationFrame = window.requestAnimationFrame(frame);
}

function returnToRoulette() {
    resetChallengeView();
    resetRockPaperScissors();
    resetSpaceDefender();
    state.selectedId = null;
    selectedPanel.hidden = true;
    spinButton.disabled = false;
    state.activeScreen = 'roulette';
    renderProgress();
    setActiveScreen(state.activeScreen);
}

function showResult(result, details = {}) {
    stopGlobalTimer(state);
    stopIndividualTimer();
    if (spaceAnimationFrame) {
        window.cancelAnimationFrame(spaceAnimationFrame);
        spaceAnimationFrame = null;
    }
    activeGame = false;
    individualTimeWrapper.hidden = true;

    const challenge = state.challenges.find((item) => item.id === state.selectedId);
    if (challenge) {
        recordChallengeOutcome(state, challenge.id, result, {
            attemptNumber: challenge.attempts,
            timeUsedSeconds: details.timeUsedSeconds ?? getGlobalElapsedSeconds(state),
            score: details.score ?? null,
        });
    }

    const copy = getResultCopy(result);
    resultCard.dataset.result = result;
    resultEyebrow.textContent = copy.eyebrow;
    resultTitle.textContent = copy.title;
    resultDescription.textContent = copy.description;
    state.activeScreen = 'result';
    setActiveScreen(state.activeScreen);
    renderProgress();
    playInterfaceSound(state.soundEnabled, result);
}

function abandonActiveChallenge() {
    if (activeGame || countdownInterval) {
        if (activeGame) {
            showResult('abandonment');
            return;
        }
    }
    returnToRoulette();
}

spinButton?.addEventListener('click', spinRoulette);

startButton?.addEventListener('click', () => {
    state.activeScreen = 'instructions';
    setActiveScreen(state.activeScreen);
});

backButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (activeGame || countdownInterval) {
            abandonDialog?.showModal();
        } else {
            returnToRoulette();
        }
    });
});

window.addEventListener('keydown', (event) => {
    if (!activeGame || !['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'KeyW', 'KeyA', 'KeyS', 'KeyD'].includes(event.code)) {
        return;
    }
    if (spaceGame && state.activeScreen === 'space-defender') {
        spaceGame.keys[event.code] = true;
        event.preventDefault();
    }
});

window.addEventListener('keyup', (event) => {
    if (spaceGame) {
        spaceGame.keys[event.code] = false;
    }
});

spaceControlButtons.forEach((button) => {
    const control = button.dataset.spaceControl;
    const keyMap = {
        up: 'ArrowUp',
        down: 'ArrowDown',
        left: 'ArrowLeft',
        right: 'ArrowRight',
    };
    const key = keyMap[control];

    const press = (event) => {
        event.preventDefault();
        if (activeGame && state.activeScreen === 'space-defender') {
            spaceGame.keys[key] = true;
        }
    };
    const release = (event) => {
        event.preventDefault();
        spaceGame.keys[key] = false;
    };

    button.addEventListener('pointerdown', press);
    button.addEventListener('pointerup', release);
    button.addEventListener('pointerleave', release);
    button.addEventListener('pointercancel', release);
});

window.addEventListener('resize', resizeSpaceCanvas);

beginButton?.addEventListener('click', () => {
    beginButton.hidden = true;
    instructionsScreen.querySelectorAll('[data-instructions-content]').forEach((element) => {
        element.hidden = true;
    });
    countdownPanel.hidden = false;

    const sequence = ['3', '2', '1', 'Inicio'];
    let index = 0;
    countdown.hidden = false;
    countdown.textContent = sequence[index];
    playInterfaceSound(state.soundEnabled, 'countdown');

    countdownInterval = window.setInterval(() => {
        index += 1;
        if (index >= sequence.length) {
            window.clearInterval(countdownInterval);
            countdownInterval = null;
            countdown.textContent = 'Minijuego listo';
            activeGame = true;
            startGlobalTimer(state);
            playInterfaceSound(state.soundEnabled, 'start');

            const selectedChallenge = state.challenges.find((challenge) => (
                challenge.id === state.selectedId
            ));
            if (selectedChallenge) {
                registerChallengeAttempt(state, selectedChallenge.id);
            }

            const individualSeconds = selectedChallenge?.id === 'trivia' ? 45 : 30;
            individualTimeWrapper.hidden = false;
            document.querySelectorAll('[data-individual-time]').forEach((element) => {
                element.textContent = `${individualSeconds}s`;
            });

            if (selectedChallenge?.id === 'rock-paper-scissors') {
                resetRockPaperScissors();
                state.activeScreen = 'rock-paper-scissors';
                setActiveScreen(state.activeScreen);
            }
            if (selectedChallenge?.id === 'space-defender') {
                resetSpaceDefender();
                state.activeScreen = 'space-defender';
                setActiveScreen(state.activeScreen);
                startSpaceDefender();
            }

            startIndividualTimer(individualSeconds, () => {
                if (!activeGame) {
                    return;
                }
                showResult('defeat', {
                    timeUsedSeconds: individualSeconds,
                    score: state.selectedId === 'rock-paper-scissors' ? rpsGame.playerWins : 0,
                });
            });
            return;
        }

        countdown.textContent = sequence[index];
        playInterfaceSound(state.soundEnabled, 'countdown');
    }, 700);
});

rpsChoiceButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (!activeGame || rpsGame.finished || rpsGame.locked) {
            return;
        }

        const playerChoice = button.dataset.rpsChoice;
        const computerChoice = getComputerChoice();
        const result = playRockPaperScissorsRound(rpsGame, playerChoice, computerChoice);

        rpsPlayerChoice.textContent = `${rpsIcons[playerChoice]} ${rpsLabels[playerChoice]}`;
        rpsComputerChoice.textContent = `${rpsIcons[computerChoice]} ${rpsLabels[computerChoice]}`;

        if (result.roundResult === 'tie') {
            rpsRoundResult.textContent = 'Empate — repitan la jugada';
        } else if (result.roundResult === 'victory') {
            rpsRoundResult.textContent = 'Ganaste esta ronda';
            playInterfaceSound(state.soundEnabled, 'selection');
        } else {
            rpsRoundResult.textContent = 'La computadora ganó esta ronda';
            playInterfaceSound(state.soundEnabled, 'defeat');
        }

        renderRockPaperScissors();

        if (result.outcome) {
            window.setTimeout(() => {
                showResult(result.outcome, {
                    timeUsedSeconds: getGlobalElapsedSeconds(state),
                    score: rpsGame.playerWins,
                });
            }, 550);
            return;
        }

        rpsChoiceButtons.forEach((choiceButton) => {
            choiceButton.disabled = true;
        });
        window.setTimeout(() => {
            rpsGame.locked = false;
            rpsChoiceButtons.forEach((choiceButton) => {
                choiceButton.disabled = false;
            });
        }, 650);
    });
});

soundButton?.addEventListener('click', () => {
    state.soundEnabled = !state.soundEnabled;
    renderSoundButton();
    playInterfaceSound(state.soundEnabled, 'selection');
});

retryButton?.addEventListener('click', () => {
    resetChallengeView();
    resetRockPaperScissors();
    resetSpaceDefender();
    state.activeScreen = 'instructions';
    setActiveScreen(state.activeScreen);
    beginButton?.click();
});

returnButtons.forEach((button) => {
    button.addEventListener('click', returnToRoulette);
});

abandonDialog?.addEventListener('close', () => {
    if (abandonDialog.returnValue !== 'confirm') {
        return;
    }

    if (activeGame) {
        showResult('abandonment');
    } else {
        returnToRoulette();
    }
});

window.setInterval(updateGlobalTimerDisplay, 100);

document.addEventListener('visibilitychange', () => {
    if (document.visibilityState !== 'hidden' || !activeGame) {
        return;
    }

    const elapsed = stopGlobalTimer(state);
    stopIndividualTimer();

    const challenge = state.challenges.find((item) => item.id === state.selectedId);
    if (challenge) {
        recordChallengeOutcome(state, challenge.id, 'abandonment', {
            attemptNumber: challenge.attempts,
            timeUsedSeconds: elapsed,
        });
    }
    activeGame = false;
    individualTimeWrapper.hidden = true;
    returnToRoulette();
});

timer.textContent = formatTime(state.globalElapsedSeconds);
renderProgress();
renderSoundButton();
