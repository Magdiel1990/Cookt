<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC</title>
    <style>
     *{
         padding: 0;
         margin: 0;
         box-sizing: border-box;
     }
     body {
         position: relative;
         width: 100%;
         height: 100%;
         background: linear-gradient(90deg, rgba(131,58,180,1) 0%, rgba(253,29,29,1) 50%, rgba(252,176,69,1) 100%);
     }
     header {
         position: relative;
         width: 100%;
         height: 100%;
         background-color: rgba(0,0,0,0.7);
     }
     header h1 {
         text-align: center;
         color: white;
     }
     nav {
        position: relative;
        width: 100%;
        height: 100%;
        padding-bottom: 10px;
     }
     nav ul {
         display: flex;
         list-style: none;
         flex-wrap: wrap ;
         justify-content: space-evenly;
     }
    nav ul li a{
        position: relative;
        text-decoration: none;
        color: white;        
     }
     nav ul li a:hover{
        color: rgb(100,20,150);   
     }
     main{
         position: relative;
         width: 100%;
         height: 100vh;
         border: 3px solid green;
     }
     footer {
        position: relative;
        width: 100%;
        height: 20vh;
        background-color: rgba(0,0,0,0.7);
    }
     footer div {
        text-align: center;
        font-size: 20px;
        color: white;
     }
    </style>