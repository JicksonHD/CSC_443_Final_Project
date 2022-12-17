const moon = document.getElementById("moon");
let heart = document.getElementById("heart");

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

    if(heart.src.match("./empty_heart.png")){
        heart.src = "./full_heart.png";
    } else{
        heart.src = "./empty_heart.png";
    }  
}