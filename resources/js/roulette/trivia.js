const fallbackQuestions = [
    { question: '¿Qué compañía creó el videojuego Super Mario Bros?', difficulty: 'easy', answers: ['Nintendo', 'Sega', 'Atari', 'Sony'], correctAnswer: 'Nintendo' },
    { question: '¿Qué lenguaje usa Laravel como base?', difficulty: 'easy', answers: ['PHP', 'Python', 'Java', 'Ruby'], correctAnswer: 'PHP' },
    { question: '¿Cómo se llama el personaje principal de The Legend of Zelda?', difficulty: 'medium', answers: ['Link', 'Zelda', 'Ganondorf', 'Epona'], correctAnswer: 'Link' },
    { question: '¿Qué estructura se usa para consultas de datos en bases relacionales?', difficulty: 'medium', answers: ['SQL', 'HTML', 'XML', 'JSON'], correctAnswer: 'SQL' },
    { question: '¿Qué algoritmo clásico fue publicado por Edsger Dijkstra?', difficulty: 'hard', answers: ['Ruta más corta', 'Burbuja', 'RSA', 'Diffie-Hellman'], correctAnswer: 'Ruta más corta' },
];

export function createTriviaGame() {
    return {
        questions: [],
        currentIndex: 0,
        correctAnswers: 0,
        incorrectAnswers: 0,
        answered: false,
        finished: false,
    };
}

export function shuffleItems(items) {
    const shuffled = [...items];
    for (let index = shuffled.length - 1; index > 0; index -= 1) {
        const randomIndex = Math.floor(Math.random() * (index + 1));
        [shuffled[index], shuffled[randomIndex]] = [shuffled[randomIndex], shuffled[index]];
    }
    return shuffled;
}

export function getProgressiveFallbackQuestions() {
    return fallbackQuestions.map((question) => ({
        ...question,
        answers: shuffleItems(question.answers),
    }));
}

export function normalizeExternalQuestions(items) {
    return items
        .filter((item) => item?.question && item?.correct_answer && Array.isArray(item?.incorrect_answers))
        .filter((item) => item.incorrect_answers.length === 3)
        .map((item) => ({
            question: decodeHtmlEntities(item.question),
            difficulty: item.difficulty,
            answers: shuffleItems([
                decodeHtmlEntities(item.correct_answer),
                ...item.incorrect_answers.map(decodeHtmlEntities),
            ]),
            correctAnswer: decodeHtmlEntities(item.correct_answer),
        }));
}

function decodeHtmlEntities(value) {
    const textarea = document.createElement('textarea');
    textarea.innerHTML = value;
    return textarea.value;
}

export function selectFiveProgressiveQuestions(questions) {
    const grouped = {
        easy: shuffleItems(questions.filter((question) => question.difficulty === 'easy')),
        medium: shuffleItems(questions.filter((question) => question.difficulty === 'medium')),
        hard: shuffleItems(questions.filter((question) => question.difficulty === 'hard')),
    };
    const selected = [
        grouped.easy[0],
        grouped.easy[1] ?? grouped.medium[0],
        grouped.medium[0] ?? grouped.easy[1],
        grouped.hard[0] ?? grouped.medium[1],
        grouped.hard[1] ?? grouped.medium[2] ?? grouped.easy[2],
    ].filter(Boolean);
    return selected.length === 5 ? selected : getProgressiveFallbackQuestions();
}

export async function fetchTriviaQuestions(apiUrl = '/api/questions') {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 5000);
    try {
        const response = await fetch(
            apiUrl,
            { signal: controller.signal },
        );
        if (!response.ok) {
            return getProgressiveFallbackQuestions();
        }
        const payload = await response.json();
        if (payload.response_code !== 0 || !Array.isArray(payload.results)) {
            return getProgressiveFallbackQuestions();
        }
        const normalized = normalizeExternalQuestions(payload.results);
        return selectFiveProgressiveQuestions(normalized);
    } catch {
        return getProgressiveFallbackQuestions();
    } finally {
        clearTimeout(timeout);
    }
}

export function resolveTriviaAnswer(game, selectedAnswer) {
    if (game.finished || game.answered) {
        return null;
    }
    game.answered = true;
    const question = game.questions[game.currentIndex];
    const correct = selectedAnswer === question.correctAnswer;
    if (correct) {
        game.correctAnswers += 1;
    } else {
        game.incorrectAnswers += 1;
    }
    return { correct, correctAnswer: question.correctAnswer };
}
