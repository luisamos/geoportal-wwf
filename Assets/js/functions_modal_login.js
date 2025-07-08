function openModal(){
    document.querySelector('#txtUsuario').value ="";
    document.querySelector('#txtClave').value ="";
    document.querySelector("#formLogin").reset();
}

document.addEventListener('DOMContentLoaded', function(){
    if(document.querySelector("#formLogin")){
        let formLogin = document.querySelector("#formLogin");
        formLogin.onsubmit = function(e) {
            e.preventDefault();

            let strEmail = document.querySelector('#txtUsuario').value;
            let strPassword = document.querySelector('#txtClave').value;

            if(strEmail == "" || strPassword == "")
            {
                new Toast("Por favor , Escribe usuario y contraseñaa.", "error");
                return false;
            }else{
                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url+'/Ingreso/loginUser'; 
                var formData = new FormData(formLogin);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                request.onreadystatechange = function(){
                    if(request.readyState != 4) return;
                    if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(objData.status)
                        {
                            window.location.reload(false);
                        }
                        else{
                            new Toast("Error2: "+objData.msg);
                            document.querySelector('#txtClave').value = "";
                        }
                    }
                    else{
                        new Toast("Error1: "+objData.msg);
                    }
                    return false;
                }
            }
        }
    }

}, false);