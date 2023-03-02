<?php

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

return [
    'accepted'             => 'Այս դաշտը պետք է ընդունվի։',
    'accepted_if'          => 'Այս դաշտը պետք է ընդունվի երբ :other համապատասխանում է :value։',
    'active_url'           => 'Այս դաշտը վավեր URL չէ։',
    'after'                => 'Այս դաշտի ամսաթիվը պետք է լինի :date-ից հետո։',
    'after_or_equal'       => 'Այս դաշտի ամսաթիվը պետք է լինի :date կամ դրանից հետո։',
    'alpha'                => 'Այս դաշտը պետք է պարունակի միայն տառեր։',
    'alpha_dash'           => 'Այս դաշտը պետք է պարունակի միայն տառեր, թվեր, գծիկներ և ընդգծումներ։',
    'alpha_num'            => 'Այս դաշտը պետք է պարունակի միայն տառեր և թվեր։',
    'array'                => 'Այս դաշտը պետք է լինի զանգված։',
    'before'               => 'Այս դաշտի ամսաթիվը պետք է լինի :date-ից առաջ։',
    'before_or_equal'      => 'Այս դաշտի ամսաթիվը պետք է լինի :date կամ դրանից առաջ։',
    'between'              => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը պետք է լինի :min-ի և :max-ի միջև։',
        'file'    => 'Այս դաշտի ֆայլի չափը պետք է լինի :min և :max կիլոբայթի միջև։',
        'numeric' => 'Այս դաշտի արժեքը պետք է լինի :min և :max թվերի միջև։',
        'string'  => 'Այս դաշտի արժեքը պետք է ունենա :min-ից :max նիշ։',
    ],
    'boolean'              => 'Այս դաշտի արժեքը պետք է լինի ճշմարիտ կամ կեղծ։',
    'confirmed'            => 'Հաստատումը չի համապատասխանում։',
    'current_password'     => 'Այս դաշտը պարունակում է անվավեր գաղտնաբառ։',
    'date'                 => 'Այս դաշտի արժեքը վավեր ամսաթիվ չէ։',
    'date_equals'          => 'Այս դաշտում ամսաթիվը պետք է լինի :date։',
    'date_format'          => 'Այս դաշտի արժեքը չի համապատասխանում :format ձևաչափին։',
    'declined'             => 'This value must be declined.',
    'declined_if'          => 'This value must be declined when :other is :value.',
    'different'            => 'Այս և :other դաշտերը պետք է լինեն տարբեր։',
    'digits'               => 'Այս դաշտի թվանշանների քանակը պետք է լինի :digits։',
    'digits_between'       => 'Այս դաշտի թվանշանների քանակը պետք է լինի :min-ից :max։',
    'dimensions'           => 'Այս դաշտի տվյալը ունի անվավեր նկարի չափեր։',
    'distinct'             => 'Այս դաշտի արժեքը կրկնվում է։',
    'email'                => 'Այս դաշտի արժեքը պետք է լինի վավեր Էլ․ հասցե։',
    'ends_with'            => 'Այս դաշտը պետք է ավարտվի հետևյալ արժեքներից մեկով․ :values։',
    'enum'                 => 'The selected value is invalid.',
    'exists'               => 'Ընտրված արժեքն անվավեր է։',
    'file'                 => 'Մուտքագրաված տվյալը պետք է լինի ֆայլ։',
    'filled'               => 'Այս դաշտը պետք է անպայման լրացված լինի։',
    'gt'                   => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը պետք է լինի :value-ից մեծ։',
        'file'    => 'Այս դաշտի ֆայլի չափը պետք է լինի :value կիլոբայթից մեծ։',
        'numeric' => 'Այս դաշտը պետք է լինի :value-ից մեծ։',
        'string'  => 'Այս դաշտի նիշերի քանակը պետք է գերազանցի :value-ը։',
    ],
    'gte'                  => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը պետք է մեծ կամ հավասար լինի :value-ից։',
        'file'    => 'Այս դաշտի ֆայլի չափը պետք է մեծ կամ հավասար լինի :value կիլոբայթից։',
        'numeric' => 'Այս դաշտը պետք է մեծ կամ հավասար լինի :value-ից։',
        'string'  => 'Այս դաշտի նիշերի քանակը պետք է մեծ կամ հավասար լինի :value-ից։',
    ],
    'image'                => 'Այս դաշտը պետք է լինի նկար։',
    'in'                   => 'Այս դաշտի ընտրված արժեքն անվավեր է։',
    'in_array'             => 'Այս դաշտը գոյություն չունի :other-ում։',
    'integer'              => 'Այս դաշտը պետք է լինի ամբողջ թիվ։',
    'ip'                   => 'Այս դաշտը պետք է լինի վավեր IP հասցե.',
    'ipv4'                 => 'Այս դաշտը պետք է լինի վավեր IPv4 հասցե։',
    'ipv6'                 => 'Այս դաշտը պետք է լինի վավեր IPv6 հասցե։',
    'json'                 => 'Այս դաշտը պետք է լինի վավեր JSON տեքստ։',
    'lt'                   => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը պետք է փոքր լինի :value-ից։',
        'file'    => 'Այս դաշտի ֆայլի չափը պետք է փոքր լինի :value կիլոբայթից։',
        'numeric' => 'Այս դաշտը պետք է փոքր լինի :value-ից։',
        'string'  => 'Այս դաշտը պետք է ունենա :value-ից պակաս նիշեր։',
    ],
    'lte'                  => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը պետք է փոքր կամ հավասար լինի :value-ից։',
        'file'    => 'Այս դաշտի ֆայլի չափը պետք է փոքր կամ հավասար լինի :value կիլոբայթից։',
        'numeric' => 'Այս դաշտը պետք է փոքր կամ հավասար լինի :value-ից։',
        'string'  => 'Այս դաշտի նիշերի քանակը պետք է փոքր կամ հավասար լինի :value-ից։',
    ],
    'mac_address'          => 'The value must be a valid MAC address.',
    'max'                  => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը չպետք է գերազանցի :max-ը։',
        'file'    => 'Այս դաշտի ֆայլի չափը չպետք է գերազանցի :max կիլոբայթը։',
        'numeric' => 'Այս դաշտը չի կարող լինել :max-ից մեծ։',
        'string'  => 'Այս դաշտի նիշերի քանակը չի կարող լինել :max-ց մեծ։',
    ],
    'mimes'                => 'Այս դաշտի ֆայլի տեսակը պետք է լինի հետևյալներից մեկը․ :values։',
    'mimetypes'            => 'Այս դաշտի ֆայլի տեսակը պետք է լինի հետևյալներից մեկը․ :values։',
    'min'                  => [
        'array'   => 'Այս դաշտում էլեմենտների քանակը պետք է լինի առնվազն :min։',
        'file'    => 'Այս դաշտի ֆայլի չափը պետք է լինի առնվազն :min կիլոբայթ։',
        'numeric' => 'Այս դաշտը պետք է լինի առնվազն :min։',
        'string'  => 'Այս դաշտի նիշերի քանակը պետք է լինի առնվազն :min։',
    ],
    'multiple_of'          => 'Այս դաշտի արժեքը պետք է լինի բազմապատիկ :value-ին։',
    'not_in'               => 'Այս դաշտի ընտրված արժեքն անվավեր է։',
    'not_regex'            => 'Այս դաշտի ձևաչափը սխալ է։',
    'numeric'              => 'Այս դաշտը պետք է լինի թիվ։',
    'password'             => 'Գաղտնաբառը սխալ է։',
    'present'              => 'Այս դաշտը պետք է առկա լինի։',
    'prohibited'           => 'Այս դաշտը արգելված է։',
    'prohibited_if'        => 'Այս դաշտը արգելված է երբ :other դաշտի արժեքը :value է։',
    'prohibited_unless'    => 'Այս դաշտը արգելված է քանի դեռ :other դաշտի արժեքը :values միջակայքում չի։',
    'prohibits'            => 'This field prohibits :other from being present.',
    'regex'                => 'Այս դաշտի ձևաչափը սխալ է։',
    'required'             => 'Այս դաշտը պարտադիր է։',
    'required_array_keys'  => 'This field must contain entries for: :values.',
    'required_if'          => 'Այս դաշտը պարտադիր է երբ :other-ը հավասար է :value։',
    'required_unless'      => 'Այս դաշտը պարտադիր է քանի դեռ :other-ը հավասար չէ :values։',
    'required_with'        => 'Այս դաշտը պարտադիր է երբ :values արժեքն առկա է։',
    'required_with_all'    => 'Այս դաշտը պարտադիր է երբ :values արժեքները առկա են։',
    'required_without'     => 'Այս դաշտը պարտադիր է երբ :values արժեքը նշված չէ։',
    'required_without_all' => 'Այս դաշտը պարտադիր է երբ :values արժեքներից ոչ մեկը նշված չեն։',
    'same'                 => 'Այս և :other դաշտերը պետք է համընկնեն։',
    'size'                 => [
        'array'   => 'Այս դաշտը պետք է պարունակի :size էլեմենտ։',
        'file'    => 'Այս դաշտում ֆայլի չափը պետք է լինի :size կիլոբայթ։',
        'numeric' => 'Այս դաշտը պետք է հավասար լինի :size-ի։',
        'string'  => 'Այս դաշտը պետք է ունենա :size նիշ։',
    ],
    'starts_with'          => 'Այս դաշտը պետք է սկսվի հետևյալ արժեքներից մեկով․ :values։',
    'string'               => 'Այս դաշտը պետք է լինի տեքստ։',
    'timezone'             => 'Այս դաշտը պետք է լինի վավեր ժամային գոտի։',
    'unique'               => 'Այս դաշտի մուտքագրված արժեքն արդեն գոյություն ունի։',
    'uploaded'             => 'Այս դաշտի վերբեռնումը ձախողվել է։',
    'url'                  => 'Այս դաշտի ձևաչափը սխալ է։',
    'uuid'                 => 'Այս դաշտը պետք է լինի վավեր UUID։',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
];
