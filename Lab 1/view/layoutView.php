<?php

class LayoutView
{
	 /**
  * renders HTML output
  * @param LoginView || RegView
  * @param DateTimeView
  * @return void
  */
  public function render($view) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Webteknik-II Lab 1</title>
        </head>
        <body>
          <h1>Webteknik-II Lab 1</h1>          
          <h2>Webbcrawler</h2>
          
          <div class="container">              
              ' . $view->response . '            
          </div>
         </body>
      </html>
    ';
  }
}