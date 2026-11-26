/**
 * Entrada Vite del módulo de cartas.
 * Expone window.InfestCardReveal para usarlo desde Blade sin acoplamiento.
 */

import '../../css/cards/card-reveal.css';
import '../../css/cards/card-tilt.css';
import { CardReveal } from './CardReveal.js';
import { attachCardTilt } from './CardTilt.js';

window.InfestCardReveal = CardReveal;
window.InfestCardTilt = { attach: attachCardTilt };

export default CardReveal;