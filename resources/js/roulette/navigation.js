export function setActiveScreen(screenName, { scroll = true } = {}) {
    document.querySelectorAll('[data-roulette-screen]').forEach((screen) => {
        screen.hidden = screen.dataset.rouletteScreen !== screenName;
    });

    const gameHud = document.querySelector('[data-game-hud]');
    if (gameHud) {
        gameHud.hidden = ['roulette', 'instructions', 'result'].includes(screenName);
    }

    if (scroll) {
        const prefersReducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
        window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    }
}
