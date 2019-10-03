<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Lang;
use Quid\Lemur;

// fr
// french language content used by this namespace
class Fr extends Lemur\Lang\Fr
{
    // config
    public static $config = [

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
                'contact'=>'Contact',
                'media'=>'Médiathèque',
                'page'=>'Page',
                'section'=>'Section']
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'googleMaps'=>'Géo-localisation',
                    'youTube'=>'YouTube',
                    'vimeo'=>'Vimeo',
                    'newsletter'=>'Infolettre'
                ],

                // contact
                'contact'=>[
                    'email'=>'Adresse courriel',
                    'name'=>'Prénom et nom',
                    'phone'=>'Numéro de téléphone'
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
                ],

                // insert
                'insert'=>[
                    'contact'=>[
                        'failure'=>"Erreur lors de l'envoie du message."
                    ]
                ],

                // eventSubmit
                'eventSubmit'=>[
                    'duplicate'=>"Cet utilisateur est déjà inscrit à l'événement"
                ],

                // pollSubmit
                'pollSubmit'=>[
                    'duplicate'=>'Cet utilisateur a déjà voté sur ce sondage'
                ],

                // formSubmit
                'formSubmit'=>[
                    'duplicate'=>'Cet utilisateur a déjà répondu au formulaire'
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
                ],

                // insert
                'insert'=>[
                    'contact'=>[
                        'success'=>'Merci, le message a été envoyé !'
                    ]
                ]
            ]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'contactSubmit'=>'Contact - Soumettre',
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
                'label'=>'Libellé',
                'required'=>'Requis',
                'description'=>'Description',
                'type'=>'Type',
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
                    'contact'=>'Archive des messages envoyés via le formulaire de la page de contact',
                    'media'=>'Gère les médias comme les photos, vidéos et fichiers.',
                    'page'=>"Pages accessibles via l'application",
                    'section'=>'Regroupement de pages']
            ],

            // col
            'col'=>[

                // description
                'description'=>[

                    // *
                    '*'=>[
                        'googleMaps'=>'Entrer une adresse complète comme dans Google Maps, utiliser des virgules.',
                        'youTube'=>'Entrer une URL YouTube complète',
                        'vimeo'=>'Entrer une URL Vimeo complète'
                    ],

                    // section
                    'section'=>[
                        'page_id'=>'Spécifie la page racine de la section',
                        'page_ids'=>'Spécifie les pages de premier niveau de la section'
                    ],

                    // page
                    'page'=>[
                        'page_id'=>'Spécifie la page parent de la page',
                        'pageContent_ids'=>'Spécifie les contenus de la page'
                    ],

                    // contact
                    'contact'=>[
                        'message'=>'Contenu du message'
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
                    'checkbox'=>'Cases à cocher (multiples choix)'
                ]
            ],

            // tinycme
            'tinymce'=>[
                'paragraph'=>'Paragraphe',
                'superscript'=>'Exposant',
                'header2'=>'En-tête 2',
                'header3'=>'En-tête 3',
                'header4'=>'En-tête 4',
                'header5'=>'En-tête 5',
                'header6'=>'En-tête 6',
                'alignLeft'=>'Aligner à gauche',
                'alignCenter'=>'Aligner au centre',
                'alignRight'=>'Aligner à droite',
                'floatLeft'=>'Flotter à gauche',
                'floatRight'=>'Flotter à droite'
            ]
        ]
    ];
}

// init
Fr::__init();
?>