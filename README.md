Engineer Assignment
===================


Developer: Filippo Malvisi

E-Mail: comalv@gmail.com


Tools Used:

OS: Windows 7 SP1 x64

Editor: Notepad++ v5.9.6.1

Localhost webserver: EasyPHP DevServer v14.1 (Apache v2.4.7, PHP v5.5.8)

Web Browser: Google Chrome v37.0.2062.124 m


Functions implemented:

Required: initialization(), signIn(), signOut(), destroy()

Optional: getCatalogList(), getCatalog()


Interface changes:

initialization: removed '$secret' as it does not seem to be used in initialization (as it seems to be only useful with a token, as explained in notes.pdf), added '$v1' for API v1 authentication

destroy: changed to '$token' and '$secret' as they are the only things required to destroy the session

getCatalogList: forced '$options' to be an array, added '$token' and '$secret' because they are required to perform the request but they should be separated from the rest as they are session/user related and not request related (ie: they have nothing to do with catalogs)

getCatalog: same as getCatalogList



Testing Page Usage:
access /test/index.php for testing. Be sure to fill in the default credentials before starting and if you can edit the catalog-related testing info. For more information (also regarding '$verbose') check notes.pdf. A successful test should not return any errors.


A quick note on token renewal: As I also state in notes.pdf I feel like there should be a function somewhere that checks for an exesting session, provided you have a token and tries to use that. Also this function should be responsible for checking the expiration date on the current token and renewing it if it is close to expiring. Since there is no interface for it (and it should definitely be a public function) I did not implement one, but it should be there for a complete SDK. I did not want to include it in initalization() because I feel its name would become misleading.


For everything else there is notes.pdf and for everything that might not be covered there, you can reach me by email.
