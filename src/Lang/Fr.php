<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Lang;
use Quid\Lemur;

// fr
// french language content used by this namespace
class Fr extends Lemur\Lang\Fr
{
    // config
    protected static array $config = [

        // newsletter
        'newsletter'=>[
            'email'=>'Courriel',
            'firstName'=>'Prénom',
            'lastName'=>'Nom de famille'
        ],

        // table
        'table'=>[

            // label
            'label'=>[
                'page'=>'Page']
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'googleMaps'=>'Géo-localisation',
                    'embed'=>'Vidéo',
                    'youTube'=>'YouTube',
                    'vimeo'=>'Vimeo',
                    'newsletter'=>'Infolettre'
                ],

                // page
                'page'=>[
                    'page_id'=>'Page parent'
                ]
            ]
        ],

        // com
        'com'=>[

            // neg
            'neg'=>[

                // newsletter
                'newsletter'=>[

                    // subscribe
                    'subscribe'=>[
                        'failure'=>"Erreur lors de l'ajout de votre courriel à notre liste d'envoi.",
                        'duplicate'=>"Votre courriel est déjà dans notre liste d'envoi."
                    ]
                ]
            ],

            // pos
            'pos'=>[

                // newsletter
                'newsletter'=>[

                    // subscribe
                    'subscribe'=>[
                        'success'=>"Votre courriel a été ajouté à notre liste d'envoi. Vous recevrez sous peu un courriel de confirmation."
                    ]
                ]
            ]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'newsletterSubmit'=>'Infolettre - Soumettre',
            ]
        ],

        // relation
        'relation'=>[

            // contextType
            'contextType'=>[
                'app'=>'Application'
            ]
        ],

        // cms
        '@cms'=>[

            // jsonForm
            'jsonForm'=>[
                'type'=>'Type',
                'label'=>'Libellé',
                'description'=>'Description',
                'required'=>'Requis',
                'minLength'=>'Longueur minimale',
                'maxLength'=>'Longueur maximale',
                'choices'=>'Choix de réponse (un par ligne)',
            ],

            // emailNewsletter
            'emailNewsletter'=>[
                'label'=>"Inscrit sur l'infolettre"
            ],

            // hierarchy
            'hierarchy'=>[
                'noParent'=>'Aucun parent'
            ],

            // table
            'table'=>[

                // description
                'description'=>[
                    'page'=>"Pages accessibles via l'application"]
            ],

            // col
            'col'=>[

                // description
                'description'=>[

                    // *
                    '*'=>[
                        'googleMaps'=>'Entrer une adresse complète comme dans Google Maps, utiliser des virgules.',
                        'embed'=>'Entrer une URL YouTube ou Vimeo',
                        'youTube'=>'Entrer une URL YouTube complète',
                        'vimeo'=>'Entrer une URL Vimeo complète'
                    ],

                    // page
                    'page'=>[
                        'page_id'=>'Spécifie la page parent de la page'
                    ]
                ]
            ],

            // relation
            'relation'=>[

                // jsonForm
                'jsonForm'=>[
                    'inputText'=>'Champ texte',
                    'textarea'=>'Champ texte long',
                    'select'=>'Menu de sélection',
                    'radio'=>'Boutons radios (un choix)',
                    'checkbox'=>'Cases à cocher (multiples choix)',
                    'inputFile'=>'Champ fichier',
                    'separator'=>'Séparateur de section'
                ]
            ]
        ]
    ];
}

// init
Fr::__init();
?>