// =========================================
// =============== VARIABLES ===============
// =========================================

const btn1 = document.querySelector(".btn1");
const btn2 = document.querySelector(".btn2");
const btn3 = document.querySelector(".btn3");

// =========================================
// ================== BOXS =================
// =========================================

const d1 = document.querySelector(".box1");
const d2 = document.querySelector(".box2");
const d3 = document.querySelector(".box3");

// =========================================
// =============== EVENEMNTS ===============
// =========================================

btn1.addEventListener("click", Box1);
btn2.addEventListener("click", Box2);
btn3.addEventListener("click", Box3);

// =========================================
// =============== FONCTIONS ===============
// =========================================

function Box1(){
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(d3.classList.contains("active")){
        d3.classList.remove("active")
    };
    if(!d1.classList.contains('active')){
        d1.classList.add("active")  
    }
        
}

// =========================================

function Box2(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(d3.classList.contains("active")){
        d3.classList.remove("active")
    };
    if(!d2.classList.contains('active')){
        d2.classList.add("active")  
    } 
}

// =========================================

function Box3(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(!d3.classList.contains('active')){
        d3.classList.add("active")  
    } 
}