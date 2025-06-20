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
 <?php include 'navigacija.php'; ?>
    </nav>
    <main>
    <div class="dizajnOnama">
      <div class="tsOnama">
        <div class="tekstOnama"> 
           <h3 id="son"> Solar Energy - Sve o nama</h3>
            <b>Solar Energy</b> je inovativna kompanija osnovana 2019. godine, posvećena širenju svesti o značaju solarne energije među različitim zajednicama. Naš tim čini grupa stručnih inženjera sa višegodišnjim iskustvom na različitim projektima. U želji da zadovoljimo potrebe naših kupaca visokokvalitetnim proizvodima i uslugama, izgradili smo moderan sistem upravljanja kvalitetom koji se striktno pridržava međunarodnih standarda.
            <br> <br>
            Uzimajući integritet, kvalitet i profesionalizam kao korporativnu svrhu, ulažemo velike napore da obezbedimo solarne panele visokih performansi po konkurentnim cenama. Bilo da birate proizvod iz našeg kataloga ili tražite inženjersku pomoć, naša korisnička služba vam je na raspolaganju kako bi vam pružila sve neophodne informacije i pomogla da donesete najbolju odluku, osiguravajući vaše potpuno zadovoljstvo.
            <br> <br>
            Održivi razvoj zavisi od čistijih obnovljivih izvora energije. Promovisanjem obnovljive energije obezbedićemo ekonomski rast, bolji kvalitet života i dati svoj deo u borbi protiv klimatskih promena.
            <br> <br>
            Isporukom čiste, pouzdane energije na konzistentan, ekološki prihvatljiv i etički način, mi ćemo razviti, izgraditi, posedovati i upravljati solarnim elektranama. Solar Energy se fokusira na izgradnju solarnih projekata na tržištima u razvoju gde možemo maksimalno povećati svoj uticaj na održivost i ekonomski rast. Sa preko 60 zaposlenih, naša i stručna radna snaga je ključni činilac u ostvarivanju poslovnih ciljeva i pružanju visoko kvalitetnih proizvoda i usluga našim klijentima. Raznovrsnost našeg tima omogućava nam efikasno rešavanje izazova, promovisanje inovacija i postizanje izuzetnih rezultata. Stalno ulažemo u razvoj naših zaposlenih kroz obuke, mentorstvo i druge programe podrške, osiguravajući njihov profesionalni rast i napredak.
            <br> <br>
            Verujemo u timski duh, transparentnost i otvorenu komunikaciju unutar organizacije. S nama, budućnost je svetla i održiva.        </div>
        <div class="slikeOnama">
            <img width="350px" src="../slike za sajt/tim.jpg" alt="Slika ne može biti učitana">
            <img src="" alt="">
            <img width="350px" src="../slike za sajt/skaldiste.webp" alt="Slika ne može biti učitana">
        </div>

     </div>

        <div class="videoOnama">
            <video src="../slike za sajt/video.mp4" width="1000px" controls >
            </video>
        </div>

    </div>
    </main>

    <footer>

        <div class="ikonice1"> <h3>Kontakti:</h3>
         <img class="ikona" src="../slike za sajt/ikonamapa.webp" alt=""> <a href="PavlaJankovićaŠoleta5">Pavla Jankovića Šoleta 5 (Klisa) Novi Sad</a>
        <br> <img class="ikona" src="../slike za sajt/ikonagmail.png" alt=""> <a href="office@gmail.com">office@gmail.com</a>
        <br> <img class="ikona" src="../slike za sajt/slusalica.jpg" alt=""> <a href="tel:+381654025">+381 654 025</a>    
        <br> <img class="ikona" src="../slike za sajt/slusalica.jpg" alt=""> <a href="tel:+381452843">+381 452 843</a>
        </div>
  
         <div class="ikonice"> <h3>O nama</h3>
            Više informacija o nama i našim uslugama možete pogledati:<a href="O_nama.php" id="odje">OVDE</a> 
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