const moon = document.getElementById("moon");
let heart = document.querySelector('img');

moon.onclick = function(){
    document.body.classList.toggle("dark-theme");
    if(document.body.classList.contains("dark-theme")){
        moon.src = "./sun.png";
    }else{
        moon.src = "./moon.png";
    }
}

heart.onclick = function(){
    console.log("I entered");
    let mySrc = heart.getAttribute('src');
    if(mySrc === "./empty_heart.png"){
        heart.setAttribute('src',"./full_heart.png");
    } else{
        heart.setAttribute('src',"./empty_heart.png");
    }  
}