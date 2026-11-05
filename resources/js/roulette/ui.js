const challengeInstructions = {
    'rock-paper-scissors': {
        objective: 'Consigue dos victorias antes de que se agoten las rondas o los 30 segundos.',
        controls: 'Elige piedra, papel o tijera en cada ronda.',
        time: 30,
    },
    'space-defender': {
        objective: 'Alcanza 20 puntos esquivando meteoritos y reuniendo esferas.',
        controls: 'Mueve la nave con las teclas o los controles táctiles.',
        time: 30,
    },
    trivia: {
        objective: 'Responde correctamente al menos tres de cinco preguntas.',
        controls: 'Selecciona una de las cuatro respuestas disponibles.',
        time: 45,
    },
    maze: {
        objective: 'Encuentra la salida del laberinto antes de que termine el tiempo.',
        controls: 'Muévete con las flechas del teclado o los controles táctiles.',
        time: 30,
    },
};

export function formatTime(seconds) {
    const safeSeconds = Math.max(0, Math.floor(seconds));
    const minutes = Math.floor(safeSeconds / 60);
    const remainder = safeSeconds % 60;
    return `${String(minutes).padStart(2, '0')}:${String(remainder).padStart(2, '0')}`;
}

export function getChallengeInstructions(challengeId) {
    return challengeInstructions[challengeId] ?? {
        objective: 'Completa la condición principal antes de que termine el tiempo.',
        controls: 'Los controles se mostrarán al iniciar el minijuego.',
        time: 30,
    };
}

export function getResultCopy(result) {
    if (result === 'abandonment') {
        return {
            eyebrow: 'Intento finalizado',
            title: 'Desafío abandonado',
            description: 'El abandono quedó registrado. Puedes volver a intentarlo más tarde.',
        };
    }

    return result === 'victory'
        ? {
            eyebrow: 'Desafío superado',
            title: '¡Victoria!',
            description: 'El desafío queda registrado como completado.',
        }
        : {
            eyebrow: 'Desafío no superado',
            title: 'Derrota',
            description: 'Puedes reintentar el desafío o volver a la ruleta.',
        };
}

export function playInterfaceSound(enabled, type = 'selection') {
    if (!enabled || typeof window === 'undefined') {
        return;
    }

    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!AudioContext) {
        return;
    }

    const context = new AudioContext();
    const oscillator = context.createOscillator();
    const gain = context.createGain();
    const frequencies = { countdown: 330, start: 520, victory: 660, defeat: 180, selection: 440 };

    oscillator.type = type === 'victory' ? 'triangle' : 'sine';
    oscillator.frequency.value = frequencies[type] ?? frequencies.selection;
    gain.gain.setValueAtTime(0.0001, context.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.12, context.currentTime + 0.01);
    gain.gain.exponentialRampToValueAtTime(0.0001, context.currentTime + 0.18);
    oscillator.connect(gain);
    gain.connect(context.destination);
    oscillator.start();
    oscillator.stop(context.currentTime + 0.2);
    oscillator.addEventListener('ended', () => context.close());
}
