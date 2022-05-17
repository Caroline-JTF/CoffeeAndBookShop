// Boutons
const btn1 = document.querySelector(".btn1");
const btn2 = document.querySelector(".btn2");
const btn3 = document.querySelector(".btn3");
const btn4 = document.querySelector(".btn4");
const btn5 = document.querySelector(".btn5");

// boxs
const d1 = document.querySelector(".box1");
const d2 = document.querySelector(".box2");
const d3 = document.querySelector(".box3");
const d4 = document.querySelector(".box4");
const d5 = document.querySelector(".box5");

// events
btn1.addEventListener("click", Box1);
btn2.addEventListener("click", Box2);
btn3.addEventListener("click", Box3);
btn4.addEventListener("click", Box4);
btn5.addEventListener("click", Box5);

//Fonctions

function Box1(){
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(d3.classList.contains("active")){
        d3.classList.remove("active")
    };
    if(d4.classList.contains("active")){
        d4.classList.remove("active")
    };
    if(d5.classList.contains("active")){
        d5.classList.remove("active")
    };
    if(!d1.classList.contains('active')){
        d1.classList.add("active")  
    }
        
}

function Box2(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(d3.classList.contains("active")){
        d3.classList.remove("active")
    };
    if(d4.classList.contains("active")){
        d4.classList.remove("active")
    };
    if(d5.classList.contains("active")){
        d5.classList.remove("active")
    };
    if(!d2.classList.contains('active')){
        d2.classList.add("active")  
    } 
}

function Box3(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(d4.classList.contains("active")){
        d4.classList.remove("active")
    };
    if(d5.classList.contains("active")){
        d5.classList.remove("active")
    };
    if(!d3.classList.contains('active')){
        d3.classList.add("active")  
    } 
}

function Box4(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(d3.classList.contains("active")){
        d3.classList.remove("active")
    };
    if(d5.classList.contains("active")){
        d5.classList.remove("active")
    };
    if(!d4.classList.contains('active')){
        d4.classList.add("active")  
    }  
}

function Box5(){
    if(d1.classList.contains("active")){
        d1.classList.remove("active")
    };
    if(d2.classList.contains("active")){
        d2.classList.remove("active")
    };
    if(d3.classList.contains("active")){
        d3.classList.remove("active")
    };
    if(d4.classList.contains("active")){
        d4.classList.remove("active")
    };
    if(!d5.classList.contains('active')){
        d5.classList.add("active")  
    }  
}