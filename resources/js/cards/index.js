/**
 * Entrada Vite del módulo de cartas.
 * Expone window.InfestCardReveal para usarlo desde Blade sin acoplamiento.
 */

import '../../css/cards/card-reveal.css';
import { CardReveal } from './CardReveal.js';

window.InfestCardReveal = CardReveal;

export default CardReveal;