//funcion para buscar pedidos por tipo de pago
function fntSearchPagos(){
    let fecha = document.querySelector(".pagoMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        //creamos una variable para implementar ajax
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        //creamos una variable que lamacenen la carpeta raiz del proyecto y concatenamos el controlador Dash.. y su función
        let ajaxUrl = base_url+'/Dashboard/tipoPagoMes';
        // creamos un avista de carga
        divLoading.style.display = "flex";
        // creamos una variable para almacenar un nuevo objeto de tipo FormData
        let formData = new FormData();
        // con append le agragamos un campo a la variable formData
        formData.append('fecha',fecha);
        // abrimos una conexion de tipo Post por medio de por medio de la url alamcenada en ajaxURL
        request.open("POST",ajaxUrl,true);
        // seguidamente enviamos los datos que corresponden a la variable formData
        request.send(formData);
        // onreadystatechange nos devolvera la informacion enviada
        request.onreadystatechange = function(){
            //validamos- - si la respuesta es diferente de 4 entonces no hay respsuesta, retornamos el proceso
            if(request.readyState != 4) return;
            // de lo contrario si es igual a 200
            if(request.status == 200){
                // mostramos el resultado a traves de jquery
                //nos dirigimos al id pagosMesAnio y dentro d esu html le colocamos todo la respuesta de request.responseText 
                $("#pagosMesAnio").html(request.responseText);
                //ocultamos el loadin que agregamos anteriormente
                divLoading.style.display = "none";
                // finalizamos el proceso
                return false;
            }
        }
    }
}

//funcion para buscar pedidos por mes
function fntSearchVMes(){
    let fecha = document.querySelector(".ventasMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        //creamos una variable para implementar ajax
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        //creamos una variable que lamacenen la carpeta raiz del proyecto y concatenamos el controlador Dash.. y su funcion
        let ajaxUrl = base_url+'/Dashboard/ventasMes';
        // creamos un avista de carga
        divLoading.style.display = "flex";
        // creamos una variable para almacenar un nuevo objeto de tipo FormData
        let formData = new FormData();
        // con append le agragamos un campo a la variable formData
        formData.append('fecha',fecha);
        // abrimos una conexion de tipo Post por medio de por medio de la url alamcenada en ajaxURL
        request.open("POST",ajaxUrl,true);
        // seguidamente enviamos los datos que corresponden a la variable formData
        request.send(formData);
        // onreadystatechange nos devolvera la informacion enviada
        request.onreadystatechange = function(){
            //validamos- - si la respuesta es diferente de 4 entonces no hay respsuesta, retornamos el proceso
            if(request.readyState != 4) return;
            // de lo contrario si es igual a 200
            if(request.status == 200){
                // mostramos el resultado a traves de jquery
                //nos dirigimos al id pagosMesAnio y dentro d esu html le colocamos todo la respuesta de request.responseText 
                $("#graficaMes").html(request.responseText);
                //ocultamos el loadin que agregamos anteriormente
                divLoading.style.display = "none";
                // finalizamos el proceso
                return false;
            }
        }
    }
}

//funcion para buscar pedidos por año
function fntSearchVAnio(){
    //nuestro variable anio sera igual a lo que obtenga de la clase ventasAnio
    let anio = document.querySelector(".ventasAnio").value;
    //validamos si la variable es igual  a nada entonces se mostrata un eveno de error y retornara false para deterner el proceso
    if(anio == ""){
        swal("", "Ingrese año " , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/ventasAnio';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('anio',anio);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#graficaAnio").html(request.responseText);
                divLoading.style.display = "none";
                return false;
            }
        }
    }
}