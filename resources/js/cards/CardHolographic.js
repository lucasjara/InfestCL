/**
 * CardHolographic — controla el brillo holográfico y el ligero tilt 3D
 * de la carta revelada. Trabaja sobre el overlay de revelación.
 */

export class CardHolographic {
    constructor(root) {
        this.root = root;
        this.holo = root ? root.querySelector('.card-reveal__holographic') : null;
        this.card = root ? root.querySelector('.card-reveal__card') : null;
        this.wrap = root ? root.querySelector('.card-reveal__card-wrap') : null;
        this.enabled = false;
        this._onPointerMove = this._onPointerMove.bind(this);
        this._onPointerLeave = this._onPointerLeave.bind(this);
    }

    /**
     * Activa el efecto holográfico y el seguimiento del puntero (tilt suave).
     */
    enable() {
        if (this.enabled) return;
        this.enabled = true;

        if (this.wrap) {
            this.wrap.addEventListener('pointermove', this._onPointerMove);
            this.wrap.addEventListener('pointerleave', this._onPointerLeave);
        }
    }

    disable() {
        if (!this.enabled) return;
        this.enabled = false;

        if (this.wrap) {
            this.wrap.removeEventListener('pointermove', this._onPointerMove);
            this.wrap.removeEventListener('pointerleave', this._onPointerLeave);
        }

        if (this.card) {
            this.card.style.removeProperty('--tilt-x');
            this.card.style.removeProperty('--tilt-y');
        }
    }

    _onPointerMove(event) {
        if (!this.wrap || !this.card) return;

        const rect = this.wrap.getBoundingClientRect();
        const px = (event.clientX - rect.left) / rect.width - 0.5;
        const py = (event.clientY - rect.top) / rect.height - 0.5;

        // Tilt muy sutil para no romper el flip 3D base.
        const tiltX = (-py * 10).toFixed(2);
        const tiltY = (px * 14).toFixed(2);

        this.card.style.setProperty('--tilt-x', `${tiltX}deg`);
        this.card.style.setProperty('--tilt-y', `${tiltY}deg`);

        if (this.holo) {
            this.holo.style.setProperty('--holo-x', `${(px + 0.5) * 100}%`);
        }
    }

    _onPointerLeave() {
        if (!this.card) return;
        this.card.style.removeProperty('--tilt-x');
        this.card.style.removeProperty('--tilt-y');
    }
}

export default CardHolographic;