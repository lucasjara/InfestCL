/**
 * CardReveal — orquesta la secuencia de revelación de cartas.
 *
 * Secuencia: oscurecido → portal → aparición del reverso → flip 3D →
 * destellos + partículas → presentación (flotación + holográfico) → cierre.
 *
 * Uso:
 *   import { CardReveal } from './CardReveal.js';
 *   CardReveal.play({ card, source });
 *
 * No altera la lógica de creación/obtención/almacenamiento: solo muestra
 * un overlay visual y devuelve una Promesa que se resuelve al cerrar.
 */

import { CardParticles } from './CardParticles.js';
import { CardHolographic } from './CardHolographic.js';

const prefersReducedMotion = () =>
    window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;

class CardRevealController {
    constructor() {
        this.root = null;
        this.busy = false;
        this._token = 0;
        this._resolve = null;
        this._particles = null;
        this._holo = null;
        this._refs = null;
        this._bound = false;
        this._autoCloseTimer = null;
    }

    /**
     * Lanza la animación. Devuelve una Promesa que resuelve al cerrar el overlay.
     * @param {{ card?: object, source?: HTMLElement }} options
     */
    play(options = {}) {
        if (this.busy) return Promise.resolve();

        this._ensureDom();
        if (!this._refs.root) return Promise.resolve();

        this.busy = true;
        this._closing = false;
        this._token += 1;
        const token = this._token;

        return new Promise((resolve) => {
            this._resolve = resolve;
            this._prepareCard(options);
            this._bindControls();
            this._run(token);
        });
    }

    /* ------------------------------------------------------------------ */
    /* Preparación                                                        */
    /* ------------------------------------------------------------------ */

    _ensureDom() {
        if (this._refs && document.body.contains(this._refs.root)) return;

        const existing = document.getElementById('card-reveal');
        if (existing) {
            this._refs = this._collect(existing);
            this._particles = new CardParticles(this._refs.particles);
            this._holo = new CardHolographic(existing);
            return;
        }

        // Fallback: si el componente Blade no está presente, construir el DOM.
        const root = document.createElement('div');
        root.id = 'card-reveal';
        root.className = 'card-reveal';
        root.hidden = true;
        root.innerHTML = `
            <div class="card-reveal__backdrop"></div>
            <div class="card-reveal__portal"></div>
            <canvas class="card-reveal__particles"></canvas>
            <div class="card-reveal__stage">
                <div class="card-reveal__card-wrap">
                    <div class="card-reveal__glow"></div>
                    <div class="card-reveal__card">
                        <div class="card-reveal__face card-reveal__face--back">
                            <div class="card-reveal__back-mark">
                                <span class="card-reveal__back-emblem">
    <img src="/images/brand/Infest_Logo_completo.png" alt="INFEST">
</span>
                                <span class="card-reveal__back-title">INFEST</span>
                                <span class="card-reveal__back-sub">Card</span>
                            </div>
                        </div>
                        <div class="card-reveal__face card-reveal__face--front"></div>
                    </div>
                    <div class="card-reveal__holographic"></div>
                    <div class="card-reveal__flash"></div>
                </div>
            </div>
            <button type="button" class="card-reveal__skip">Saltar</button>
            <button type="button" class="card-reveal__close">Continuar</button>
        `;
        document.body.appendChild(root);

        this._refs = this._collect(root);
        this._particles = new CardParticles(this._refs.particles);
        this._holo = new CardHolographic(root);
    }

    _collect(root) {
        const q = (sel) => root.querySelector(sel);
        return {
            root,
            backdrop: q('.card-reveal__backdrop'),
            particles: q('.card-reveal__particles'),
            cardWrap: q('.card-reveal__card-wrap'),
            card: q('.card-reveal__card'),
            front: q('.card-reveal__face--front'),
            skip: q('.card-reveal__skip'),
            close: q('.card-reveal__close'),
        };
    }

    _prepareCard({ card, source }) {
        const { root, front } = this._refs;

        // Limpiar una carta previa.
        front.innerHTML = '';

        // Clonar la carta visible para que la revelación coincida exactamente.
        let accent = '#a855f7';
        if (source && source.cloneNode) {
            const clone = source.cloneNode(true);
            clone.querySelectorAll('[id]').forEach((node) => node.removeAttribute('id'));
            // Limpiar efectos del tilt 3D para no interferir con el flip.
            clone.classList.remove('card-3d');
            clone.style.removeProperty('transform');
            clone.style.removeProperty('--holo-x');
            clone.style.removeProperty('--holo-y');
            clone.style.removeProperty('--shadow-x');
            clone.style.removeProperty('--shadow-y');
            clone.querySelectorAll('.card-loading').forEach((node) => {
                node.style.display = 'none';
            });

            const computed = getComputedStyle(source);
            const color = computed.getPropertyValue('--category-color').trim();
            if (color) accent = color;

            front.appendChild(clone);
        } else if (card) {
            front.innerHTML = this._buildCardFallback(card);
        }

        root.style.setProperty('--reveal-accent', accent);
        root.classList.remove(
            'is-open',
            'is-portal',
            'is-enter',
            'is-flashing',
            'is-revealed'
        );
        this._refs.card?.classList.remove('is-flipped');
        this._holo?.disable();
        this._particles?.clear();
    }

    _buildCardFallback(card) {
        const escape = (value) =>
            String(value ?? '').replace(/[&<>"]/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
            }[c]));

        return `
            <div class="generated-card" style="--category-color: var(--reveal-accent)">
                <div class="generated-card-header">
                    <h3>${escape(card.name)}</h3>
                    <span>${escape(card.category ?? '')}</span>
                </div>
                <div class="generated-card-image">
                    <img src="${escape(card.image ?? '')}" alt="">
                </div>
                <div class="generated-card-description">
                    <p>${escape(card.description ?? '')}</p>
                </div>
            </div>
        `;
    }

    /* ------------------------------------------------------------------ */
    /* Controles                                                          */
    /* ------------------------------------------------------------------ */

    _bindControls() {
        if (this._bound || !this._refs) return;
        this._bound = true;

        this._refs.skip?.addEventListener('click', () => this._skip());
        this._refs.close?.addEventListener('click', () => this._close());
        this._refs.backdrop?.addEventListener('click', () => {
            if (this._revealed) this._close();
            else this._skip();
        });

        this._onKey = (event) => {
            if (event.key === 'Escape') {
                if (this._revealed) this._close();
                else this._skip();
            }
        };
        document.addEventListener('keydown', this._onKey);

        this._onResize = () => this._particles?.resize();
        window.addEventListener('resize', this._onResize);
    }

    /* ------------------------------------------------------------------ */
    /* Secuencia                                                          */
    /* ------------------------------------------------------------------ */

    async _run(token) {
        const reduce = prefersReducedMotion();
        const { root, cardWrap } = this._refs;

        this._revealed = false;
        root.hidden = false;
        // Forzar reflow para que las transiciones arranquen desde el estado inicial.
        void root.offsetWidth;
        root.classList.add('is-open');
        this._particles?.resize();

        const wait = (ms) => this._sleep(reduce ? Math.min(ms, 30) : ms, token);
        const alive = () => token === this._token;

        // 1-2. Portal + aparición del reverso.
        if (!reduce) root.classList.add('is-portal');
        if (!alive()) return;
        await wait(420);
        if (!alive()) return;

        root.classList.add('is-enter');
        await wait(720);
        if (!alive()) return;

        // 3. Flip 3D + destello + partículas.
        this._refs.card?.classList.add('is-flipped');
        await wait(reduce ? 20 : 430);
        if (!alive()) return;

        if (!reduce) {
            root.classList.add('is-flashing');
            this._burstCenter(cardWrap, 120);
        }
        await wait(reduce ? 20 : 520);
        if (!alive()) return;

        // 4. Presentación.
        this._reveal();
    }

    _reveal() {
        if (!this._refs) return;
        this._revealed = true;
        this._refs.root.classList.add('is-revealed');
        this._holo?.enable();

        if (!prefersReducedMotion()) {
            const rect = this._refs.cardWrap.getBoundingClientRect();
            this._particles?.burst(rect.left + rect.width / 2, rect.top + rect.height / 2, {
                count: 70,
                speed: 7,
            });
        }

        // Cierre automático tras mostrar la carta revelada.
        if (this._autoCloseTimer) clearTimeout(this._autoCloseTimer);
        const delay = 5000;
        this._autoCloseTimer = setTimeout(() => {
            if (this._revealed) this._close();
        }, delay);
    }

    _burstCenter(cardWrap, count) {
        if (!this._particles || !cardWrap) return;
        const rect = cardWrap.getBoundingClientRect();
        this._particles.burst(rect.left + rect.width / 2, rect.top + rect.height / 2, {
            count,
            speed: 8,
        });
    }

    /* ------------------------------------------------------------------ */
    /* Cierre                                                             */
    /* ------------------------------------------------------------------ */

    _skip() {
        if (!this._refs || this._revealed || this._closing) return;
        this._token += 1; // cancela la secuencia pendiente
        this._refs.card?.classList.add('is-flipped');
        this._refs.root.classList.remove('is-portal', 'is-enter', 'is-flashing');
        this._reveal();
    }

    _close() {
        if (!this._refs || this._closing) return;
        this._closing = true;
        if (this._autoCloseTimer) {
            clearTimeout(this._autoCloseTimer);
            this._autoCloseTimer = null;
        }
        this._token += 1;
        this._holo?.disable();
        this._particles?.stop();

        const { root } = this._refs;
        root.classList.remove('is-open', 'is-revealed');
        root.classList.add('is-closing');

        // Transición de desaparición antes de ocultar.
        setTimeout(() => {
            root.classList.remove('is-closing', 'is-portal', 'is-enter', 'is-flashing');
            root.hidden = true;
            this._revealed = false;
            this.busy = false;
            this._closing = false;
            this._particles?.clear();

            if (this._resolve) {
                const resolve = this._resolve;
                this._resolve = null;
                resolve();
            }
        }, 450);
    }

    _sleep(ms, token) {
        return new Promise((resolve) => {
            setTimeout(() => resolve(token === this._token), ms);
        });
    }
}

export const CardReveal = new CardRevealController();
export default CardReveal;