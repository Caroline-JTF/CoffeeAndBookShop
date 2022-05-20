// =========================================
// =============== VARIABLES ===============
// =========================================

const btn1 = document.querySelector(".btn1");
const btn2 = document.querySelector(".btn2");

// =========================================
// ================== BOXS =================
// =========================================

const d1 = document.querySelector(".box1");
const d2 = document.querySelector(".box2");

// =========================================
// =============== EVENEMNTS ===============
// =========================================

btn1.addEventListener("click", Box1);
btn2.addEventListener("click", Box2);

// =========================================
// =============== FONCTIONS ===============
// =========================================

function Box1(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(!d2.classList.contains('active')){
        d2.classList.add("active")  
    }   
}

// =========================================

function Box2(){
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(!d1.classList.contains('active')){
        d1.classList.add("active")
    } 
}
