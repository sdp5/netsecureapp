; <?php die(); ?>

; IDS Config.ini

; General configuration settings

; !!!DO NOT PLACE THIS FILE INSIDE THE WEB-ROOT IF DATABASE CONNECTION DATA WAS ADDED!!!

[General]

    ; basic settings - customize to make the IDS work at all
    filter_type     = xml
    
    base_path       = /full/path/to/IDS/ 
    use_base_path   = false
    
    filter_path     = default_filter.xml
    tmp_path        = tmp
    scan_keys       = false
    
    HTML_Purifier_Path	= vendors/htmlpurifier/HTMLPurifier.auto.php
    HTML_Purifier_Cache = vendors/htmlpurifier/HTMLPurifier/DefinitionCache/Serializer
    
    ; define which fields contain html and need preparation before 
    ; hitting the PHPIDS rules (new in PHPIDS 0.5)
    html[]          = POST.__wysiwyg
    
    ; define which fields shouldn't be monitored (a[b]=c should be referenced via a.b)
    exceptions[]    = GET.__utmz
    exceptions[]    = GET.__utmc

    min_php_version = 5.1.6

; If you use the PHPIDS logger you can define specific configuration here

[Logging]

    ; file logging
    path            = tmp/phpids_log.txt

    ; email logging

    ; note that enabling safemode you can prevent spam attempts,
    ; see documentation
    recipients[]    = try@demo.user
    subject         = "IDS detected an intrusion attempt!"
    header			= "From: <Netsecureapp> info@netsecureapp.in"
    envelope        = ""
    safemode        = true
    urlencode       = true
    allowed_rate    = 15

    ; database logging

    wrapper         = "mysql:host=localhost;port=3306;dbname=netsecureapp"
    user            =  root
    password        =  
    table           =  nsa_intrusions