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
let countdownInterval = null;
let currentRouletteRotation = 0;
let individualTimerRemaining = null;
let activeGame = false;

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

function returnToRoulette() {
    resetChallengeView();
    state.activeScreen = 'roulette';
    setActiveScreen(state.activeScreen);
}

function showResult(result, details = {}) {
    stopGlobalTimer(state);
    stopIndividualTimer();
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
            startIndividualTimer(individualSeconds, () => {
                if (!activeGame) {
                    return;
                }
                showResult('defeat', { timeUsedSeconds: individualSeconds, score: 0 });
            });
            return;
        }

        countdown.textContent = sequence[index];
        playInterfaceSound(state.soundEnabled, 'countdown');
    }, 700);
});

soundButton?.addEventListener('click', () => {
    state.soundEnabled = !state.soundEnabled;
    renderSoundButton();
    playInterfaceSound(state.soundEnabled, 'selection');
});

retryButton?.addEventListener('click', () => {
    state.activeScreen = 'instructions';
    setActiveScreen(state.activeScreen);
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
