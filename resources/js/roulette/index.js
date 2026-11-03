import { setActiveScreen } from './navigation.js';
import { createRouletteState, selectPendingChallenge } from './state.js';

const state = createRouletteState();

const roulette = document.querySelector('[data-roulette]');
const spinButton = document.querySelector('[data-spin-roulette]');
const selectedPanel = document.querySelector('[data-selected-challenge]');
const selectedName = document.querySelector('[data-selected-name]');
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
let countdownInterval = null;

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainder = seconds % 60;
    return `${String(minutes).padStart(2, '0')}:${String(remainder).padStart(2, '0')}`;
}

function renderProgress() {
    const completed = state.challenges.filter((challenge) => challenge.completed).length;
    progress.textContent = `${completed}/4`;
    progress.setAttribute('aria-label', `${completed} de 4 experiencias completadas`);

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
        selectedPanel.hidden = false;
        spinButton.disabled = true;
        startButton.hidden = true;
        return;
    }

    state.selectedId = challenge.id;
    selectedName.textContent = challenge.name;
    selectedPanel.hidden = false;
    instructionName.textContent = challenge.name;
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
    const targetRotation = rotations * 360 + (360 - (challengeIndex * segmentAngle));

    roulette.style.setProperty('--roulette-rotation', `${targetRotation}deg`);
    roulette.classList.add('is-spinning');

    window.setTimeout(() => {
        roulette.classList.remove('is-spinning');
        roulette.classList.add('is-selected');
        renderSelection(challenge);
        spinButton.disabled = false;
    }, 1800);
}

function renderSoundButton() {
    soundButton.setAttribute('aria-pressed', String(state.soundEnabled));
    soundButton.textContent = state.soundEnabled ? 'Sonido activo' : 'Sonido silencioso';
}

spinButton?.addEventListener('click', spinRoulette);

startButton?.addEventListener('click', () => {
    state.activeScreen = 'instructions';
    setActiveScreen(state.activeScreen);
});

backButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (countdownInterval) {
            window.clearInterval(countdownInterval);
            countdownInterval = null;
        }

        state.activeScreen = 'roulette';
        setActiveScreen(state.activeScreen);

        instructionsScreen.querySelectorAll('[data-instructions-content]').forEach((element) => {
            element.hidden = false;
        });
        countdown.hidden = true;
        countdownPanel.hidden = true;
        beginButton.hidden = false;
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

    countdownInterval = window.setInterval(() => {
        index += 1;
        if (index >= sequence.length) {
            window.clearInterval(countdownInterval);
            countdownInterval = null;
            countdown.textContent = 'Minijuego listo';
            return;
        }

        countdown.textContent = sequence[index];
    }, 700);
});

soundButton?.addEventListener('click', () => {
    state.soundEnabled = !state.soundEnabled;
    renderSoundButton();
});

timer.textContent = formatTime(state.globalElapsedSeconds);
renderProgress();
renderSoundButton();
