document.addEventListener("DOMContentLoaded", () => {
//estrelas no header
const observer = new IntersectionObserver(entries => {
entries.forEach(entry => {
if(entry.isIntersecting){
entry.target.classList.add("show");
}
});
},{threshold:0.2});

document.querySelectorAll(".fade").forEach(el => observer.observe(el));


const starsContainer = document.querySelector(".stars");

if(starsContainer){

const totalStars = 80;

for(let i=0;i<totalStars;i++){

const star=document.createElement("div");
star.classList.add("star");

star.style.top=Math.random()*100+"%";
star.style.left=Math.random()*100+"%";

const size=Math.random()*3+1;
star.style.width=size+"px";
star.style.height=size+"px";

star.style.animationDuration=(Math.random()*3+1)+"s";

starsContainer.appendChild(star);

}

}

});

//comentarios no header tocando em modo aliatorio 

const faders = document.querySelectorAll(".fade");

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.2 });

faders.forEach(fader => observer.observe(fader));

document.addEventListener("DOMContentLoaded", () => {
  const comentarios = [
    "Bem-vindo ao Estrela Rastreamento!",
    "Proteção total para seu veículo 24h!",
    "Monitoramento em tempo real e confiável.",
    "Cada veículo é uma estrela!",
    "Segurança e tecnologia para você!",
    "Cuidamos e protejemos o seu veiculo!"

  ];

  const comentarioEl = document.getElementById("comentario");

  function mostrarComentarioAleatorio() {
    const index = Math.floor(Math.random() * comentarios.length);
    comentarioEl.style.opacity = 0; // efeito fade out
    setTimeout(() => {
      comentarioEl.innerText = comentarios[index];
      comentarioEl.style.opacity = 1; 
    }, 300);
  }

  // Mostra um comentário ao carregar a página
  mostrarComentarioAleatorio();

  // Troca o comentário a cada 5 segundos
  setInterval(mostrarComentarioAleatorio, 5000);
});
