export function setActiveScreen(screenName) {
    document.querySelectorAll('[data-roulette-screen]').forEach((screen) => {
        screen.hidden = screen.dataset.rouletteScreen !== screenName;
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
