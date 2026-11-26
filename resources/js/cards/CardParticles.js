/**
 * CardParticles — sistema de partículas para la revelación de cartas.
 * Dibuja destellos y partículas neón sobre un <canvas> sin dependencias.
 */

const NEON_PALETTE = ['#c3a5ff', '#a855f7', '#22d3ee', '#f472b6', '#facc15', '#ffffff'];

function randomBetween(min, max) {
    return min + Math.random() * (max - min);
}

function pick(list) {
    return list[Math.floor(Math.random() * list.length)];
}

export class CardParticles {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas ? canvas.getContext('2d') : null;
        this.particles = [];
        this.raf = null;
        this.dpr = Math.min(window.devicePixelRatio || 1, 2);
        this.running = false;

        if (canvas) {
            this.resize();
        }
    }

    resize() {
        if (!this.canvas) return;

        const rect = this.canvas.getBoundingClientRect();
        this.width = rect.width || window.innerWidth;
        this.height = rect.height || window.innerHeight;
        this.canvas.width = Math.floor(this.width * this.dpr);
        this.canvas.height = Math.floor(this.height * this.dpr);
        this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);
    }

    /**
     * Emite una ráfaga de partículas desde un punto (coordenadas CSS del canvas).
     */
    burst(x, y, options = {}) {
        const count = options.count ?? 90;
        const speed = options.speed ?? 6;
        const spread = options.spread ?? Math.PI * 2;
        const baseAngle = options.angle ?? 0;
        const colors = options.colors ?? NEON_PALETTE;
        const sizeRange = options.size ?? [1.5, 4.5];
        const life = options.life ?? [40, 90];

        for (let i = 0; i < count; i += 1) {
            const angle = baseAngle + randomBetween(-spread / 2, spread / 2);
            const velocity = randomBetween(speed * 0.35, speed);

            this.particles.push({
                x,
                y,
                vx: Math.cos(angle) * velocity,
                vy: Math.sin(angle) * velocity,
                gravity: randomBetween(0.02, 0.08),
                friction: randomBetween(0.94, 0.99),
                size: randomBetween(sizeRange[0], sizeRange[1]),
                color: pick(colors),
                life: randomBetween(life[0], life[1]),
                maxLife: life[1],
                twinkle: Math.random() * Math.PI * 2,
            });
        }

        this.start();
    }

    start() {
        if (this.running || !this.ctx) return;
        this.running = true;
        this._loop();
    }

    stop() {
        this.running = false;
        if (this.raf) {
            cancelAnimationFrame(this.raf);
            this.raf = null;
        }
    }

    clear() {
        this.particles = [];
        if (this.ctx) {
            this.ctx.clearRect(0, 0, this.width, this.height);
        }
    }

    _loop() {
        if (!this.running) return;

        const { ctx } = this;
        ctx.clearRect(0, 0, this.width, this.height);
        ctx.globalCompositeOperation = 'lighter';

        for (let i = this.particles.length - 1; i >= 0; i -= 1) {
            const p = this.particles[i];

            p.vx *= p.friction;
            p.vy = p.vy * p.friction + p.gravity;
            p.x += p.vx;
            p.y += p.vy;
            p.life -= 1;
            p.twinkle += 0.35;

            if (p.life <= 0) {
                this.particles.splice(i, 1);
                continue;
            }

            const alpha = Math.max(0, p.life / p.maxLife);
            const twinkle = 0.6 + 0.4 * Math.sin(p.twinkle);
            ctx.globalAlpha = alpha * twinkle;
            ctx.fillStyle = p.color;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fill();
        }

        ctx.globalAlpha = 1;
        ctx.globalCompositeOperation = 'source-over';

        if (this.particles.length > 0) {
            this.raf = requestAnimationFrame(() => this._loop());
        } else {
            this.running = false;
            this.raf = null;
        }
    }
}

export default CardParticles;