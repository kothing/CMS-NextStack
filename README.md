# NextStack

NextStack CMS

## URL Rewrite
**Nginx**  
```
location / {
  if (!-e $request_filename){
    rewrite  ^(.*)$ /index.php?s=$1 last; break;
  }
}
```

**Apache**  
```
<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteBase /
  #RewriteCond %{REQUEST_URI} !((.*).jpg|.jpeg|.bmp|.gif|.png|.js|.css|.tts|.woff )$
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} !-d
  RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} !-f
  #RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
  RewriteRule ^(.*)$ index.php [E=PATH_INFO:$1,QSA,PT,L]
</IfModule>
```

**IIS**  
```
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
        <rule name="allow_rules" enabled="true" stopProcessing="true">
          <match url="^(.*)$" ignoreCase="false" />
          <conditions logicalGrouping="MatchAll" trackAllCaptures="false">
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
            <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Rewrite" url="index.php/{R:1}" appendQueryString="true" />
        </rule>
      </rules>
    </rewrite>
  </system.webServer>
</configuration>
```