// Boutons
const btn1 = document.querySelector(".btn1");
const btn2 = document.querySelector(".btn2");

const menu = document.querySelectorAll(".menu");

let index = 0;

// events
btn1.addEventListener("click", precedent);
btn2.addEventListener("click", suivant);

//Fonctions

function suivant(){

    if(index < 2){

        menu[index].classList.remove('active');
        index++;
        menu[index].classList.add('active');

    }
    else if (index === 2) {

        menu[index].classList.remove('active');
        index = 0;
        menu[index].classList.add('active');

    }

}

function precedent(){

    if(index > 0){

        menu[index].classList.remove('active');
        index--;
        menu[index].classList.add('active');

    }
    else if (index === 0) {

        menu[index].classList.remove('active');
        index = 2;
        menu[index].classList.add('active');

    }
}