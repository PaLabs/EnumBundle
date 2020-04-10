# Symfony bundle for PaLabs/php-enum

Bundle provide integration PaLabs/php-enum with symfony

## Features
- Enum form type 
- Enum translator (with twig extension)
- Doctrine integration (enum field type, types auto generation)

## Installation
1. Require bundle using composer
```
composer require palabs/enum-bundle
```

2. Add bundle to bundle list (file bundles.php in symfony 5)
```php
return [
    PaLabs\EnumBundle\PaEnumBundle::class => ['all' => true],
];
```

3. Create configuration (in your config dir)
```yaml
pa_enum:
  translator:
    domain: 'enums'
  doctrine:
    path:
      - '%kernel.project_dir%/src'
```

## Usage

### Translator
EnumTranslator class provide methods to translate enum values. Example:
```php
use PaLabs\Enum\Enum;
use \PaLabs\EnumBundle\Translator\EnumTranslator;
use Symfony\Component\HttpFoundation\Request;

class ActionEnum extends Enum {
    public static ActionEnum $VIEW, $EXIT;
}
ActionEnum::init();

class FooController {
    private EnumTranslator $enumTranslator;

    public function __construct(EnumTranslator $enumTranslator) {
        $this->enumTranslator = $enumTranslator;           
    }

    public function fooAction(Request $request) {
        return $this->enumTranslator->translate(ActionEnum::$VIEW);
    }

}
```

### Enum form

Class EnumType provide symfony form type for your forms. Example:
```php

use PaLabs\EnumBundle\Form\EnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MyForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder,array $options){
        $builder->add('field', EnumType::class, [
            'type'=>ActionEnum::class,
             'required' => true,        
        ]);
    }
}
```

### Doctrine type

Bundle provide a mechanism for doctrine type generation based on you enums. 
For example, if you have an entity class:

```php

use Doctrine\ORM\Mapping as ORM;
use PaLabs\Enum\Enum;

class BookType extends Enum {
    public static BookType $MONOGRAPHY, $THESES, $OTHER:
}
BookType::init();

class Book {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;
    
    /**
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private string $name = '';
    
    /**
      * @ORM\Column(name="type", type=BookType::class, nullable=false)
     */
    private BookType $type;
    
    public function __construct() {
        $this->type = BookType::$MONOGRAPHY;
    }
}
```

Bundle will automaticly generate a doctrine type for BookType enum and add it to doctrine types config.
You can specify paths where enums will be found:
```yaml
pa_enum:
  doctrine:
    path:
      - '%kernel.project_dir%/src'
      - '%kernel.project_dir%/vendor/my-bundle/src'
```