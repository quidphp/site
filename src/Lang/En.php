<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
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
                'media'=>'Media',
                'page'=>'Page',
                'section'=>'Section']
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'googleMaps'=>'Google Maps',
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
                'label'=>'Label',
                'required'=>'Required',
                'description'=>'Description',
                'type'=>'Type',
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
                    'media'=>'Manages media like photos, videos and files.',
                    'page'=>'Pages accessible via the application',
                    'section'=>'Page grouping']
            ],

            // col
            'col'=>[

                // description
                'description'=>[

                    // *
                    '*'=>[
                        'googleMaps'=>'Enter a full address as in Google Maps, use commas',
                        'youTube'=>'Enter a full YouTube URL',
                        'vimeo'=>'Enter a full Vimeo URL',
                    ],

                    // section
                    'section'=>[
                        'page_id'=>'Root page of the section',
                        'page_ids'=>'First-level pages of the section'
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
                    'checkbox'=>'Checkboxes (multiple choices)'
                ]
            ]
        ]
    ];
}

// init
En::__init();
?>