{
"name": "{{composerName}}",
"description": "{{description}}",
"autoload": {
"psr-4": {
"{{namespace_escaped}}\\": "src/"
}
},
"config": {
"prepend-autoloader": false
},
"type": "prestashop-module"
}