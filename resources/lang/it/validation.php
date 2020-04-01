<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"             => "Il campo :attribute deve essere accettato.",
    "active_url"           => "Il campo :attribute non &egrave; un URL valido.",
    "after"                => "Il campo :attribute deve essere una data successiva a :date.",
    "alpha"                => "Il campo :attribute pu&ograve; contenere unicamente lettere.",
    "alpha_dash"           => "Il campo :attribute pu&ograve; contenere solo lettere, numeri e punteggiatura.",
    "alpha_num"            => "Il campo :attribute pu&ograve; contenere unicamente lettere e numeri.",
    "array"                => "Il campo :attribute deve essere un array.",
    "before"               => "Il campo :attribute deve essere una data antecedente al :date.",
    "between"              => [
        "numeric" => "Il valore :attribute deve essere compreso tra :min e :max.",
        "file"    => "Il file :attribute deve avere dimensione minima compresa tra :min e :max kilobytes.",
        "string"  => "La stringa :attribute deve avere lunghezza compresa tra :min e :max caratteri.",
        "array"   => "Il vettore :attribute deve avere tra :min e :max elementi.",
    ],
    "boolean"              => "Il campo :attribute pu&ograve; essere unicamente vero o falso.",
    "confirmed"            => "Il test di validazione su :attribute non ha restituito un valore legale.",
    "date"                 => "Il campo :attribute non &egrave; una data valida.",
    "date_format"          => "Il campo :attribute non possiede il formato :format.",
    "different"            => "Il campo :attribute e il campo :other devono essere differenti.",
    "digits"               => "Il campo :attribute deve essere di :digits cifre.",
    "digits_between"       => "Il campo :attribute deve possedere tra :min e :max cifre.",
    "email"                => "Il campo :attribute deve contenere un indirizzo email valido.",
    "filled"               => "Il campo :attribute &egrave; indispensabile.",
    "exists"               => "Il campo :attribute selezionato non &egrave; valido.",
    "image"                => "Il campo :attribute deve essere un'immagine.",
    "in"                   => "Il campo :attribute selezionato non &egrave; valido.",
    "integer"              => "Il campo :attribute deve essere un numero intero.",
    "ip"                   => "Il campo :attribute deve essere un indirizzo IP valido.",
    "max"                  => [
        "numeric" => "Il valore :attribute non pu&ograve; essere maggiore di :max.",
        "file"    => "Il file :attribute non pu&ograve; essere maggiore di :max kilobytes.",
        "string"  => "La stringa :attribute non pu&ograve; essere maggiore di :max caratteri.",
        "array"   => "Il vettore :attribute non pu&ograve; contenere pi&ugrave; di :max oggetti.",
    ],
    "mimes"                => ":attribute deve essere unicamente un file di tipo: :values.",
    "min"                  => [
        "numeric" => "Il valore :attribute deve essere maggiore di :min.",
        "file"    => "Il file :attribute deve essere maggiore di :min kilobytes.",
        "string"  => "La stringa :attribute deve essere maggiore di :min caratteri.",
        "array"   => "Il vettore :attribute deve essere maggiore di :min elementi.",
    ],
    "not_in"               => "Il campo :attribute selezionato non &egrave; valido.",
    "numeric"              => "Il valore :attribute deve essere un numero.",
    "regex"                => "Il formato di :attribute non &egrave; valido.",
    "required"             => "Il campo :attribute &egrave; obbilgatorio.",
    "required_if"          => "Il campo :attribute &egrave; richiesto nel caso in cui :other sia :value.",
    "required_with"        => "Il campo :attribute &egrave; richiesto nel caso in cui :values sia presente.",
    "required_with_all"    => "Il campo :attribute &egrave; richiesto nel caso in cui :values sia presente.",
    "required_without"     => "Il campo :attribute &egrave; richiesto nel caso in cui :values non sia presente.",
    "required_without_all" => "Il campo :attribute &egrave; richiesto nel caso in cui nessuno dei valori :values sia presente.",
    "same"                 => "Il campo :attribute e il campo :other devono essere identici.",
    "size"                 => [
        "numeric" => "Il valore :attribute deve essere di :size.",
        "file"    => "Il file :attribute deve essere di :size kilobytes.",
        "string"  => "La stringa :attribute deve essere di :size characters.",
        "array"   => "Il vettore :attribute deve contenere :size elementi.",
    ],
    "unique"               => "Il valore per il campo :attribute &egrave; gi&agrave; stato utilizzato.",
    "url"                  => "Il formato di :attribute non &egrave; valido.",
    "timezone"             => "La zona :attribute deve essere una zona valida.",

    "quantity_not_available" => "La quantit&agrave; deve essere inferiore a :attribute",

    "serial_not_found"  => "Il seriale non è presente nel sistema",
    "serial_found"  => "Il seriale è già stato scaricato",
    "product_virtual" => "Non è possibile caricare prodotti virtuali",
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
