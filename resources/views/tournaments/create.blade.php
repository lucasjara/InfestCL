@extends('layouts.base')

@section('title', 'Crear torneo | INFEST Chile')

@section('meta_description', 'Genera el cuadro de eliminación directa de un torneo.')

@section('navigation')
    <a href="{{ url('/') }}">Inicio</a>
@endsection

@section('styles')
    .tournament-page {
        padding-block: clamp(2.5rem, 6vw, 5rem);
    }

    .tournament-form {
        max-width: 860px;
        margin-inline: auto;
    }

    .page-heading {
        margin-bottom: 2rem;
    }

    .page-heading p {
        margin: 0 0 .5rem;
        color: var(--muted);
        font-size: .75rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .page-heading h1 {
        margin: 0;
        font-size: clamp(2.25rem, 7vw, 4.5rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .form-card {
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 24px;
        background: var(--surface);
    }

    .form-section {
        padding: clamp(1.25rem, 4vw, 2rem);
        border-bottom: 1px solid var(--line);
    }

    .form-section:last-of-type {
        border-bottom: 0;
    }

    .form-section h2 {
        margin: 0 0 1.25rem;
        font-size: 1rem;
        letter-spacing: -.02em;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .field {
        min-width: 0;
    }

    .field-full {
        grid-column: 1 / -1;
    }

    .field label,
    .check-field {
        display: block;
        margin-bottom: .5rem;
        font-size: .78rem;
        font-weight: 750;
    }

    .field input,
    .field select,
    .field textarea {
        width: 100%;
        min-height: 46px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
        color: var(--ink);
        font: inherit;
        padding: .75rem .85rem;
    }

    .field textarea {
        min-height: 90px;
        resize: vertical;
    }

    .field input[readonly],
    .field select:disabled {
        background: var(--surface);
        color: var(--muted);
        cursor: not-allowed;
        opacity: .9;
    }

    .repeat-list {
        display: grid;
        gap: .75rem;
    }

    .repeat-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr)) auto;
        align-items: end;
        gap: .75rem;
    }

    .prize-row {
        grid-template-columns: minmax(70px, .55fr) minmax(110px, 1fr) minmax(110px, 1fr) minmax(90px, .75fr) minmax(120px, 1fr) minmax(90px, .75fr) 46px;
    }

    .prize-row .field-description {
        grid-column: 1 / -1;
    }

    .prize-row .field-description textarea {
        min-height: 72px;
        resize: vertical;
    }

    .field input[readonly],
    .field select:disabled {
        background: var(--surface);
        color: var(--muted);
        cursor: not-allowed;
        opacity: .9;
    }

    .icon-button,
    .add-button,
    .submit-button {
        min-height: 46px;
        border-radius: 999px;
        font: inherit;
        font-weight: 800;
        cursor: pointer;
    }

    .add-button,
    .submit-button {
        border: 0;
        background: var(--contrast-bg);
        color: var(--contrast-fg);
    }

    .add-button {
        margin-top: .75rem;
        padding-inline: 1rem;
    }

    .add-button:disabled,
    .icon-button:disabled {
        cursor: not-allowed;
        opacity: .45;
    }

    .icon-button {
        width: 46px;
        border: 1px solid var(--line);
        background: transparent;
        color: var(--ink);
    }

    .check-field {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin: 0;
    }

    .third-place-field {
        align-self: center;
    }

    .settings-grid {
        align-items: end;
    }

    .settings-grid .third-place-field {
        min-height: 46px;
        display: flex;
        align-items: center;
    }

    .check-field input {
        width: 18px;
        height: 18px;
        accent-color: var(--violet);
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
        padding: clamp(1.25rem, 4vw, 2rem);
    }

    .submit-button {
        min-width: 180px;
        padding-inline: 1.5rem;
    }

    .submit-button:disabled {
        cursor: wait;
        opacity: .65;
    }

    .form-status {
        display: none;
        margin-right: auto;
        color: var(--muted);
        font-size: .82rem;
        line-height: 1.4;
    }

    .form-status.is-visible {
        display: block;
    }

    .form-status.is-error {
        color: #b42318;
    }

    .result-card {
        display: none;
        margin-top: 1.25rem;
        padding: 1.25rem;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .result-card.is-visible {
        display: block;
    }

    .result-card h2 {
        margin-top: 0;
    }

    .result-card pre {
        max-height: 420px;
        overflow: auto;
        padding: 1rem;
        border-radius: 12px;
        background: var(--paper);
        font-size: .78rem;
        white-space: pre-wrap;
    }

    @media (max-width: 700px) {
        .form-grid,
        .repeat-row {
            grid-template-columns: 1fr;
        }

        .prize-row {
            grid-template-columns: repeat(2, minmax(0, 1fr)) auto;
        }

        .prize-row .field-description {
            grid-column: 1 / -1;
        }

        .field-full {
            grid-column: auto;
        }

        .icon-button {
            justify-self: end;
        }
    }
@endsection

@section('content')
    <div class="shell tournament-page">
        <form class="tournament-form" id="tournament-form" novalidate>
            <div class="page-heading">
                <p>INFEST Tournament</p>
                <h1>Crear torneo</h1>
            </div>

            <div class="form-card">
                <section class="form-section" aria-labelledby="tournament-details">
                    <h2 id="tournament-details">Datos del torneo</h2>
                    <div class="form-grid">
                        <div class="field">
                            <label for="name">Nombre</label>
                            <input id="name" name="name" type="text" maxlength="255" required value="{{ old('name', 'INFEST Gaming Cup') }}">
                        </div>
                        <div class="field">
                            <label for="game">Juego</label>
                            <input id="game" name="game" type="text" maxlength="255" required placeholder="Ej. Tekken 8">
                        </div>
                        <div class="field">
                            <label for="format">Formato</label>
                            <select id="format" name="format" required disabled>
                                <option value="single_elimination">Eliminación simple</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="max_participants">Máximo de participantes</label>
                            <input id="max_participants" name="max_participants" type="number" min="2" max="32" value="8" required>
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="tournament-participants">
                    <h2 id="tournament-participants">Participantes</h2>
                    <div class="repeat-list" id="participants-list">
                        <div class="repeat-row participant-row" data-repeat-row>
                            <div class="field">
                                <label>ID</label>
                                <input name="participant_id[]" type="text" value="1" readonly required>
                            </div>
                            <div class="field">
                                <label>Nombre</label>
                                <input name="participant_name[]" type="text" maxlength="255" required placeholder="Nombre del participante">
                            </div>
                            <button class="icon-button remove-row" type="button" aria-label="Eliminar participante">×</button>
                        </div>
                    </div>
                    <button class="add-button" type="button" data-add="participant">+ Agregar participante</button>
                </section>

                <section class="form-section" aria-labelledby="tournament-prizes">
                    <h2 id="tournament-prizes">Premios</h2>
                    <div class="repeat-list" id="prizes-list">
                        <div class="repeat-row prize-row" data-repeat-row>
                            <div class="field">
                                <label>Lugar</label>
                                <input name="prize_place[]" type="number" value="1" readonly required>
                            </div>
                            <div class="field">
                                <label>Tipo</label>
                                <select name="prize_type[]">
                                    <option value="money">Dinero</option>
                                    <option value="item">Objeto</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Valor Monetario</label>
                                <input name="prize_amount[]" type="text" inputmode="numeric" data-thousands placeholder="100.000">
                            </div>
                            <div class="field">
                                <label>Moneda</label>
                                <input name="prize_currency[]" type="text" value="CLP" readonly required>
                            </div>
                            <div class="field">
                                <label>Nombre</label>
                                <input name="prize_name[]" type="text" maxlength="255" placeholder="Trofeo">
                            </div>
                            <div class="field">
                                <label>Cantidad</label>
                                <input name="prize_quantity[]" type="number" min="1" value="1">
                            </div>
                            <button class="icon-button remove-row prize-remove" type="button" aria-label="Eliminar premio" disabled>×</button>
                            <div class="field field-description">
                                <label>Descripción</label>
                                <textarea name="prize_description[]" maxlength="255" placeholder="Primer lugar"></textarea>
                            </div>
                        </div>
                    </div>
                    <button class="add-button" type="button" data-add="prize">+ Agregar premio</button>
                </section>

                <section class="form-section" aria-labelledby="tournament-settings">
                    <h2 id="tournament-settings">Configuración</h2>
                    <div class="form-grid settings-grid">
                        <label for="seeding">Orden inicial:</label>
                        <br>
                        <div class="field">
                            <select id="seeding" name="seeding" >
                                <option value="random">Aleatorio</option>
                                <option value="seeded">Según el orden ingresado</option>
                            </select>
                        </div>
                        <div class="field third-place-field">
                            <span class="check-field">
                                <input id="third_place_match" name="third_place_match" type="checkbox" value="1">
                                <label for="third_place_match">Incluir partido por el tercer lugar</label>
                            </span>
                        </div>
                    </div>
                </section>

                <div class="form-actions">
                    <p class="form-status" id="form-status" role="status" aria-live="polite"></p>
                    <button class="submit-button" type="submit">Generar torneo</button>
                </div>
            </div>
        </form>

        <section class="result-card" id="result-card" aria-labelledby="result-title">
            <h2 id="result-title">Resultado generado</h2>
            <pre id="tournament-result"></pre>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.querySelector('#tournament-form');
        const status = document.querySelector('#form-status');
        const submitButton = form.querySelector('.submit-button');
        const resultCard = document.querySelector('#result-card');
        const resultOutput = document.querySelector('#tournament-result');

        document.querySelector('[data-add="participant"]').addEventListener('click', () => {
            const index = document.querySelectorAll('.participant-row').length + 1;
            const row = document.createElement('div');
            row.className = 'repeat-row participant-row';
            row.dataset.repeatRow = '';
            row.innerHTML = `
                <div class="field">
                    <label>ID</label>
                    <input name="participant_id[]" type="text" value="${index}" readonly required>
                </div>
                <div class="field">
                    <label>Nombre</label>
                    <input name="participant_name[]" type="text" maxlength="255" required placeholder="Nombre del participante">
                </div>
                <button class="icon-button remove-row" type="button" aria-label="Eliminar participante">×</button>
            `;
            document.querySelector('#participants-list').appendChild(row);
        });

        document.querySelector('[data-add="prize"]').addEventListener('click', () => {
            const list = document.querySelector('#prizes-list');
            if (list.children.length >= 3) return;

            const place = list.children.length + 1;
            const row = document.createElement('div');
            row.className = 'repeat-row prize-row';
            row.dataset.repeatRow = '';
            row.innerHTML = `
                <div class="field"><label>Lugar</label><input name="prize_place[]" type="number" value="${place}" readonly required></div>
                <div class="field">
                    <label>Tipo</label>
                    <select name="prize_type[]">
                        <option value="money">Dinero</option>
                        <option value="item">Objeto</option>
                    </select>
                </div>
                <div class="field"><label>Monto</label><input name="prize_amount[]" type="text" inputmode="numeric" data-thousands placeholder="100.000"></div>
                <div class="field"><label>Moneda</label><input name="prize_currency[]" type="text" value="CLP" readonly required></div>
                <div class="field"><label>Nombre</label><input name="prize_name[]" type="text" maxlength="255"></div>
                <div class="field"><label>Cantidad</label><input name="prize_quantity[]" type="number" min="1" value="1"></div>
                <button class="icon-button remove-row prize-remove" type="button" aria-label="Eliminar premio">×</button>
                <div class="field field-description"><label>Descripción</label><textarea name="prize_description[]" maxlength="255"></textarea></div>
            `;
            list.appendChild(row);
            updatePrizeControls();
        });

        function formatThousands(input) {
            const digits = input.value.replace(/\D/g, '');
            input.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        document.addEventListener('input', (event) => {
            if (event.target.matches('[name="prize_amount[]"][data-thousands]')) {
                formatThousands(event.target);
            }
        });

        function updatePrizeControls() {
            const rows = [...document.querySelectorAll('.prize-row')];
            rows.forEach((row, index) => {
                row.querySelector('[name="prize_place[]"]').value = String(index + 1);
                const removeButton = row.querySelector('.prize-remove');
                if (removeButton) removeButton.disabled = rows.length === 1;
            });

            document.querySelector('[data-add="prize"]').disabled = rows.length >= 3;
        }

        updatePrizeControls();

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.remove-row')) return;

            const list = event.target.closest('.repeat-list');
            const row = event.target.closest('[data-repeat-row]');
            if (list.id === 'participants-list' && list.children.length > 1) {
                row.remove();
                [...list.querySelectorAll('.participant-row')].forEach((participantRow, index) => {
                    participantRow.querySelector('[name="participant_id[]"]').value = String(index + 1);
                });
            }

            if (list.id === 'prizes-list' && list.children.length > 1) {
                row.remove();
                updatePrizeControls();
            }
        });

        function setStatus(message, isError = false) {
            status.textContent = message;
            status.className = `form-status is-visible${isError ? ' is-error' : ''}`;
        }

        function collectPrizes() {
            const rows = [...document.querySelectorAll('.prize-row')];

            return rows
                .map((row) => ({
                    place: row.querySelector('[name="prize_place[]"]').value,
                    type: row.querySelector('[name="prize_type[]"]').value,
                    description: row.querySelector('[name="prize_description[]"]').value,
                    amount: row.querySelector('[name="prize_amount[]"]').value,
                    currency: row.querySelector('[name="prize_currency[]"]').value,
                    name: row.querySelector('[name="prize_name[]"]').value,
                    quantity: row.querySelector('[name="prize_quantity[]"]').value,
                }))
                .filter((prize) => prize.place || prize.type || prize.description || prize.amount || prize.currency || prize.name || prize.quantity)
                .map((prize) => {
                    const cleaned = {};
                    if (prize.place) cleaned.place = Number(prize.place);
                    if (prize.type) cleaned.type = prize.type;
                    if (prize.description) cleaned.description = prize.description;
                    if (prize.amount !== '') cleaned.amount = Number(prize.amount.replace(/\./g, ''));
                    if (prize.currency) cleaned.currency = prize.currency;
                    if (prize.name) cleaned.name = prize.name;
                    if (prize.quantity !== '') cleaned.quantity = Number(prize.quantity);
                    return cleaned;
                });
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const participantIds = [...form.querySelectorAll('[name="participant_id[]"]')].map((input) => input.value.trim());
            const participantNames = [...form.querySelectorAll('[name="participant_name[]"]')].map((input) => input.value.trim());

            if (participantIds.length === 0 || participantIds.some((id) => !id) || participantNames.some((name) => !name)) {
                setStatus('Completa el ID y el nombre de cada participante.', true);
                return;
            }

            if (new Set(participantIds).size !== participantIds.length) {
                setStatus('Los identificadores de participantes deben ser únicos.', true);
                return;
            }

            const payload = {
                name: form.elements.name.value.trim(),
                game: form.elements.game.value.trim(),
                format: 'single_elimination',
                max_participants: Number(form.elements.max_participants.value),
                prizes: collectPrizes(),
                settings: {
                    seeding: form.elements.seeding.value,
                    third_place_match: form.elements.third_place_match.checked,
                },
                participants: participantIds.map((id, index) => ({
                    id,
                    name: participantNames[index],
                })),
            };

            submitButton.disabled = true;
            setStatus('Generando cuadro del torneo...');

            try {
                const response = await fetch('/api/tournaments', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'No fue posible generar el torneo.');
                }

                resultOutput.textContent = JSON.stringify(data, null, 2);
                resultCard.classList.add('is-visible');
                setStatus('Torneo generado correctamente.');
                resultCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (error) {
                setStatus(error.message, true);
            } finally {
                submitButton.disabled = false;
            }
        });
    </script>
@endpush
