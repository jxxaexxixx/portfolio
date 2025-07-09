# twig-extension-callfunc
Twig extension to call PHP functions or static methods from templates

Installation:
```php
# $twig being your Twig_Environment object
$twig->addExtension(new \ProGest\Twig\CallFuncExtension());
```

Usage:
```
# Call function
{{ callfunc('myFunction') }}

# Call static method
{{ callfunc('myClass::myStaticMethod') }}

# Pass one or multiple arguments
{{ callfunc('myFunction', 8546) }}
{{ callfunc('myFunction', ['testValue', 9897]) }}
```