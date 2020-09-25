<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Lang;
use Quid\Lemur;

// en
// english language content used by this namespace
class En extends Lemur\Lang\En
{
    // config
    protected static array $config = [

        // newsletter
        'newsletter'=>[
            'email'=>'Email',
            'firstName'=>'First name',
            'lastName'=>'Last name'
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
                    'googleMaps'=>'Google Maps',
                    'embed'=>'Video',
                    'youTube'=>'YouTube',
                    'vimeo'=>'Vimeo',
                    'newsletter'=>'Newsletter'
                ],

                // page
                'page'=>[
                    'page_id'=>'Parent page'
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
                        'failure'=>'Error while adding your email to our mailing list.',
                        'duplicate'=>'Your email is already in our mailing list.'
                    ]
                ]
            ],

            // pos
            'pos'=>[

                // newsletter
                'newsletter'=>[

                    // subscribe
                    'subscribe'=>[
                        'success'=>'Your email has been added to our mailing list. You will receive a confirmation email shortly.'
                    ]
                ]
            ]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'newsletterSubmit'=>'Newsletter - Subscribe'
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
                'label'=>'Label',
                'description'=>'Description',
                'required'=>'Required',
                'minLength'=>'Minimum length',
                'maxLength'=>'Maximum length',
                'choices'=>'Answer choice (one per line)',
            ],

            // emailNewsletter
            'emailNewsletter'=>[
                'label'=>'Subscribed on the newsletter'
            ],

            // hierarchy
            'hierarchy'=>[
                'noParent'=>'No parent'
            ],

            // table
            'table'=>[

                // description
                'description'=>[
                    'page'=>'Pages accessible via the application']
            ],

            // col
            'col'=>[

                // description
                'description'=>[

                    // *
                    '*'=>[
                        'googleMaps'=>'Enter a full address as in Google Maps, use commas',
                        'embed'=>'Enter a YouTube or Vimeo URL',
                        'youTube'=>'Enter a full YouTube URL',
                        'vimeo'=>'Enter a full Vimeo URL',
                    ],

                    // page
                    'page'=>[
                        'page_id'=>'Parent page of the current page'
                    ]
                ]
            ],

            // relation
            'relation'=>[

                // jsonForm
                'jsonForm'=>[
                    'inputText'=>'Text input',
                    'textarea'=>'Textarea',
                    'select'=>'Select menu',
                    'radio'=>'Radio buttons (one choice)',
                    'checkbox'=>'Checkboxes (multiple choices)',
                    'inputFile'=>'File input',
                    'separator'=>'Section separator'
                ]
            ]
        ]
    ];
}

// init
En::__init();
?>