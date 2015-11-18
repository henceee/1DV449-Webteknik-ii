# 1DV449-Webteknik-ii
##URL
[URL till webbskrapan](http://hg222dv.freeiz.com/)

##Finns det några etiska aspekter vid webbskrapning. Kan du hitta något rättsfall?
Angående etik, kan man självklart argumentera att det inte spelar någon roll ifall jag som användare använder ett skript för att utföra
handlingar som jag annars hade gjort via en browser, men det viktiga är väl snarare VAD man gör med informationen, HUR man får tag i den
och hur mycket hänsyn man visar webbsidans ägare (se avdelning om riktlinjer nedan).

Exempelvis är det harmlöst för mig att utläsa vilka filmer som går, men att manipulera postningar till formulär med egenskapade
arrayer med information känns något mer åt gråzonen för mig personligen, framförallt kan väl det leda till overposting ifall man inte är
försiktig. Man kan säkert även hitta exploits, ta reda på inloggningsdata och simulera input i ett formulär för att komma åt andras personliga
uppgifter.

Det finns säkert flera rättsfall, ett fall jag fann var när [Ebay försökte stämma företeget Bidders Edge](http://www.tomwbell.com/NetLaw/Ch06/eBay.html)
för att de hade skrapat någon form av information från dem.

##Finns det några riktlinjer för utvecklare att tänka på om man vill vara "en god skrapare" mot serverägarna?

Man bör naturligtvis ta hänsyn till Robot.Txt, ibland implementeras de felaktigt eller testas ej och
man kan helt enkelt ta i beaktning de regler som satts upp för webcrawling, som många sajter som erbjuder prisjämförelser följer.
(http://www.robotstxt.org/orig.html).

Man bör även ta i beaktning terms of use, ifall en sådan finns och i bästa fall (vilket jag tvivlar på att någon gör, eftersom
nätet är som ett "free-for-all") kontakta ägaren av sidan. Dock är det viktigaste att man ser till att inte bryta mot eventuella
riktlinjer webbansvarige satt upp. Jag hittade dock följande intressanta motargument:

"simply including “do not scrape us” in your website Ts & Cs did not constitute a legally binding agreement. It seemed like the battle against the scrapers had been lost."
[Web Scraping : Everything You Wanted to Know (but were afraid to ask)](http://resources.distilnetworks.com/h/i/111901208-web-scraping-everything-you-wanted-to-know-but-were-afraid-to-ask/181642)

samt:

"The law is still not settled on so-called "click-wrap" contracts, but a court will look at how prominently the terms of use are displayed and whether you had to agree to them before you could proceed with using the website or service.
If you never saw the terms of use, there can be no "meeting of the minds" to form a contract. In Specht v. Netscape, a court found that there was no contract for a software download, where there was no proof the downloaders were on notice of or agreed to the terms."
[Lumin - Linking](https://www.lumendatabase.org/topics/18#QID596).

Framförallt, speciellt ifall man vill komma undan utan att få en cease and decist bör man vara att man helt helt enkelt beter sig
som en vanlig användare, utan illvilja och därför inte söker att exploitera svagheter, göra extremt många posts eller på annat vis
påverka trafiken till webbsidan - påverkas inte prestanda och trafiken inte ökar kan man kanske komma undan obemärkt.

Ett exempel jag hittat på en sådan illvillig skrapare var att någon använde  HTTP 1/1 "keepalive" 
för att hålla anslutningen öppen och använda så mycket resurser som möjligt. [Här](https://www.cs.washington.edu/lab/webcrawler-policy)
beskrivs policys för webbskrapning mycket väl.

##Begränsningar i din lösning- vad är generellt och vad är inte generellt i din kod?

Min applikation hårdkodar inte in bas-urlen, dock finns ett strängberoende för att jämföra URL:erna av varje länk på förstasidan.
Applikationen iterar den igenom varje länk som motsvarar en person istället för att förutsätta att det alltid kommer vara peter, mary och paul, och att det alltid är 
3 personer. Detta innebär att ifall det blir 4 personer istället, bör det fungera att leta efter en dag som matchar alla 4, istället för 3.

Applikationen har ett starkt strängberoende till dagarnas namn - exmpelvis ifall informationen byts ut till på engelska så 
måste koden ändras om, såväl som antalet dagar som i nuläget hanteras i switch satser och innefattar endast fredag,lördag och söndag.


##Vad kan robots.txt spela för roll?

Man kan se till att visa delar urler inte tillåts för vissa user-agents, genom att göra disallow. Dock kan man inte tillåta, endast förbjuda.

[About Robot.Txt)(http://www.robotstxt.org/robotstxt.html)
