document.querySelector("#letsPlayButton").addEventListener("click", e => {
  document.querySelector("#title").classList.remove("hidden");
  document.querySelector("#announcement").classList.add("hidden");
});

const canvas = document.getElementById('game');
const context = canvas.getContext('2d');
const grid = 64;
const numRows = 13;
const numCols = 15;

// create a new canvas and draw the soft wall image. then we can use this
// canvas to draw the images later on
const softWallCanvas = document.createElement('canvas');
const softWallCtx = softWallCanvas.getContext('2d');
softWallCanvas.width = softWallCanvas.height = grid;

softWallCtx.fillStyle = 'black';
softWallCtx.fillRect(0, 0, grid, grid);
softWallCtx.fillStyle = '#a9a9a9';

// 1st row brick
softWallCtx.fillRect(1, 1, grid - 2, 20);

// 2nd row bricks
softWallCtx.fillRect(0, 23, 20, 18);
softWallCtx.fillRect(22, 23, 42, 18);

// 3rd row bricks
softWallCtx.fillRect(0, 43, 42, 20);
softWallCtx.fillRect(44, 43, 20, 20);

// create a new canvas and draw the wall image. then we can use this
// canvas to draw the images later on
const wallCanvas = document.createElement('canvas');
const wallCtx = wallCanvas.getContext('2d');
wallCanvas.width = wallCanvas.height = grid;

wallCtx.fillStyle = 'black';
wallCtx.fillRect(0, 0, grid, grid);
wallCtx.fillStyle = 'white';
wallCtx.fillRect(0, 0, grid - 2, grid - 2);
wallCtx.fillStyle = '#a9a9a9';
wallCtx.fillRect(2, 2, grid - 4, grid - 4);

// create a mapping of object types
const types = {
  wall: '▉',
  softWall: 1,
  bomb: 2,
  powerUp: 3
};

// keep track of all entities
let entities = [];

// keep track of what is in every cell of the game using a 2d array. the
// template is used to note where walls are and where soft walls cannot spawn.
// '▉' represents a wall
// 'x' represents a cell that cannot have a soft wall (player start zone)
let cells = [];
const template = [
  ['▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉'],
  ['▉','x' , 'x',   ,    ,    ,    ,    ,    ,    ,   ,    ,'x' ,'x' ,'▉'],
  ['▉','x','▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉','x','▉'],
  ['▉','x',   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,'x','▉'],
  ['▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉'],
  ['▉',   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,'▉'],
  ['▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉'],
  ['▉',   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,'▉'],
  ['▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉'],
  ['▉','x',   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,   ,'x','▉'],
  ['▉','x','▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉',   ,'▉','x','▉'],
  ['▉','x','x',   ,   ,   ,   ,   ,   ,   ,   ,   ,'x','x','▉'],
  ['▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉','▉']
];

// populate the level with walls and soft walls
function generateLevel() {
  cells = [];

  for (let row = 0; row < numRows; row++) {
    cells[row] = [];

    for (let col = 0; col < numCols; col++) {

      // 90% chance cells will contain a soft wall
      if (!template[row][col] && Math.random() < 0.90) {
        cells[row][col] = types.softWall;
      }
      else if (template[row][col] === types.wall) {
        cells[row][col] = types.wall;
      }
    }
  }
}

// blow up a bomb and its surrounding tiles
function blowUpBomb(bomb) {

  // bomb has already exploded so don't blow up again
  if (!bomb.isAlive) return;

  bomb.isAlive = false;

  // remove bomb from grid
  cells[bomb.row][bomb.col] = null;

  // explode bomb outward by size
  const dirs = [{
    // up
    row: -1,
    col: 0
  }, {
    // down
    row: 1,
    col: 0
  }, {
    // left
    row: 0,
    col: -1
  }, {
    // right
    row: 0,
    col: 1
  }];
  dirs.forEach((dir) => {
    for (let i = 0; i < bomb.size; i++) {
      const row = bomb.row + dir.row * i;
      const col = bomb.col + dir.col * i;
      const cell = cells[row][col];

      // stop the explosion if it hit a wall
      if (cell === types.wall) {
        return;
      }

      // center of the explosion is the first iteration of the loop
      entities.push(new Explosion(row, col, dir, i === 0 ? true : false));
      cells[row][col] = null;

      // If player is in the current cell then unalive them
      this.getPlayer1 = player1;
      this.getPlayer2 = player2;
      if (this.getPlayer1.row == row && this.getPlayer1.col == col) {
        this.getPlayer1.isAlive = false;
      }
      if (this.getPlayer2.row == row && this.getPlayer2.col == col) {
        this.getPlayer2.isAlive = false;
      }

      // If power-up is in the current cell then destroy it
      entities.forEach((ent) => {
        if (ent.type == types.powerUp) {
          if (ent.row == row && ent.col == col) {
            ent.isAlive = false;
          }
        }
      });

      // bomb hit another bomb so blow that one up too
      if (cell === types.bomb) {

        // find the bomb that was hit by comparing positions
        const nextBomb = entities.find((entity) => {
          return (
            entity.type === types.bomb &&
            entity.row === row && entity.col === col
          );
        });
        blowUpBomb(nextBomb);
      }

      // stop the explosion if hit anything
      if (cell) {
        return;
      }
    }
  });
}

// bomb constructor function
function Bomb(row, col, size, owner) {
  this.row = row;
  this.col = col;
  this.radius = grid * 0.4;
  this.size = size;    // the size of the explosion
  this.owner = owner;  // which player placed this bomb
  this.isAlive = true;
  this.type = types.bomb;

  // bomb blows up after 3 seconds
  this.timer = 3000;

  // update the bomb each frame
  this.update = function(dt) {
    this.timer -= dt;

    // blow up bomb if timer is done
    if (this.timer <= 0) {
      return blowUpBomb(this);
    }

    // change the size of the bomb every half second. we can determine the size
    // by dividing by 500 (half a second) and taking the ceiling of the result.
    // then we can check if the result is even or odd and change the size
    const interval = Math.ceil(this.timer / 500);
    if (interval % 2 === 0) {
      this.radius = grid * 0.4;
    }
    else {
      this.radius = grid * 0.5;
    }
  };

  // render the bomb each frame
  this.render = function() {
    const x = (this.col + 0.5) * grid;
    const y = (this.row + 0.5) * grid;

    // draw bomb
    context.fillStyle = 'black';
    context.beginPath();
    context.arc(x, y, this.radius, 0, 2 * Math.PI);
    context.fill();

    // draw bomb fuse moving up and down with the bomb size
    const fuseY = (this.radius === grid * 0.5 ? grid * 0.15 : 0);
    context.strokeStyle = 'white';
    context.lineWidth = 5;
    context.beginPath();
    context.arc(
      (this.col + 0.75) * grid,
      (this.row + 0.25) * grid - fuseY,
      10, Math.PI, -Math.PI / 2
    );
    context.stroke();
  };
}

// explosion constructor function
function Explosion(row, col, dir, center) {
  this.row = row;
  this.col = col;
  this.dir = dir;
  this.isAlive = true;

  // show explosion for 0.3 seconds
  this.timer = 300;

  // update the explosion each frame
  this.update = function(dt) {
    this.timer -= dt;

    if (this.timer <=0) {
      this.isAlive = false;
    }


  };

  // render the explosion each frame
  this.render = function() {
    const x = this.col * grid;
    const y = this.row * grid;
    const horizontal = this.dir.col;
    const vertical = this.dir.row;

    // create a fire effect by stacking red, orange, and yellow on top of
    // each other using progressively smaller rectangles
    context.fillStyle = '#D72B16';  // red
    context.fillRect(x, y, grid, grid);

    context.fillStyle = '#F39642';  // orange

    // determine how to draw based on if it's vertical or horizontal
    // center draws both ways
    if (center || horizontal) {
      context.fillRect(x, y + 6, grid, grid - 12);
    }
    if (center || vertical) {
      context.fillRect(x + 6, y, grid - 12, grid);
    }

    context.fillStyle = '#FFE5A8';  // yellow

    if (center || horizontal) {
      context.fillRect(x, y + 12, grid, grid - 24);
    }
    if (center || vertical) {
      context.fillRect(x + 12, y, grid - 24, grid);
    }
  };
}

// Power up constructor
function PowerUp(row, col, power) {
  this.row = row;
  this.col = col;
  this.isAlive = true;
  this.power = power; 
  this.type = types.powerUp;
  this.getPlayer1 = player1;
  this.getPlayer2 = player2;

  PowerUpImage = new Image();
  PowerUpImage.src = 'src/' + this.power + '.png';

  // update the power up each frame
  this.update = function() {
    if ((this.getPlayer1.row == this.row && this.getPlayer1.col == this.col)) {
      this.isAlive = false;
      PlayerPowerUp(this.power, this.getPlayer1)
    }
    if ((this.getPlayer2.row == this.row && this.getPlayer2.col == this.col)) {
      this.isAlive = false;
      PlayerPowerUp(this.power, this.getPlayer2)
    }
    
  }

  this.render = function() {
    const x = this.col * grid;
    const y = this.row * grid;

    context.drawImage(PowerUpImage, x, y, 64, 64);
  };
}

function PlayerPowerUp(power, player) {
  this.getPlayer = player;

  switch(power) {
    case 3:
      this.getPlayer.numBombs += 1;
      break;
    case 4:
      this.getPlayer.bombSize += 1;
      break;
  }
}

// player 1 character (just a simple circle, for now)
const player1 = {
  isAlive: true,
  countDown: 5,
  row: 1,
  col: 1,
  numBombs: 1,
  bombSize: 2,
  radius: grid * 0.35,
  render() {
    const x = (this.col + 0.5) * grid;
    const y = (this.row + 0.5) * grid;

    context.save();
    context.fillStyle = 'white';
    context.beginPath();
    context.arc(x, y, this.radius, 0, 2 * Math.PI);
    context.fill();
  },
  dead() {
    const x = (this.col + 0.5) * grid;
    const y = (this.row + 0.5) * grid;

    context.save();
    context.strokeStyle = 'red';
    context.beginPath();
    context.moveTo((x - this.radius), (y - this.radius));
    context.lineTo((x + this.radius), (y + this.radius));
    context.moveTo((x + this.radius), (y - this.radius));
    context.lineTo((x - this.radius), (y + this.radius));
    context.stroke();
  }
}

// player 2 character (just a simple circle, for now)
const player2 = {
  isAlive: true,
  countDown: 5,
  row: 11,
  col: 13,
  numBombs: 1,
  bombSize: 2,
  radius: grid * 0.35,
  render() {
    const x = (this.col + 0.5) * grid;
    const y = (this.row + 0.5) * grid;

    context.save();
    context.fillStyle = 'white';
    context.beginPath();
    context.arc(x, y, this.radius, 0, 2 * Math.PI);
    context.fill();
  },
  dead() {
    const x = (this.col + 0.5) * grid;
    const y = (this.row + 0.5) * grid;

    context.save();
    context.strokeStyle = 'red';
    context.beginPath();
    context.moveTo((x - this.radius), (y - this.radius));
    context.lineTo((x + this.radius), (y + this.radius));
    context.moveTo((x + this.radius), (y - this.radius));
    context.lineTo((x - this.radius), (y + this.radius));
    context.stroke();
  }
}

// game loop
let last;
let dt;
function loop(timestamp) {
  requestAnimationFrame(loop);
  context.clearRect(0,0,canvas.width,canvas.height);

  // calculate the time difference since the last update. requestAnimationFrame
  // passes the current timestamp as a parameter to the loop
  if (!last) {
    last = timestamp;
  }
  dt = timestamp - last;
  last = timestamp;

  // update and render everything in the grid
  for (let row = 0; row < numRows; row++) {
    for (let col = 0; col < numCols; col++) {
      switch(cells[row][col]) {
        case types.wall:
          context.drawImage(wallCanvas, col * grid, row * grid);
          break;
        case types.softWall:
          context.drawImage(softWallCanvas, col * grid, row * grid);
          break;
      }
    }
  }

  // update and render all entities
  entities.forEach((entity) => {
    entity.update(dt);
    entity.render();
  });

  // remove dead entities
  entities = entities.filter((entity) => entity.isAlive);

  player1.render();
  player2.render();

  // Check for dead players
  if (player1.isAlive == false) {
    player1.countDown -= 1;
    if (player1.countDown == 1) {
      player1.dead();
    }
    else if (player1.countDown == 0) {
      alert("Player 2 wins!!!");
    }
    return;
  }
  if (player2.isAlive == false) {
    player2.countDown -= 1;
    if (player2.countDown == 1) {
      player2.dead();
    }
    else if (player2.countDown == 0) {
      alert("Player 1 wins!!!");
    }
    return;
  }
}

// listen to keyboard events to move the player1
document.addEventListener('keydown', function(e) {
  getPlayer = player1;
  let row = getPlayer.row;
  let col = getPlayer.col;

  // a key
  if (e.which === 65) {
    col--;
  }
  // g key
  else if (e.which === 71) {
    entities.push(new PowerUp(1, 1, 3));
  }
  // w key
  else if (e.which === 87) {
    row--;
  }
  // d key
  else if (e.which === 68) {
    col++;
  }
  // s key
  else if (e.which === 83) {
    row++;
  }
  // F key (bomb)
  else if (
    e.which === 70 && !cells[row][col] &&
    // count the number of bombs the player1 has placed
    entities.filter((entity) => {
      return entity.type === types.bomb && entity.owner === getPlayer
    }).length < getPlayer.numBombs
  ) {
    // place bomb
    const bomb = new Bomb(row, col, getPlayer.bombSize, getPlayer);
    entities.push(bomb);
    cells[row][col] = types.bomb;
  }

  // don't move the player1 if something is already at that position
  if (!cells[row][col]) {
    getPlayer.row = row;
    getPlayer.col = col;
  }
});

// listen to keyboard events to move the player2
document.addEventListener('keydown', function(e) {
  getPlayer = player2;
  let row = getPlayer.row;
  let col = getPlayer.col;

  // J key
  if (e.which === 74) {
    col--;
  }
  // I key
  else if (e.which === 73) {
    row--;
  }
  // L key
  else if (e.which === 76) {
    col++;
  }
  // K key
  else if (e.which === 75) {
    row++;
  }
  // ; key (bomb)
  else if (
    e.which === 186 && !cells[row][col] &&
    // count the number of bombs the player2 has placed
    entities.filter((entity) => {
      return entity.type === types.bomb && entity.owner === getPlayer
    }).length < getPlayer.numBombs
  ) {
    // place bomb
    const bomb = new Bomb(row, col, getPlayer.bombSize, getPlayer);
    entities.push(bomb);
    cells[row][col] = types.bomb;
  }

  // don't move the player2 if something is already at that position
  if (!cells[row][col]) {
    getPlayer.row = row;
    getPlayer.col = col;
  }
});

// start the game
generateLevel();
requestAnimationFrame(loop);