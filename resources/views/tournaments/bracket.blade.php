@extends('layouts.base')

@section('title', 'Cuadro del torneo | INFEST Chile')

@section('meta_description', 'Visualización del cuadro generado para el torneo.')

@section('navigation')
    <a href="{{ route('tournaments.create') }}">Nuevo torneo</a>
@endsection

@section('styles')
    .bracket-page {
        padding-block: clamp(2rem, 5vw, 4rem);
    }

    .bracket-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 1.75rem;
    }

    .bracket-kicker {
        margin: 0 0 .45rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .bracket-heading h1 {
        max-width: 760px;
        margin: 0;
        font-size: clamp(2.25rem, 7vw, 4.75rem);
        letter-spacing: -.07em;
        line-height: .95;
    }

    .new-tournament-button {
        flex: 0 0 auto;
        padding: .85rem 1.15rem;
        border-radius: 999px;
        background: var(--contrast-bg);
        color: var(--contrast-fg);
        font-size: .82rem;
        font-weight: 800;
        text-decoration: none;
    }

    .tournament-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        margin-bottom: 1.75rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .summary-item {
        min-width: 0;
        padding: 1rem 1.25rem;
        border-right: 1px solid var(--line);
    }

    .summary-item:last-child {
        border-right: 0;
    }

    .summary-item span {
        display: block;
        margin-bottom: .25rem;
        color: var(--muted);
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .summary-item strong {
        display: block;
        overflow: hidden;
        font-size: .95rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 230px;
        gap: 1.25rem;
        align-items: start;
    }

    .bracket-panel,
    .prizes-panel {
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .bracket-panel {
        overflow-x: auto;
    }

    .competition-column {
        min-width: 0;
    }

    .third-place-panel {
        margin-top: 1rem;
        padding: 1rem;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
    }

    .third-place-panel h2 {
        margin: 0 0 .75rem;
        font-size: .9rem;
    }

    .third-place-candidates {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
    }

    .third-place-candidate {
        display: flex;
        min-height: 48px;
        align-items: center;
        gap: .55rem;
        padding: .55rem .65rem;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
        font-size: .8rem;
        font-weight: 750;
    }

    .third-place-candidate.is-winner {
        border-color: var(--violet);
    }

    .bracket {
        display: flex;
        min-width: max-content;
        gap: 1.25rem;
        padding: 1.25rem;
    }

    .round-column {
        width: 210px;
    }

    .round-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 38px;
        margin-bottom: .75rem;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .round-heading span:last-child {
        display: grid;
        width: 24px;
        height: 24px;
        place-items: center;
        border-radius: 50%;
        background: var(--paper);
        font-size: .68rem;
    }

    .matches {
        display: flex;
        min-height: 500px;
        flex-direction: column;
        justify-content: space-around;
        gap: 1rem;
    }

    .match-card {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
    }

    .match-card::before {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 3px;
        background: var(--violet);
        content: "";
        opacity: .75;
    }

    .participant-slot {
        display: flex;
        min-height: 48px;
        align-items: center;
        gap: .55rem;
        padding: .55rem .65rem;
        font-size: .8rem;
        font-weight: 750;
    }

    .participant-slot + .participant-slot {
        border-top: 1px solid var(--line);
    }

    .participant-slot.is-empty {
        color: var(--muted);
        font-weight: 550;
    }

    .participant-slot.is-winner {
        background: var(--surface);
    }

    .slot-participant-name {
        min-width: 0;
        flex: 1;
    }

    .winner-action {
        width: 28px;
        height: 28px;
        flex: 0 0 auto;
        display: grid;
        place-items: center;
        padding: 0;
        border: 1px solid var(--line);
        border-radius: 50%;
        background: transparent;
        color: var(--muted);
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        transition: background .15s ease, color .15s ease, border-color .15s ease;
    }

    .winner-action:hover {
        border-color: var(--violet);
        color: var(--violet);
    }

    .participant-slot.is-winner .winner-action {
        border-color: var(--violet);
        background: var(--violet);
        color: var(--contrast-bg);
    }

    .slot-index {
        display: grid;
        width: 22px;
        height: 22px;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 50%;
        background: var(--surface);
        border: 1px solid var(--line);
        font-size: .62rem;
    }

    .prizes-panel {
        padding: 1rem;
    }

    .prizes-panel h2 {
        margin: 0 0 .75rem;
        font-size: .9rem;
    }

    .prize-card {
        margin-top: .65rem;
        padding: .85rem;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--paper);
    }

    .prize-place {
        color: var(--muted);
        font-size: .68rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .prize-card strong {
        display: block;
        margin-top: .2rem;
        font-size: .88rem;
    }

    .prize-card p {
        margin: .35rem 0 0;
        color: var(--muted);
        font-size: .76rem;
        line-height: 1.45;
    }

    .prize-winner {
        margin-top: .75rem;
        padding-top: .75rem;
        border-top: 1px solid var(--line);
        color: var(--violet);
        font-size: .78rem;
        font-weight: 850;
    }

    .third-place {
        margin-top: 1rem;
        padding: 1rem;
        border: 1px dashed var(--line);
        border-radius: 12px;
        text-align: center;
    }

    .empty-state {
        max-width: 520px;
        margin-inline: auto;
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state h1 {
        margin-bottom: .5rem;
    }

    .empty-state p {
        color: var(--muted);
    }

    @media (max-width: 900px) {
        .tournament-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .summary-item:nth-child(2) {
            border-right: 0;
        }

        .summary-item:nth-child(-n + 2) {
            border-bottom: 1px solid var(--line);
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .bracket-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .tournament-summary {
            grid-template-columns: 1fr;
        }

        .summary-item,
        .summary-item:nth-child(2) {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }

        .summary-item:last-child {
            border-bottom: 0;
        }

        .round-column {
            width: 190px;
        }

        .third-place-candidates {
            grid-template-columns: 1fr;
        }
    }
@endsection

@section('content')
    <div class="shell bracket-page" id="bracket-page">
        <div class="empty-state" id="empty-state">
            <h1>Sin datos del torneo</h1>
            <p>Genera un torneo desde el formulario para mostrar su cuadro.</p>
            <a class="new-tournament-button" href="{{ route('tournaments.create') }}">Crear torneo</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const page = document.querySelector('#bracket-page');

        let tournament;

        try {
            tournament = JSON.parse(
                localStorage.getItem('infest-tournament')
                || sessionStorage.getItem('infest-tournament')
            );
        } catch (error) {
            tournament = null;
        }

        if (!tournament) {
            // The empty state remains visible.
        } else {
            const moneyFormatter = new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: tournament.prizes?.find((prize) => prize.currency)?.currency || 'CLP',
                maximumFractionDigits: 0,
            });

            function escapeHtml(value) {
                const element = document.createElement('div');
                element.textContent = value ?? '';
                return element.innerHTML;
            }

            function prizeLabel(prize) {
                if (prize.type === 'item') return escapeHtml(prize.name || prize.description || 'Objeto');
                if (prize.amount !== undefined && prize.amount !== null && prize.amount !== '') {
                    return moneyFormatter.format(Number(prize.amount));
                }
                return escapeHtml(prize.name || prize.description || 'Premio');
            }

            const rounds = JSON.parse(JSON.stringify(tournament.bracket?.rounds || []));
            const prizes = tournament.prizes || [];
            let thirdPlaceWinner = null;

            function sameParticipant(first, second) {
                return first?.id !== undefined
                    && second?.id !== undefined
                    && String(first.id) === String(second.id);
            }

            function findNextMatch(match) {
                if (!match.next_match_id) return null;

                for (const round of rounds) {
                    const nextMatch = round.matches.find((candidate) => candidate.id === match.next_match_id);
                    if (nextMatch) return nextMatch;
                }

                return null;
            }

            function removeParticipantFromNext(match, participant) {
                const nextMatch = findNextMatch(match);
                if (!nextMatch) return;

                const nextSlot = match.next_slot === 2 ? 'participant2' : 'participant1';
                if (sameParticipant(nextMatch[nextSlot], participant)) {
                    nextMatch[nextSlot] = null;
                }

                if (sameParticipant(nextMatch.winner, participant)) {
                    nextMatch.winner = null;
                    removeParticipantFromNext(nextMatch, participant);
                }
            }

            function cancelWinner(roundIndex, matchIndex, participant, slotKey) {
                const match = rounds[roundIndex].matches[matchIndex];

                if (!sameParticipant(match.winner, participant)) return;

                match.winner = null;
                removeParticipantFromNext(match, participant);
                renderBracket();
            }

            function selectWinner(roundIndex, matchIndex, participant, slotKey) {
                const match = rounds[roundIndex].matches[matchIndex];

                if (sameParticipant(match.winner, participant)) {
                    cancelWinner(roundIndex, matchIndex, participant, slotKey);
                    return;
                }

                if (match.winner) {
                    removeParticipantFromNext(match, match.winner);
                }

                match.winner = participant;
                const nextMatch = findNextMatch(match);

                if (nextMatch) {
                    const nextSlot = match.next_slot === 2 ? 'participant2' : 'participant1';
                    nextMatch[nextSlot] = participant;
                    if (nextMatch.winner && !sameParticipant(nextMatch.winner, participant)) {
                        nextMatch.winner = null;
                    }
                }

                renderBracket();
            }

            function renderMatch(match, roundIndex, matchIndex) {
                const winnerId = match.winner?.id;
                const firstClass = winnerId && match.participant1?.id === winnerId ? ' is-winner' : '';
                const secondClass = winnerId && match.participant2?.id === winnerId ? ' is-winner' : '';

                function slotHtml(participant, slotKey, slotIndex, winnerClass) {
                    const isSelected = sameParticipant(match.winner, participant);
                    const action = participant ? `
                        <button
                            type="button"
                            class="winner-action"
                            data-winner-action
                            data-round="${roundIndex}"
                            data-match="${matchIndex}"
                            data-slot="${slotKey}"
                            title="${isSelected ? 'Eliminar selección de ganador' : 'Agregar como ganador'}"
                            aria-label="${isSelected ? 'Eliminar selección de ganador' : 'Agregar como ganador'}"
                        >${isSelected ? '−' : '+'}</button>
                    ` : '';

                    return `
                        <div class="participant-slot${winnerClass}${!participant ? ' is-empty' : ''}">
                            <span class="slot-index">${slotIndex}</span>
                            <span class="slot-participant-name">${participant ? escapeHtml(participant.name) : 'Por definir / BYE'}</span>
                            ${action}
                        </div>
                    `;
                }

                return `
                    <article
                        class="match-card"
                        data-match-id="${escapeHtml(match.id)}"
                        data-round-index="${roundIndex}"
                        data-match-index="${matchIndex}"
                    >
                        ${slotHtml(match.participant1, 'participant1', 1, firstClass)}
                        ${slotHtml(match.participant2, 'participant2', 2, secondClass)}
                    </article>
                `;
            }

            function getVisibleRounds() {
                return rounds.map((round, roundIndex) => ({
                    ...round,
                    roundIndex,
                    matches: round.matches.map((match, matchIndex) => ({
                        match,
                        matchIndex,
                    })).filter(({ match }) =>
                        match.status !== 'empty'
                        && (
                            match.participant1 != null
                            || match.participant2 != null
                        )
                    ),
                })).filter((round) => round.matches.length > 0);
            }

            function getChampion() {
                const finalRound = rounds.find((round) =>
                    round.name === 'Final'
                    || round.round === rounds.length
                );

                if (!finalRound) return null;

                const finalMatch = finalRound.matches.find((match) => match.winner);
                return finalMatch?.winner || null;
            }

            function getFinalMatch() {
                return rounds.find((round) =>
                    round.name === 'Final'
                    || round.round === rounds.length
                )?.matches.find((match) => match.winner) || null;
            }

            function getRunnerUp() {
                const finalMatch = getFinalMatch();
                const champion = finalMatch?.winner || null;
                if (!finalMatch || !champion) return null;

                if (sameParticipant(finalMatch.participant1, champion)) {
                    return finalMatch.participant2 || null;
                }

                return finalMatch.participant1 || null;
            }

            function getThirdPlaceCandidates() {
                const semifinalRound = rounds.find((round) =>
                    round.name === 'Semifinals'
                    || (round.matches.length > 0 && round.round === rounds.length - 1)
                );

                if (!semifinalRound) return [];

                return semifinalRound.matches
                    .filter((match) => match.winner)
                    .map((match) => {
                        if (sameParticipant(match.participant1, match.winner)) {
                            return match.participant2;
                        }
                        return match.participant1;
                    })
                    .filter((participant) => participant != null);
            }

            function alignLaterRounds() {
                const columns = [...page.querySelectorAll('.round-column')];
                if (columns.length < 2) return;

                columns.forEach((column) => {
                    column.querySelectorAll('.match-card').forEach((card) => {
                        card.style.transform = '';
                    });
                });

                for (let columnIndex = 1; columnIndex < columns.length; columnIndex++) {
                    const previousColumn = columns[columnIndex - 1];
                    const currentColumn = columns[columnIndex];

                    currentColumn.querySelectorAll('.match-card').forEach((card) => {
                        const matchIndex = Number(card.dataset.matchIndex);
                        const sourceIndexes = [matchIndex * 2, matchIndex * 2 + 1];
                        const sourceCards = sourceIndexes
                            .map((sourceIndex) => previousColumn.querySelector(
                                `[data-match-index="${sourceIndex}"]`
                            ))
                            .filter((sourceCard) => sourceCard !== null);

                        if (sourceCards.length === 0) return;

                        const cardCenter = card.getBoundingClientRect().top
                            + card.getBoundingClientRect().height / 2;
                        const sourceCenter = sourceCards.reduce((total, sourceCard) => {
                            const bounds = sourceCard.getBoundingClientRect();
                            return total + bounds.top + bounds.height / 2;
                        }, 0) / sourceCards.length;

                        const offset = sourceCenter - cardCenter;
                        if (Math.abs(offset) > .5) {
                            card.style.transform = `translateY(${offset}px)`;
                        }
                    });
                }
            }

            const summary = `
                <div class="tournament-summary">
                    <div class="summary-item"><span>Juego</span><strong>${escapeHtml(tournament.game)}</strong></div>
                    <div class="summary-item"><span>Formato</span><strong>Eliminación simple</strong></div>
                    <div class="summary-item"><span>Participantes</span><strong>${escapeHtml(tournament.participants.length)} / ${escapeHtml(tournament.max_participants)}</strong></div>
                    <div class="summary-item"><span>Cuadro</span><strong>${escapeHtml(tournament.bracket?.size || tournament.participants.length)} plazas</strong></div>
                </div>
            `;

            function renderBracket() {
                const visibleRounds = getVisibleRounds();
                const champion = getChampion();
                const runnerUp = getRunnerUp();
                const thirdPlaceCandidates = getThirdPlaceCandidates();
                const hasThirdPrize = prizes.some((prize) => String(prize.place) === '3');

                if (
                    thirdPlaceWinner
                    && !thirdPlaceCandidates.some((candidate) =>
                        sameParticipant(candidate, thirdPlaceWinner)
                    )
                ) {
                    thirdPlaceWinner = null;
                }

                const bracketColumns = visibleRounds.map((round) => `
                    <section class="round-column" aria-label="${escapeHtml(round.name)}">
                        <div class="round-heading">
                            <span>${escapeHtml(round.name)}</span>
                            <span>${round.matches.length}</span>
                        </div>
                        <div class="matches">
                            ${round.matches.map(({ match, matchIndex }) =>
                                renderMatch(match, round.roundIndex, matchIndex)
                            ).join('')}
                        </div>
                    </section>
                `).join('');

            const prizesHtml = prizes.length ? `
                <aside class="prizes-panel">
                    <h2>Premios</h2>
                    ${prizes.map((prize) => `
                        <article class="prize-card">
                            <span class="prize-place">${escapeHtml(prize.place)}° lugar</span>
                            <strong>${prizeLabel(prize)}</strong>
                            ${prize.description ? `<p>${escapeHtml(prize.description)}</p>` : ''}
                            ${String(prize.place) === '1' && champion ? `
                                <div class="prize-winner">Ganador: ${escapeHtml(champion.name)}</div>
                            ` : ''}
                            ${String(prize.place) === '2' && runnerUp ? `
                                <div class="prize-winner">Segundo lugar: ${escapeHtml(runnerUp.name)}</div>
                            ` : ''}
                            ${String(prize.place) === '3' && thirdPlaceWinner ? `
                                <div class="prize-winner">Tercer lugar: ${escapeHtml(thirdPlaceWinner.name)}</div>
                            ` : ''}
                        </article>
                    `).join('')}
                    ${tournament.third_place_match ? `
                        <div class="third-place">
                            <span class="prize-place">Incluye partido por el tercer lugar</span>
                        </div>
                    ` : ''}
                </aside>
            ` : '';

                const thirdPlaceHtml = hasThirdPrize ? `
                    <section class="third-place-panel" aria-label="Partido por el tercer lugar">
                        <h2>Resolver tercer lugar</h2>
                        <div class="third-place-candidates">
                            ${thirdPlaceCandidates.length ? thirdPlaceCandidates.map((candidate) => {
                                const isWinner = sameParticipant(thirdPlaceWinner, candidate);
                                return `
                                    <div class="third-place-candidate${isWinner ? ' is-winner' : ''}">
                                        <span class="slot-participant-name">${escapeHtml(candidate.name)}</span>
                                        <button
                                            type="button"
                                            class="winner-action"
                                            data-third-place-action
                                            data-participant-id="${escapeHtml(candidate.id)}"
                                            title="${isWinner ? 'Eliminar selección de tercer lugar' : 'Asignar tercer lugar'}"
                                            aria-label="${isWinner ? 'Eliminar selección de tercer lugar' : 'Asignar tercer lugar'}"
                                        >${isWinner ? '−' : '+'}</button>
                                    </div>
                                `;
                            }).join('') : `
                                <p class="slot-participant-name">Selecciona los ganadores de las semifinales para definir candidatos.</p>
                            `}
                        </div>
                    </section>
                ` : '';

                page.innerHTML = `
                    <div class="bracket-heading">
                        <div>
                            <p class="bracket-kicker">Cuadro generado</p>
                            <h1>${escapeHtml(tournament.name)}</h1>
                        </div>
                        <a class="new-tournament-button" href="{{ route('tournaments.create') }}">Nuevo torneo</a>
                    </div>
                    ${summary}
                    <div class="content-grid">
                        <div class="competition-column">
                            <div class="bracket-panel">
                                <div class="bracket">${bracketColumns}</div>
                            </div>
                            ${thirdPlaceHtml}
                        </div>
                        ${prizesHtml}
                    </div>
                `;

                requestAnimationFrame(alignLaterRounds);
            }

            page.addEventListener('click', (event) => {
                const thirdPlaceButton = event.target.closest('[data-third-place-action]');
                if (thirdPlaceButton) {
                    const participantId = String(thirdPlaceButton.dataset.participantId);
                    const candidate = getThirdPlaceCandidates().find((participant) =>
                        String(participant.id) === participantId
                    );

                    if (!candidate) return;

                    thirdPlaceWinner = sameParticipant(thirdPlaceWinner, candidate) ? null : candidate;
                    renderBracket();
                    return;
                }

                const button = event.target.closest('[data-winner-action]');
                if (!button) return;

                const roundIndex = Number(button.dataset.round);
                const matchIndex = Number(button.dataset.match);
                const slotKey = button.dataset.slot;
                const match = rounds[roundIndex]?.matches[matchIndex];
                const participant = match?.[slotKey];

                if (!participant) return;

                selectWinner(roundIndex, matchIndex, participant, slotKey);
            });

            window.addEventListener('resize', () => {
                requestAnimationFrame(alignLaterRounds);
            });

            renderBracket();
        }
    </script>
@endpush
