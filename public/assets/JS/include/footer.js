//afficher année en cours sur le copyright

function displayYear (){
    let date = new Date();
    let copyright = document.getElementById('copyright');

    date = date.getFullYear();

    return copyright.innerText = date;
}

displayYear(); 