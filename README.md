# laravelAPIDefault  ( Version 5.5 )
is a project contain 
=> for Web pages if found 
    => laravel Localization
    => Debugar Bar
    => Translate Manager => for laravel Localization
=> for API Authentication
    => JWT Authentication
=> for Sending mail 
    => sendgride User
=> for user id encription
    => UUID
    
#Some Instructions
=> for using jwt  you will use them on Authorization  
  =>Bearer Token => the token recived while log in
  
=> to check if this token is authorized and token is true
  =>  $user = JWTAuth::parseToken()->authenticate()

=> if i have page i can open it without user login 
  => JWTAuth::getToken()   this will check if i have the user token  and on this procces i will not send the Authorization Token
   
