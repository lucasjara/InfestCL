import assert from 'node:assert/strict';
import test from 'node:test';

import {
    createMazeGame,
    generateMaze,
    moveMazePlayer,
    MAZE_SIZE,
} from './maze.js';

function findPath(grid, start, exit) {
    const pending = [start];
    const visited = new Set([`${start.row}:${start.column}`]);

    while (pending.length > 0) {
        const current = pending.shift();
        if (current.row === exit.row && current.column === exit.column) {
            return true;
        }

        [
            [-1, 0],
            [1, 0],
            [0, -1],
            [0, 1],
        ].forEach(([rowOffset, columnOffset]) => {
            const row = current.row + rowOffset;
            const column = current.column + columnOffset;
            const key = `${row}:${column}`;
            if (
                grid[row]?.[column] === 0
                && !visited.has(key)
            ) {
                visited.add(key);
                pending.push({ row, column });
            }
        });
    }

    return false;
}

test('maze generation creates a 15 by 15 grid with a valid path', () => {
    const grid = generateMaze();
    const exit = { row: MAZE_SIZE - 2, column: MAZE_SIZE - 2 };

    assert.equal(grid.length, 15);
    assert.equal(grid.every((row) => row.length === 15), true);
    assert.equal(grid[1][1], 0);
    assert.equal(grid[exit.row][exit.column], 0);
    assert.equal(findPath(grid, { row: 1, column: 1 }, exit), true);
});

test('maze movement blocks walls and reaches victory at the exit', () => {
    const game = createMazeGame();
    const originalRow = game.player.row;
    const originalColumn = game.player.column;

    assert.equal(moveMazePlayer(game, -1, 0), false);
    assert.equal(game.player.row, originalRow);
    assert.equal(game.player.column, originalColumn);

    game.player.row = game.exit.row;
    game.player.column = game.exit.column - 1;
    game.grid[game.exit.row][game.exit.column] = 0;
    assert.equal(moveMazePlayer(game, 0, 1), true);
    assert.equal(game.won, true);
});
