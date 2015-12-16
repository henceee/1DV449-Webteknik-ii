##ProjektBeskrivning

Mitt projekt kommer att vara en väderapplikation, där användare kunna få en femdygnsprognos presenterad för den plats användaren anger.
Finns det flera platser med samma namn ska användaren från en lista kunna välja den plats som prognosen ska visas för. Prognosen för en specifik
dag skall minst baseras på period 2, d.v.s. prognosen mellan 12:00-18:00. Saknas period 2 ska period 3 användas. Saknas period 3 tas nästa dags period 2.
Prognosen skall även innehålla en bild, som representerar vädret samt temperatur.

##Tjänster

För att tillhandahålla ortsnamn, kommer jag att använda [Geonames API](http://www.geonames.org/export/web-services.html), [Google Maps API](https://developers.google.com/maps/)
samt [yr.no API för att tillhandahålla väderinformation](http://om.yr.no/verdata/free-weather-data/). En relationsdatabas kommer även att användas för
att undvika fler anrop till tjänsterna än nödvändigt, dvs lagring av persistent data.

##Övrigt
Projektet kommer att samköras med ASP.NET MVC kursen 1DV409 och består av den rekommenderade applikationen som angivits. [Följande krav måste därför
uppfyllas](https://github.com/1dv409/kursmaterial/raw/master/Laborationsuppgifter/2-1-individuellt-arbete.pdf)
