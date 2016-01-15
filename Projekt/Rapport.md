#Weather Mashup – En API mashup tjänst av Henric Gustafsson

##Inledning
### Om mitt projekt
Bakgrunden till mitt val av applikationsidé beror helt enkelt på bristande idéer om vad för tjänster som kan behövas, då jag inte anser att det fattas mig några tjänster utöver de som redan existerar. Därmed  blev det ett logiskt val att låta detta projekt sammanfalla med projektet i kursen 1DV409 ASP.NET MVC. Projektidén är en väderapplikation, som använder data från geolocation API för att uppsöka information om platsen, för att sedan göra ett anrop till det norska väder API:et för YR.NO och sammanställa en prognos för nästkommmande 5 dagar, baserat på andra perioden (00-18:00) ifall det existerar, annars baseras prognosen på period 3 (18:00-00). Ifall inte någon väderdata existerar för någon av dessa perioder, baseras den dagens prognos på period 2 för nästkommande dag.

Naturligtvis finns det massvis med väderapplikationer med liknande funktionalitet, och mer funktionalitet, såsom väder.nu, SMHI,klart.se,DMI med flera väderapplikationer där man kan söka efter väderprognoser efter ortsnammn. Vilka tekniker dessa använder är dock för mig ovisst.
Tekniken jag har valt att anväda är ASP.NET MVC 4 samt MYSQL.

 !["Application Layers"](https://raw.githubusercontent.com/henceee/Hg222dv-1DV409-1DV449-Projekt/15236a086fa3547da9903f0f238c20108975d8f9/WeatherMashup/ApplicationLayers.png)

##Säkerhet och Prestandaomtimering

För att minska antalen anrop, kan servern ses som en proxy, där data lagras persistent i en relationsdatabas, för att uppdateras endast då datum och tid överskrider det datum satt av YR.No,  vilket innebär att inga anrop görs mot API:et så länge det finns akutell väderdata. På samma vis hämtas ingen data om platsen, ifall det finns information om platsen sparad i databasen, då data som longitud, latitud och platsnamn är statiskt och inte förändras behövs ingen datumstämpel för att kontrollera ifall det hämtas från webbtjänsten eller från databasen. 

I enlighet med Souders rekommendationer[1] för prestandaoptimering, minifieras skriptfiler och stilmallar och slås samman i såkallade ”bundles” vid publicering, vilket innebär ett minskat antal HTTP-anrop. Stilmallar och skriptfiler laddas in i headern respektive längst ned i body taggen, vilket även rekommenderas, eftersom DOM:en (Document Object Model) och CSSOM (CSS Object Model) måste bägge vara laddade för att sedan eventuellt kunna manipuleras i Javascripten. All Javascript och CSS är även extern. Dock har inte GZIP använts, då det kändes överflödigt, och inte heller något Content Delivery Network (CDN) används, då det inte känns aktuellt med tanke på applikationens storlek.

## Offline-first
Geno matt använda [nuGet RockOffline, by Gareth Rierd]( https://github.com/gareth-reid/RockOffline/tree/master/RockOfflinePackage), kan application cache tas I bruk genom att lägga till növändiga script som en “bundle”, lägga till ett manifest I en vy, samt registera detta manifest i route configen. Därmed bör en chache:ad version av sidan visas då användaren är offline.
 
##Riskanalys
Naturligvis medför en tjänst som består av två externa tjänster i form av API:er en benägenhet att vara känslig för förändringar, såsom namn på konstanter, anrop till metoder och så vidare, vilket kan orsaka att kod ”förfaller” – blir inaktuell och slutar att fungera. Det medför även att ifall en tjänst upplever något internt fel, eller ligger nere, så kan hela tjänsten falla.

Då lagrade procedurer används, samt att ogiltiga id orsakar applikationsfel som sedan fångas av applikationen för att visa en egen felmeddelande-sida, vilket gör att tjänsten inte är känslig för SQL injections. All indata binds mot ett vymodell-objekt, och orsakar också fel som hanteras av applikationen ifall indatan är inkorrekt. Detta gör att XSS och CSRF ofarliggörs. Dock kan en attack göras genom att injicera felaktig data på något sätt till webbtjänsterna (yr.no eller geolocation), då jag inte känner till hur sådan data bör testas. Dock är jag numera medveten om den risken, men bedömmer den som ganska liten.

##Reflektioner
Projektet har varit svårt och medfört mycket motgångar men det har varit intressant att jobba mot API:er i ASP.Net då det fortfande är nytt för mig. Dock orsakar Entity Framework ibland ett Optimistic Concurrency Exception – en bugg som jag inte vet vad det beror på. Mats sade att jag skulle testa att avlägsna min egna kod och försöka att lägga till ett objekt direkt jämtemot min WeatherMashupEntity, vilket jag gjorde och kunde göra slutsatsen att det inte är min kod som orsakar detta exception då det inte fungerade då heller. De lagrade procedurerna fungerar som de skall då jag kör dem i SQL Server, och att föra över koden till ett nytt projekt och lägga en ny modell från databasen fungerade inte heller. 


## Länkar
[Applikationen](http://172.16.214.1/1dv409/hg222dv)

[Källkod] (https://github.com/henceee/Hg222dv-1DV409-1DV449-Projekt)

[Video]( https://youtu.be/wEfF0fLWMtk)

[1] S. Souders, “High-Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.36,December 2008. [Online] Available: OneSearch [Downloaded: 3 december, 2015].
