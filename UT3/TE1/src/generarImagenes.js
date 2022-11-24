function generarImagenes(){
    let divImagenes=document.getElementsByName('imagenes');
    divImagenes[0].innerHTML="";
    let tamanio=document.getElementsByName('tamanio');
    let dw=tamanio[0].value;
    let ancho=document.getElementsByName("ancho");
    let bw=ancho[0].value;
    let color=document.getElementsByName("color");
    let bc=color[0].value;
    let enfoque=document.getElementsByName("enfoque");
    let sharpen=enfoque[0].value;
    let desenfoque=document.getElementsByName("desenfoque");
    let blur=desenfoque[0].value;
    let small_light="?dw="+dw+"&dh="+dw+"&bw="+bw+"&bh="+bw+"&bc="+bc+"&sharpen="+sharpen+"&blur="+blur;
    for(let i=1;i<=20;i++){
        let imagen=document.createElement("img");
        if(i<=9){
            imagen.setAttribute("src","/img/image0"+i+".jpg"+small_light);
        }else{
            imagen.setAttribute("src","/img/image"+i+".jpg"+small_light);
        }
        imagen.className="m-2";
        divImagenes[0].appendChild(imagen);
	divImagenes[0].appendChild(document.createTextNode("\n"));
    }
}
