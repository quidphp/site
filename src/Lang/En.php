<?php 
declare(strict_types=1);
namespace Quid\Site\Lang;
use Quid\Lemur;

// en
class En extends Lemur\Lang\En
{
	// config
	public static $config = array(
		
		// newsletter
		'newsletter'=>array(
			'email'=>'Email',
			'firstName'=>'First name',
			'lastName'=>'Last name'
		),
		
		// table
		'table'=>array(
			
			// label
			'label'=>array(
				'contact'=>'Contact',
				'document'=>'Document',
				'event'=>'Event',
				'eventSubmit'=>'Event - Subscription',
				'form'=>'Form',
				'formSubmit'=>'Form - Answer',
				'media'=>'Media',
				'news'=>'News',
				'page'=>'Page',
				'pageContent'=>'Contenu de page',
				'poll'=>'Poll',
				'pollSubmit'=>'Poll - Answer',
				'section'=>'Section')
		),
		
		// col
		'col'=>array(
			
			// label
			'label'=>array(
				
				// *
				'*'=>array(
					'googleMaps'=>'Google Maps',
					'youTube'=>"YouTube",
					'vimeo'=>"Vimeo",
					'newsletter'=>"Newsletter"
				),
				
				// contact 
				'contact'=>array(
					'email'=>'Email',
					'name'=>'Full name',
					'phone'=>'Phone number'
				)
			)
		),
		
		// com
		'com'=>array(
			
			// neg
			'neg'=>array(
				
				// newsletter
				'newsletter'=>array(
					
					// subscribe
					'subscribe'=>array(
						'failure'=>"Error while adding your email to our mailing list.",
						'duplicate'=>"Your email is already in our mailing list."
					)
				),
				
				// insert
				'insert'=>array(
					'contact'=>array(
						'failure'=>"Error sending the message."
					)
				),
				
				// eventSubmit
				'eventSubmit'=>array(
					'duplicate'=>"This user already subscribed to the event"
				),
				
				// pollSubmit
				'pollSubmit'=>array(
					'duplicate'=>'This user already voted on that poll'
				),
				
				// formSubmit
				'formSubmit'=>array(
					'duplicate'=>'This user already submitted the form'
				)
			),
			
			// pos
			'pos'=>array(
				
				// newsletter
				'newsletter'=>array(
					
					// subscribe
					'subscribe'=>array(
						'success'=>"Your email has been added to our mailing list. You will receive a confirmation email shortly."
					)
				),
				
				// insert
				'insert'=>array(
					'contact'=>array(
						'success'=>"Thank you, the message was sent !"
					)
				)
			)
		),
		
		// route
		'route'=>array(
			
			// label
			'label'=>array(
				'contactSubmit'=>'Contact - Submit',
				'newsletterSubmit'=>'Newsletter - Subscribe'
			)
		),
		
		// cms
		'@cms'=>array(
			
			// jsonForm
			'jsonForm'=>array(
				'label'=>'Label',
				'required'=>'Required',
				'description'=>'Description',
				'type'=>'Type',
				'choices'=>'Answer choice (one per line)',
			),
			
			// emailNewsletter
			'emailNewsletter'=>array(
				'label'=>'Subscribed on the newsletter'
			),
			
			// hierarchy
			'hierarchy'=>array(
				'noParent'=>'No parent'
			),

			// table
			'table'=>array(
				
				// description
				'description'=>array(
					'contact'=>'Archive messages sent via the contact page form',
					'document'=>'Manage document files',
					'event'=>'Manage and create events',
					'eventSubmit'=>'Registration of users to events',
					'form'=>'Manage and create forms',
					'formSubmit'=>'User responses to forms',
					'media'=>'Manages media like photos, videos and files.',
					'news'=>'Manage news',
					'page'=>"Pages accessible via the application",
					'pageContent'=>"Contenu to show in the different pages",
					'poll'=>'Manage and create polls',
					'pollSubmit'=>'Contains all votes in different polls',
					'section'=>"Page grouping")
			),
			
			// col
			'col'=>array(
				
				// description
				'description'=>array(
					
					// *
					'*'=>array(
						'googleMaps'=>'Enter a full address as in Google Maps, use commas',
						'youTube'=>'Enter a full YouTube URL',
						'vimeo'=>'Enter a full Vimeo URL',
					),
					
					// section
					'section'=>array(
						'page_id'=>'Root page of the section',
						'page_ids'=>'First-level pages of the section'
					),
					
					// page
					'page'=>array(
						'page_id'=>'Parent page of the current page',
						'pageContent_ids'=>'Specifies the contents of the page'
					),
					
					// contact
					'contact'=>array(
						'message'=>'Content of the message'
					)
				)
			),
			
			// relation
			'relation'=>array(
				
				// jsonForm 
				'jsonForm'=>array(
					'inputText'=>'Text input',
					'textarea'=>'Textarea',
					'select'=>'Select menu',
					'radio'=>'Radio buttons (one choice)',
					'checkbox'=>'Checkboxes (multiple choices)'
				)
			),
			
			// tinycme
			'tinymce'=>array(
				'paragraph'=>'Paragraph',
				'superscript'=>'Superscript',
				'header2'=>'Header 2',
				'header3'=>'Header 3',
				'header4'=>'Header 4',
				'header5'=>'Header 5',
				'alignLeft'=>'Align left',
				'alignCenter'=>'Align center',
				'alignRight'=>'Align right',
				'floatLeft'=>'Float left',
				'floatRight'=>'Float right'
			)
		)
	);
}

// config
En::__config();
?>