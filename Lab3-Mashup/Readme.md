[link to Lab 3 Traffic Mashup](http://46.101.141.31/)

###Vad finns det för krav du måste anpassa dig efter i de olika API:erna?

Naturligtvis blir en applikation uppbyggd på två API:er väldigt beroende av svarstiderna från respektive tjänst. Det är med bakgrund av detta som data cache:as både på servern, som fungerar som en proxy mot SR:s API såväl som på klienten med hjälp av localstorage. Dock är tjänsten väldigt beroende av svarstiderna från google.

En annan aspekt är naturligvis att metodanropen hanteras på respektive server samt att man som utvecklare måste hålla i åtanke att det kan ske viss förruttning, skulle man väl kanske kunna kalla det på svenska, eller code decay. Det vill säga att koden inte längre fungerar, bitar tynar bort och slutar att fungera, vilket kan orsaka fullständig kollaps av applikationen. Därför bör man tänka både en andra och tredje gång innan man gör sig så pass beroende av andras kod, ifall man inte har möjlighet till ständig och kontinuerlig uppdatering av tjänsten.

###Hur och hur länga cachar du ditt data för att slippa anropa API:erna i onödan?

Datan lagras i 10 minuter på serversidan, samt lika länge på klienten. Det är för mig ovisst hur ofta SR uppdateras, men det skulle säkert kunna reduceras till en gång per dag eller något löjligt lågt, ifall tjänsten i ett högst fiktivt läge skulle få ökad trafik.

###Vad finns det för risker kring säkerhet och stabilitet i din applikation?
Vissa risker angående stabilitet har redan tagits upp. Applikationen kan ses som en trebent pall, där ett ben är min kod, det andra är SR:s API och det tredje Google Maps. Ruttnar det ena bort, kommer stolen falla samman oavsett hur stabila de två andra benen är. Det är också problematiskt att google anvisar att man ska ha sin nyckel i urlen till javascriptfilens källa. Jag har dock inte använt parameterar i URL:en, som kan öppna till säkerhetshål och inga HTTP POST anrop görs till sidan, utan data hämtas dynamiskt. Det finns inte heller några inputfält utöver de checkboxes som finns på sidan, och avsett så leder inte dessa till att det postas till servern.

Det känns därför osannolikt att användaren kan orsaka HTML Injection, XSS eller CSRF attacker, däremot är det fruktansvärt enkelt för vem som helst att se de JSON objektet som sparas lokalt i klienten. Jag är medveten om denna risk och ser den som ett något som får utbytas mot färre anrop mot SR:s API.

Följande varning har även dykt upp i konsollen:
"getCurrentPosition() and watchPosition() are deprecated on insecure origins, and support will be removed in the future. You should consider switching your application to a secure origin, such as HTTPS. See https://goo.gl/rStTGz for more details"

Jag måste därför göra research för att finna alternativ till dessa i framtiden, men anser inte att hotet mot min applikation är speciellt stort.

###Hur har du tänkt kring optimeringen i din applikation?
Optimeringen har främst skett genom att både lokalt och på serversidan spara data, för att minska antalen anrop till SR:s API. Anropet till serven kan ses som en proxy till själva tjänsten och kan inverka responstiden positivt för användaren.
På grund av de problem som kan uppstå vid behov av uppdatering då filer minifierats har detta inte gjorts och inte heller ett DNS känns aktuellt med tanke på applikationens storlek. 


###Vad är och varför kom HTTP/2 till?

HTTP2 utvecklades för att minska laddtiden med 50 % samt undivka behovet för webbsidans ägare att ändra innehållet. Man ville även minimera komplexitet och samtidigt undvika förändringar i nätverksinstrastrukturen. Protokollet skulle utvecklas inom ett open-source community och kunna samla in data och analysera prestandaförändringar i det experientella protokollet. Det största problemet med HTTP/1 var att den använde flera anlsutningar för att hämta resurser parallelt. En anslutning kan användas mellan en klient och server för att begära flera resurser såsom bilder,CSS, Javascript osv. Dessa måste dock hämtas en efter en, och ifall en tar lång tid. kommer övriga objekt som skall begäras vänta tills resursen laddas klart. Syftet med HTTP/2 var att göra något åt denna problematik, samtidigt som man behöll den semantiska uppbyggnaden av HTTP/1.1.

Med HTTP/2 används en TCP anslutning per origin, där en begäran görs och en stream upprätthålls, som är "multiplexed", vilket innebär just att en anslutning används för flera strömmar med data. Dessa kan även prioriteras, samt delas upp i såkallade frames, så att ifall resursen är en bild t.ex. så kan dess header skickas tillbaka med dess dimensioner, men själva data framen prioretas lägre. Det finns även frame för att avsluta begäran efter en resurs. Olika vikt kan läggas på frames, och sedan beror leveransen av frames på dessa "weights" såväl som på eventuella beroende som kan finnas imellan dem. Dock behöver inte klienten inte begära varje resurs explicit, utan servern kan göra en såkallad "push promise", där servern helt enkelt skickar ett löfte att skicka med resten senare.

Domännamn kan även kollas upp i förhand för att minska antalet DNS-lookups, det vill säga Domain Name Service Lookups, säväl som föreberedelser inför TCP anslutningar, så att ett HTTP anrop förutses och en TCP anslutning öpnnas för att undivka ett nytt TCP handslag.



###Vad är Service Workers?
