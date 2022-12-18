const moon = document.getElementById("moon");
const heart = document.getElementById("heart");
const comment_icons = document.getElementById("comment");
const add_comment = document.getElementById("add_comment");
const remove_comment_box = document.getElementById("x_icon");

moon.onclick = function(){
    document.body.classList.toggle("dark-theme");
    if(document.body.classList.contains("dark-theme")){
        moon.src = "./sun.png";
    }else{
        moon.src = "./moon.png";
    }
}

heart.onclick = function(){


    if(heart.src.match("./empty_heart.png")){
        heart.src = "./full_heart.png";
    } else{
        heart.src = "./empty_heart.png";
    }  
}

const commentHandler = (c) => {

    add_comment.style.display = "flex";

};

const xicon = (x) => {

    add_comment.style.display = "none";
}

comment_icons.forEach(b => b.addEventListener("click",commentHandler));
remove_comment_box.addEventListener("click", xicon);
