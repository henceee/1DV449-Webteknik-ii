# 1dv449-messy-labbage

#Messy_Labbage Rapport

##Preformance and Optimization
The key to faster sites and web applications is through Front End Optimization. In understanding what this entails, one must first gain an understanding of where the front end and back end are in a web service, or any technological system. For a web service, the front end is where the browser downloads and presents content to the user and the back-end is on the server-side of the service provider where the requests from the user are handled according to the definition provided by Technopedia [1].
As much of the response time of a user request lies on the front-end, somewhere between 80-91%, according to estimates from Yahoo Developer Network[2] and Souders [3] respectively, one should reflect upon how the response time can be reduced. 

###Javascript and Stylesheets
To minimize the number of HTTP request without affecting the content of the page, one can combine multiple scripts into a single script and combine multiple style sheets into one, as well as using so called sprites to minimize the number of background images for the CSS [4].

####Problems and description
In its current state, the Messy labbage site contains 5 external JavaScript files and 4 external stylesheets, which results in many requests. The site also contains one JavaScript script, and one CSS style inlined in the HTML document. In order to decrease load-time, one should always make CSS and JavaScript external, rather than entwined in the HTML document, as these external files can be cached by the browser [5].

When the browser parses HTML, the Document Object Model (DOM) is created parallel to the CSSOM, which is constructed from the rules specified in the stylesheet. Together they comprise the render tree, which enables the browser to render content to the user. 
In constructing the DOM and CSSOM objects, the DOM must await the execution of JavaScript, which is reliant upon the CSSOM object. This intertwined relationship can create load-time issues, especially if the stylesheet(s) are not included in the top of the page, and the script file(s) are not included in the bottom[6].

Seemingly complex browser architecture aside, what is important is that scripts and stylesheets are loaded externally, by javascript and CSS in external documents.Including the CSS in the header and the JavaScript at the very bottom of the body tag is of course also important.

It is therefore problematic that 4 scripts are currently found in the head of the page in the Messy Labbage site, mixed in with the stylesheets. On a brighter note, CSS expressions are not used, in accordance to Souders’ 7th rule. CSS expressions are ill advised, since these expressions are based on the execution of JavaScript code embedded within the stylesheet, and are evaluated quite frequently, and reduce the load-time [7].

However, only 3 out of 5 scripts used in the application are minified. Souders recommends that JavaScript files are compressed to reduce the size of the file, by minification, which means stripping unnecessary characters such as tabs, new lines, white space and so on. By doing this, the size of the file can be reduced with around 20% [8]. 

####Solution
Although it might seem like a lot of things to think about, the solution is quite easy. Simply get rid of the inline CSS and put all the CSS in a combined stylesheet, and link it in the pages header. Accordingly, the inline script should be removed as well, and put with the rest of the scripts in a combined script file. These text files can then be minimized, in order to reduce the size of the file. 

###Compression with GZIP
While on the topic of reducing file size, it is also recommended by Souders to GZIP components. So what is GZIP, you might ask? GZIP was developed by the GNU project, and standardized by RFC 1952. It is probably the most widespread compression format out there. For more information, visit the Gzip homepage [9]. GZIP further reduces the file size, thereby reducing the amount of data to be transferred over the network. It is recommended that text files are compressed, such as the HTML document, scripts, stylesheets, XML and JSON files.  By using the GZIP data size can be reduced with around 70 % [10]. This is however only relevant if the file size exceeds 1-2 kB.


###Decreasing Response time with CDN
As of now, the application is not that extensive, nor will it be burdened with extensive traffic from different locations in the world, as it is intended only for internal use, within the company. However, to reduce the load time further one can also use a Content Delivery System (CDN), which means a collection of servers at different location that enable a more efficient delivery of content, based on proximity and response time. Yahoo recomend CDN services provied by companies such as Akamai Technologies, EdgeCast or level3 for cost efficiency[11]. As of now, on its current state, the cost would probably be to great, and bring very little value. The knowlage of how to optimize a site for improved response time when dealing with greater scale, can still be most valuble though.


###Cache-Controll
In order to make sure that the same content is not requested again and again, for each page, one can get controll over the lifetime of the object in the cache, stored in the front-end - thereby reducing the number of requests to the server. This can be achieved by using an Expires header. As long as the expiration-date is in the future, and such a date exists, the browser simply uses an cached version of that resource instead. 

One can also utilize Entity Tags, or ETags, to ensure that the cached version of a resource is valid, and corresponds with the resource located on the server. If the versions match, the server sends a 304 code back, meaning 'Not Modified', telling the server to use the cached file, rather than getting the resource from the server. Note that if ETags are not used, it is adviced that ETags are turned off in the webserver configuration file.[12]

Again, it is doubtful that the preformance is affected in this juncture, as the size of the application and expected traffic are limited. In the current state, however, all resources are requested from the server, returned with a 200 if the resource could be found and then parsed, instead of returning a header which tells the user to utilize the cached resources.

##Security

##SQL Injection
###Problem and description
An SQLIA, or an SQL Injection attack is a malicious user attempts to take advantage of vulnerabilities in a web application. This can be done, for example by injecting SQL into a field in a form, or by manipulating the URL.[13] For instance one can enter the following statement:

' OR
'1=1

This can give the user acess to restricted data, or run malicious code on the site. That exact statement enabled acess to the login on the site. Luckily, there was some validation of the email field, which required a correct format for email adresses. However, the password field was completely vulnerable to injections, granting easy access to login as Admin, without even having to know a valid email stored in the database. This is on account of the latter part of the statement '1=1, which always is true.

As described by Balasundram and Ramaraj "Malicious attacks occur
when developers combine hard-coded strings with user
provided input to create dynamic queries".

###Solution

Input validation and prepared statements is the probably the most common way to defend a web application against SQL Injections, and are two of the four solutions presented by Balasundram and Ramaraj. If input is not properly validated, and therefore cannot be "trusted" strings, malcicious users can change the intended use of SQL queries.

By preparing statements,the values are seperated from the structure of the SQL. The SQL statement is described as an skeleton, which is filled in during runtime. This may, however, come at the price of rewriting the application almost completetly, to reduce the risk of injections.

Furthermore, randomization of common SQL keywords are proposed as an technique to reneder injections harmless, as the injected statement would be syntaxally incorrect, as well as proxy filters. Proxys are however prone to human error, according to  Balasundram and Ramaraj [14]. Personally, I would argue that the two first methods should suffice to increase the sequrity signigically.


##HTML Injection, XSS and CSRF

###Problem and description

A Cross-Site-Request (CSRF or XSRF) attack is when a malicious site, such as a messaging application  as well as email, blog or program causes the browser to behave in a way which the user did not intend, while authenticated to that trusted site.This could, in worst case scenarious cause stolen bank information, money transfers or purchases in that person's name.[15]

So what happens when data is not properly validated, leaving your site open to Cross Site Scripting (XSS) and XSRF attacks? This allows malicious users to inject HTML and script tags to the document? Imagine for a moment, a vicious hacker in search for exploits to gain acess of your login data. If that user can manipulate the web application to redirect you to another site, in order to hijack your session, before redirecting you to advertised location. The hacker has now sucessfully gained access to your login information.

This may not lead to extreme cases on this perticular site, however imagine if there was some additonal data, which could be used to gain acess to other valuble information. If you are really unlucky, the hacker has already changed the password, used the provided email and by simply guessing the password unlocked your google, facebook and pretty much anything he or she so wishes from there anyway. This just goes to show the importance of having atleast a few different password.

###Evaluation and Solution
Although I was unable to get any Javascript to be actually be executed, which is indeed a good thing, there are several security issues that I feel I am obligated to atleast adress the potential threats regarding the Messy_Labbage site.

7 rules are described to avoid Cross Site Scripting on the OWASP wiki page [16]. The first of which is to HTML escape before inserting data into the HMTL content, which is clearly not done, as I could enject properly rendered HTML. If the data was properly escaped, the literal characters would be shown, but not rendered as HTML. I have therefore drawn the conclution that my attempt att XSS injection is not the result of proper validation, but rather poor hacking skills.

It is to be noted, however that rule 3.1 of aforementioned XSS checklist is properly implemented, as the Content Type is set to application/JSON with UTF-8 charset, rather than text, which leaves it less vulnerable to XSS attacks.

There is not, however any Synchronizer Token Pattern to be found on the site, which is the general recomendation made by OWASP[17] to prevent XSRF attacks. There are of course other methods, but none as effective.

##Other Security Issues /Bugs

##Deleting Messages through faked POST
This does not in any way pose a serious security threat in my eyes, although it is an action which any user should not be able to do. However, by simulating a POST via the Chrome Extension POSTMAN, one can by simply examining the sourcecode and extracting the ID of a message delete that message by doing a POST to /message/delete.

##Getting the raw JSON information by GET to /message/data
Again, this is in no way a serious threat to the users, as it simply presents the raw JSON data instead of the HTML formatted data representing a message. If this were to be some other, more sensitive data, however, it would be ill-advised to use XHR to get and POST to urls in such a way.

##Broken Session For Logout
When logged out, one can access the /messages page again by returning to that URL. One cannot, however, post comments. 


###References

 [1] “Front-End Optimization (FEO)”, Techopedia, 2015 [Online] Available: [Technopedia] (https://www.techopedia.com/definition/1515/front-end-optimization-feo [Downloaded: 3 December, 2015].)
 
[2] “Best Practices for Speeding Up Your Web Site" Yahoo Developer Network [Online] Available: [Yahoo Developer Network] (https://developer.yahoo.com/performance/rules.html#num_http=)  [Downloaded: 3 December, 2015]

[3] S. Souders, “High Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.36,December 2008. [Online] Available: [OneSearch] (http://eds.b.ebscohost.com.proxy.lnu.se/eds/detail/detail?vid=4&sid=57778c72-0d7c-4867-9509-d6effdd1f1bb%40sessionmgr114&hid=108&bdata=Jmxhbmc9c3Ymc2l0ZT1lZHMtbGl2ZSZzY29wZT1zaXRl#AN=35609277&db=buh) [Downloaded: 3 december, 2015].

[4] S. Souders, “High Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.37-38,December 2008. [Online] Available: [OneSearch] (http://eds.b.ebscohost.com.proxy.lnu.se/eds/detail/detail?vid=4&sid=57778c72-0d7c-4867-9509-d6effdd1f1bb%40sessionmgr114&hid=108&bdata=Jmxhbmc9c3Ymc2l0ZT1lZHMtbGl2ZSZzY29wZT1zaXRl#AN=35609277&db=buh) [Downloaded: 3 december, 2015].

[5] “Best Practices for Speeding Up Your Web Site" Yahoo Developer Network [Online] Available: [Yahoo Developer Network](https://developer.yahoo.com/performance/rules.html#num_http=)  [Downloaded: 3 December, 2015]

[6] I. Grigorik, “High Preformance Browser Networking”, O'Reilly Media, 2013. [E-Bok] Available: [Chimera] (http://chimera.labs.oreilly.com/books/1230000000545/index.html) [Downloaded: 3 december, 2015].

[7] [4] S. Souders, “High Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.39,December 2008. [Online] Available: [OneSearch] (http://eds.b.ebscohost.com.proxy.lnu.se/eds/detail/detail?vid=4&sid=57778c72-0d7c-4867-9509-d6effdd1f1bb%40sessionmgr114&hid=108&bdata=Jmxhbmc9c3Ymc2l0ZT1lZHMtbGl2ZSZzY29wZT1zaXRl#AN=35609277&db=buh) [Downloaded: 3 december, 2015].

[8] [4] S. Souders, “High Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.39,December 2008. [Online] Available: [OneSearch] (http://eds.b.ebscohost.com.proxy.lnu.se/eds/detail/detail?vid=4&sid=57778c72-0d7c-4867-9509-d6effdd1f1bb%40sessionmgr114&hid=108&bdata=Jmxhbmc9c3Ymc2l0ZT1lZHMtbGl2ZSZzY29wZT1zaXRl#AN=35609277&db=buh) [Downloaded: 3 december, 2015].

[9] “The GZip HomePage” 2003.[Online] Available:  [GZIP.org](http://www.gzip.org/) [Downloaded: 3 december, 2015].

[10] [4] S. Souders, “High Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.39,December 2008. [Online] Available: [OneSearch] (http://eds.b.ebscohost.com.proxy.lnu.se/eds/detail/detail?vid=4&sid=57778c72-0d7c-4867-9509-d6effdd1f1bb%40sessionmgr114&hid=108&bdata=Jmxhbmc9c3Ymc2l0ZT1lZHMtbGl2ZSZzY29wZT1zaXRl#AN=35609277&db=buh) [Downloaded: 3 december, 2015].

[11] “Best Practices for Speeding Up Your Web Site" Yahoo Developer Network [Online] Available: [Yahoo Developer Network](https://developer.yahoo.com/performance/rules.html#num_http=)  [Downloaded: 3 December, 2015]

[12] S. Souders, “High Preformance Websites”,Communications of the AMC, . vol,2008, Vol. 51 Issue 12, p.37-39,December 2008. [Online] Available: [OneSearch] (http://eds.b.ebscohost.com.proxy.lnu.se/eds/detail/detail?vid=4&sid=57778c72-0d7c-4867-9509-d6effdd1f1bb%40sessionmgr114&hid=108&bdata=Jmxhbmc9c3Ymc2l0ZT1lZHMtbGl2ZSZzY29wZT1zaXRl#AN=35609277&db=buh) [Downloaded: 3 december, 2015].

[13] I.Balasundram, E.Ramaraj, "Prevention of SQL Injection Attacks by Using Service Oriented Authentication Technique",International Journal of Modeling and Optimization June 2013, vol.3, no.3, pp. 302-3. ISSN: 2010-3697 (print), Publisher: IACSIT Press Country of Publication: Singapore [Online] Available: [OneSearch](http://www.ijmo.org/papers/286-S385.pdf)

[14] I.Balasundram, E.Ramaraj, "Prevention of SQL Injection Attacks by Using Service Oriented Authentication Technique",International Journal of Modeling and Optimization June 2013, vol.3, no.3, pp. 303. ISSN: 2010-3697 (print), Publisher: IACSIT Press Country of Publication: Singapore [Online] Available: [OneSearch](http://www.ijmo.org/papers/286-S385.pdf)

[15]"Cross-Site Request Forgery (CSRF)", 2015. [Online] Available [owasp.org](https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF))

[16]"XSS (Cross Site Scripting) Prevention Cheat Sheet",2015 [Online] Available [owasp.org](https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet)

[17]"Cross-Site Request Forgery (CSRF)", 2015. [Online] Available [owasp.org](https://www.owasp.org/index.php/CSRF_Prevention_Cheat_Sheet)
