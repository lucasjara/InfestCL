export function setActiveScreen(screenName) {
    document.querySelectorAll('[data-roulette-screen]').forEach((screen) => {
        screen.hidden = screen.dataset.rouletteScreen !== screenName;
    });

    const gameHud = document.querySelector('[data-game-hud]');
    if (gameHud) {
        gameHud.hidden = ['roulette', 'instructions', 'result'].includes(screenName);
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
