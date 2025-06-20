const slajderi = document.querySelectorAll(".slajderi img");
let SlajderIndex =0;
let intervalId = null;

document.addEventListener("DOMContentLoaded", inicijalnislajd);

function inicijalnislajd(){
    if(slajderi.length > 0){
    slajderi[SlajderIndex].classList.add("postavislajd");
    intervalId = setInterval(sledSlika, 3000);
    }

}

function pokazislajd(Slajder){

    if (Slajder >= slajderi.length){
        SlajderIndex = 0;
    }
    else if (Slajder < 0){
        SlajderIndex = slajderi.length - 1;
    }

    slajderi.forEach(SZS=> {
        SZS.classList.remove("postavislajd");
    });
    slajderi[SlajderIndex].classList.add("postavislajd");

}

function prosSlika(){
    SlajderIndex--;
    pokazislajd(SlajderIndex);
    clearInterval(intervalId);
    intervalId = setInterval(sledSlika, 3000);
}

function sledSlika(){   
    SlajderIndex++;
    pokazislajd(SlajderIndex);
    clearInterval(intervalId);
    intervalId = setInterval(sledSlika, 3000);
}