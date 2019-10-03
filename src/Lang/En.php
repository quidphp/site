<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Lang;
use Quid\Lemur;

// en
// english language content used by this namespace
class En extends Lemur\Lang\En
{
    // config
    public static $config = [

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
                'contact'=>'Contact',
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

                // contact
                'contact'=>[
                    'email'=>'Email',
                    'name'=>'Full name',
                    'phone'=>'Phone number'
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
                ],

                // insert
                'insert'=>[
                    'contact'=>[
                        'failure'=>'Error sending the message.'
                    ]
                ],

                // eventSubmit
                'eventSubmit'=>[
                    'duplicate'=>'This user already subscribed to the event'
                ],

                // pollSubmit
                'pollSubmit'=>[
                    'duplicate'=>'This user already voted on that poll'
                ],

                // formSubmit
                'formSubmit'=>[
                    'duplicate'=>'This user already submitted the form'
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
                ],

                // insert
                'insert'=>[
                    'contact'=>[
                        'success'=>'Thank you, the message was sent !'
                    ]
                ]
            ]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'contactSubmit'=>'Contact - Submit',
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
                    'contact'=>'Archive messages sent via the contact page form',
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
                        'page_id'=>'Parent page of the current page',
                        'pageContent_ids'=>'Specifies the contents of the page'
                    ],

                    // contact
                    'contact'=>[
                        'message'=>'Content of the message'
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
            ],

            // tinycme
            'tinymce'=>[
                'paragraph'=>'Paragraph',
                'superscript'=>'Superscript',
                'header2'=>'Header 2',
                'header3'=>'Header 3',
                'header4'=>'Header 4',
                'header5'=>'Header 5',
                'header6'=>'Header 6',
                'alignLeft'=>'Align left',
                'alignCenter'=>'Align center',
                'alignRight'=>'Align right',
                'floatLeft'=>'Float left',
                'floatRight'=>'Float right'
            ]
        ]
    ];
}

// init
En::__init();
?>