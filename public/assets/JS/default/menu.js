// =========================================
// =============== VARIABLES ===============
// =========================================

const btn1 = document.querySelector(".btn1");
const btn2 = document.querySelector(".btn2");
const btn3 = document.querySelector(".btn3");

const menu1 = document.querySelector(".menu1");
const menu2 = document.querySelector(".menu2");
const menu3 = document.querySelector(".menu3");

// =========================================
// =============== EVENEMNTS ===============
// =========================================

btn1.addEventListener("click", viewMenu1);
btn2.addEventListener("click", viewMenu2);
btn3.addEventListener("click", viewMenu3);

// =========================================
// =============== FONCTIONS ===============
// =========================================

function viewMenu1(){
    if(menu2.classList.contains("active")){
        menu2.classList.remove("active")
    };
    if(menu3.classList.contains("active")){
        menu3.classList.remove("active")
    };
    if(!menu1.classList.contains('active')){
        menu1.classList.add("active")  
    }   
}

// =========================================

function viewMenu2(){
    if(menu1.classList.contains("active")){
        menu1.classList.remove("active")
    };
    if(menu3.classList.contains("active")){
        menu3.classList.remove("active")
    };
    if(!menu2.classList.contains('active')){
        menu2.classList.add("active")  
    }   
}

// =========================================

function viewMenu3(){
    if(menu1.classList.contains("active")){
        menu1.classList.remove("active")
    };
    if(menu2.classList.contains("active")){
        menu2.classList.remove("active")
    };
    if(!menu3.classList.contains('active')){
        menu3.classList.add("active")  
    }   
}