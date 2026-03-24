// =========================================================
// 1. SCROLL REVEAL (ANIMAÇÃO AO ROLAR)
// =========================================================
const observador = new IntersectionObserver((entradas) => {
    entradas.forEach(en => { if (en.isIntersecting) en.target.classList.add('mostrar'); });
});
document.querySelectorAll('.escondido').forEach(el => observador.observe(el));

// =========================================================
// 2. TROCA DE TEMA E PARTÍCULAS DE FUNDO
// =========================================================
const btnTema = document.getElementById('btn-tema');
const iconeTema = btnTema?.querySelector('i');

if (btnTema) {
    btnTema.addEventListener('click', () => {
        document.body.classList.toggle('tema-claro');
        const ehClaro = document.body.classList.contains('tema-claro');
        if (iconeTema) iconeTema.className = ehClaro ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
        initParticles(); 
    });
}

const canvasBg = document.getElementById('canvas-bg');
const ctxBg = canvasBg.getContext('2d');
let particulas = [];

function ajustarBg() { 
    canvasBg.width = window.innerWidth; 
    canvasBg.height = window.innerHeight; 
}
window.addEventListener('resize', ajustarBg);
ajustarBg();

class Particula {
    constructor() {
        this.x = Math.random() * canvasBg.width;
        this.y = Math.random() * canvasBg.height;
        this.tam = Math.random() * 2 + 1;
        this.velX = Math.random() * 1 - 0.5;
        this.velY = Math.random() * 1 - 0.5;
        this.atualizarCor();
    }
    atualizarCor() {
        const claro = document.body.classList.contains('tema-claro');
        this.cor = claro ? `rgba(100,100,100,${Math.random()})` : `rgba(255,255,255,${Math.random()})`;
    }
    desenhar() {
        ctxBg.fillStyle = this.cor;
        ctxBg.beginPath(); 
        ctxBg.arc(this.x, this.y, this.tam, 0, Math.PI*2); 
        ctxBg.fill();
    }
    update() {
        this.x += this.velX; this.y += this.velY;
        if (this.x > canvasBg.width || this.x < 0) this.velX *= -1;
        if (this.y > canvasBg.height || this.y < 0) this.velY *= -1;
    }
}

function initParticles() {
    particulas = [];
    for(let i=0; i<100; i++) particulas.push(new Particula());
}

function animarParticles() {
    ctxBg.clearRect(0, 0, canvasBg.width, canvasBg.height);
    particulas.forEach(p => { p.update(); p.desenhar(); });
    requestAnimationFrame(animarParticles);
}
initParticles(); 
animarParticles();

// =========================================================
// 3. JOGO JAVA JUMP (BUG HUNTER)
// =========================================================
const canvasGame = document.getElementById('gameCanvas');
if (canvasGame) {
    const ctxGame = canvasGame.getContext('2d');
    let score = 0;
    let gameActive = true;

    canvasGame.width = 450;
    canvasGame.height = 150;

    let player = { x: 50, y: 120, w: 20, h: 20, dy: 0, jumpForce: 8, gravity: 0.5, grounded: false };
    let obstacles = [];
    let gameSpeed = 3;

    function drawPlayer() {
        ctxGame.fillStyle = "#f89820"; 
        ctxGame.fillRect(player.x, player.y, player.w, player.h);
    }

    function drawObstacle(obs) {
        ctxGame.fillStyle = "#ff5f56"; 
        ctxGame.font = "20px Monospace";
        ctxGame.fillText(";", obs.x, obs.y + 15);
    }

    function updateGame() {
        if (!gameActive) return;

        ctxGame.clearRect(0, 0, canvasGame.width, canvasGame.height);

        player.dy += player.gravity;
        player.y += player.dy;

        if (player.y > 120) {
            player.y = 120;
            player.dy = 0;
            player.grounded = true;
        }

        if (Math.random() < 0.02) {
            obstacles.push({ x: canvasGame.width, y: 120, w: 10, h: 20 });
        }

        obstacles.forEach((obs, index) => {
            obs.x -= gameSpeed;
            drawObstacle(obs);

            if (player.x < obs.x + obs.w && player.x + player.w > obs.x &&
                player.y < obs.y + obs.h && player.y + player.h > obs.y) {
                gameOverFunc();
            }

            if (obs.x + obs.w < 0) {
                obstacles.splice(index, 1);
                score++;
                const scoreElement = document.getElementById('score');
                if (scoreElement) scoreElement.innerText = `Score: ${score}`;
            }
        });

        drawPlayer();
        requestAnimationFrame(updateGame);
    }

    function jumpGame() {
        if (!gameActive) {
            restartGameFunc();
            return;
        }
        if (player.grounded) {
            player.dy = -player.jumpForce;
            player.grounded = false;
        }
    }

    function gameOverFunc() {
        gameActive = false;
        const goScreen = document.getElementById('game-over');
        if (goScreen) goScreen.style.display = 'block';
    }

    function restartGameFunc() {
        score = 0;
        obstacles = [];
        gameActive = true;
        player.y = 120;
        const goScreen = document.getElementById('game-over');
        const scoreElement = document.getElementById('score');
        if (goScreen) goScreen.style.display = 'none';
        if (scoreElement) scoreElement.innerText = `Score: 0`;
        updateGame();
    }

    // Eventos do Jogo
    window.addEventListener('keydown', (e) => {
        if (e.code === 'Space') {
            e.preventDefault(); // Evita que a página role ao apertar espaço
            jumpGame();
        }
    });

    // Permitir pular clicando no canvas do jogo
    canvasGame.addEventListener('click', jumpGame);

    updateGame();
}