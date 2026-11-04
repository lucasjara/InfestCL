export const MAZE_SIZE = 15;

export function generateMaze(size = MAZE_SIZE, random = Math.random) {
    const safeSize = size >= 5 && size % 2 === 1 ? size : MAZE_SIZE;
    const grid = Array.from({ length: safeSize }, () => (
        Array(safeSize).fill(1)
    ));
    const directions = [
        [-2, 0],
        [2, 0],
        [0, -2],
        [0, 2],
    ];

    function visit(row, column) {
        grid[row][column] = 0;
        const shuffled = [...directions].sort(() => random() - 0.5);

        shuffled.forEach(([rowOffset, columnOffset]) => {
            const nextRow = row + rowOffset;
            const nextColumn = column + columnOffset;
            if (
                nextRow > 0
                && nextRow < safeSize - 1
                && nextColumn > 0
                && nextColumn < safeSize - 1
                && grid[nextRow][nextColumn] === 1
            ) {
                grid[row + rowOffset / 2][column + columnOffset / 2] = 0;
                visit(nextRow, nextColumn);
            }
        });
    }

    visit(1, 1);
    grid[safeSize - 2][safeSize - 2] = 0;
    return grid;
}

export function createMazeGame(size = MAZE_SIZE, random = Math.random) {
    return {
        size,
        grid: generateMaze(size, random),
        player: { row: 1, column: 1 },
        exit: { row: size - 2, column: size - 2 },
        finished: false,
        won: false,
    };
}

export function moveMazePlayer(game, rowOffset, columnOffset) {
    if (game.finished) {
        return false;
    }

    const nextRow = game.player.row + rowOffset;
    const nextColumn = game.player.column + columnOffset;
    if (
        nextRow < 0
        || nextRow >= game.size
        || nextColumn < 0
        || nextColumn >= game.size
        || game.grid[nextRow][nextColumn] === 1
    ) {
        return false;
    }

    game.player.row = nextRow;
    game.player.column = nextColumn;
    const won = nextRow === game.exit.row && nextColumn === game.exit.column;
    if (won) {
        game.finished = true;
        game.won = true;
    }
    return true;
}

