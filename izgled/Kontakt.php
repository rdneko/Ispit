<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   <h1>Solarni Paneli</h1>
         <nav class="navigacija">
      <ul>
      <?php include 'navigacija.php'; ?>
         </nav>
    
     <div class="boja" style="background-image: url('../slikezasajt/pozadina.avif'); margin-bottom: 0%;margin-left: 0px;margin-right: 0%; margin-top: 2%; height: 100%;padding-top: 2px;width: 100%;padding-bottom: 35%;">
        <div class="kontakti">

            <h2>Pošaljite poruku</h2>  
            <br> 
            <form >
                <div class="popuni">

                    <label for="ImeiPrezime">Ime i prezime:</label>
                     <input type="textbox" id="ImeiPrezime" size="10%" name="ImeiPrezime" placeholder="Ime i prezime" required>
                
                </div>
                <br>
                <div class="popuni">

                    <label for="Brojtelefona">Broj telefona:</label>
                     <input type="tel" id="Brojtelefona" size="7%" name="Brojtelefona" placeholder="Broj telefona" required>
                    
                </div>
                <br>
                <div class="popuni">

                    <label for="Email">Email:</label>
                     <input type="email" id="Email" size="10%" name="Email" placeholder="Email"required>
                        
                </div>
                <br>

                <label class="popuni" for="Poruka">Poruka:</label>
                
                <div class="popuni">

                     <textarea name="poruku" id="Poruka" placeholder="Poruka" required></textarea>
                        
                </div>
                <div class="dugmeposalji">
                    <button type="submit">Pošalji</button> 
                </div>
            </form>
           
        </div>
     </div> 

     <footer>

        <div class="ikonice1"> <h3>Kontakti:</h3>
         <img class="ikona" src="../slike za sajt/ikonamapa.webp" alt=""> <a href="PavlaJankovićaŠoleta5">Pavla Jankovića Šoleta 5 (Klisa) Novi Sad</a>
        <br> <img class="ikona" src="../slike za sajt/ikonagmail.png" alt=""> <a href="office@gmail.com">office@gmail.com</a>
        <br> <img class="ikona" src="../slike za sajt/slusalica.jpg" alt=""> <a href="tel:+381654025">+381 654 025</a>    
        <br> <img class="ikona" src="../slike za sajt/slusalica.jpg" alt=""> <a href="tel:+381452843">+381 452 843</a>
        </div>

         <div class="ikonice"> <h3>O nama</h3>
            Više informacija o nama i našim uslugama možete pogledati:<a href="O_nama.php   " id="odje">OVDE</a> 
         </div>
             
             <div class="ikonice">
                <form> <h3>Pretplatite se</h3>
                Pretplatite se za naš newsletter! <br> <br> <input type="email" id="Email" size="10%" placeholder="Email"required>
                <button type="submit">Pošalji</button>  
            </form>
             </div>
    </footer> 

</body>
</html>