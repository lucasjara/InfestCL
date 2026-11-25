/**
 * CardTilt — efecto 3D interactivo para la carta de «Tu carta obtenida».
 * Inclinación suave por cursor/touch, sombra dinámica y reflejo holográfico.
 * Sin dependencias externas.
 */

const MAX_TILT = 16; // grados
const LERP = 0.14;
const RESET_DURATION = 450; // ms

export class CardTilt {
    constructor(element) {
        this.el = element;
        this.stage = element?.parentElement ?? null;
        this.targetX = 0;
        this.targetY = 0;
        this.currentX = 0;
        this.currentY = 0;
        this.isDragging = false;
        this.pointerId = null;
        this.raf = null;
        this.reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;

        if (!this.reduceMotion && element) {
            this._bind();
            this._loop();
        }
    }

    _bind() {
        const el = this.el;

        el.addEventListener('pointerenter', () => el.classList.add('is-tilting'));
        el.addEventListener('pointerleave', () => this._reset());
        el.addEventListener('pointermove', (event) => this._onPointerMove(event));
        el.addEventListener('pointerdown', (event) => this._onPointerDown(event));
        el.addEventListener('pointerup', () => this._onPointerUp());
        el.addEventListener('pointercancel', () => this._onPointerUp());

        // Evitar que el arrastre seleccione texto.
        el.addEventListener('dragstart', (event) => event.preventDefault());
    }

    _onPointerMove(event) {
        if (!this.el) return;
        const rect = this.el.getBoundingClientRect();
        const px = (event.clientX - rect.left) / rect.width - 0.5;
        const py = (event.clientY - rect.top) / rect.height - 0.5;

        this.targetY = px * MAX_TILT * 2; // rotateY
        this.targetX = -py * MAX_TILT * 2; // rotateX

        this.el.style.setProperty('--holo-x', `${(px + 0.5) * 100}%`);
        this.el.style.setProperty('--holo-y', `${(py + 0.5) * 100}%`);

        const shadowX = (px * 18).toFixed(1);
        const shadowY = (14 + py * 8).toFixed(1);
        this.el.style.setProperty('--shadow-x', `${shadowX}px`);
        this.el.style.setProperty('--shadow-y', `${shadowY}px`);
    }

    _onPointerDown(event) {
        this.isDragging = true;
        this.pointerId = event.pointerId;
        this.el.classList.add('is-dragging');
        try {
            this.el.setPointerCapture(event.pointerId);
        } catch {
            // Ignorar si el navegador no soporta pointer capture.
        }
    }

    _onPointerUp() {
        if (!this.isDragging) return;
        this.isDragging = false;
        this.pointerId = null;
        this.el.classList.remove('is-dragging');
        this._reset();
    }

    _reset() {
        this.targetX = 0;
        this.targetY = 0;
        this.el.classList.remove('is-tilting');
        this.el.style.removeProperty('--holo-x');
        this.el.style.removeProperty('--holo-y');
        this.el.style.setProperty('--shadow-x', '0px');
        this.el.style.setProperty('--shadow-y', '14px');
    }

    _loop() {
        if (!this.el) return;

        this.currentX += (this.targetX - this.currentX) * LERP;
        this.currentY += (this.targetY - this.currentY) * LERP;

        this.el.style.transform =
            `translateZ(22px) rotateX(${this.currentX.toFixed(2)}deg) rotateY(${this.currentY.toFixed(2)}deg)`;

        this.raf = requestAnimationFrame(() => this._loop());
    }

    destroy() {
        if (this.raf) cancelAnimationFrame(this.raf);
        this.raf = null;
        this.el = null;
    }
}

export function attachCardTilt(element) {
    if (!element) return null;
    return new CardTilt(element);
}

export default CardTilt;