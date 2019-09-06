<?php 
declare(strict_types=1);
namespace Quid\Site\Lang;
use Quid\Lemur;

// fr
class Fr extends Lemur\Lang\Fr
{
	// config
	public static $config = array(
		
		// newsletter
		'newsletter'=>array(
			'email'=>'Courriel',
			'firstName'=>'Prénom',
			'lastName'=>'Nom de famille'
		),
		
		// table
		'table'=>array(
			
			// label
			'label'=>array(
				'contact'=>'Contact',
				'document'=>'Document',
				'event'=>'Événement',
				'eventSubmit'=>'Événement - Inscription',
				'form'=>'Formulaire',
				'formSubmit'=>'Formulaire - Réponse',
				'media'=>'Médiathèque',
				'news'=>'Nouvelles',
				'page'=>'Page',
				'pageContent'=>'Contenu de page',
				'poll'=>'Sondage',
				'pollSubmit'=>'Sondage - Réponse',
				'section'=>'Section')
		),
		
		// col
		'col'=>array(
			
			// label
			'label'=>array(
				
				// *
				'*'=>array(
					'googleMaps'=>'Géo-localisation',
					'youTube'=>"YouTube",
					'vimeo'=>"Vimeo",
					'newsletter'=>"Infolettre"
				),
				
				// contact 
				'contact'=>array(
					'email'=>'Adresse courriel',
					'name'=>'Prénom et nom',
					'phone'=>'Numéro de téléphone'
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
						'failure'=>"Erreur lors de l'ajout de votre courriel à notre liste d'envoi.",
						'duplicate'=>"Votre courriel est déjà dans notre liste d'envoi."
					)
				),
				
				// insert
				'insert'=>array(
					'contact'=>array(
						'failure'=>"Erreur lors de l'envoie du message."
					)
				),
				
				// eventSubmit
				'eventSubmit'=>array(
					'duplicate'=>"Cet utilisateur est déjà inscrit à l'événement"
				),
				
				// pollSubmit
				'pollSubmit'=>array(
					'duplicate'=>'Cet utilisateur a déjà voté sur ce sondage'
				),
				
				// formSubmit
				'formSubmit'=>array(
					'duplicate'=>'Cet utilisateur a déjà répondu au formulaire'
				)
			),
			
			// pos
			'pos'=>array(
				
				// newsletter
				'newsletter'=>array(
					
					// subscribe
					'subscribe'=>array(
						'success'=>"Votre courriel a été ajouté à notre liste d'envoi. Vous recevrez sous peu un courriel de confirmation."
					)
				),
				
				// insert
				'insert'=>array(
					'contact'=>array(
						'success'=>"Merci, le message a été envoyé !"
					)
				)
			)
		),
		
		// route
		'route'=>array(
			
			// label
			'label'=>array(
				'contactSubmit'=>'Contact - Soumettre',
				'newsletterSubmit'=>'Infolettre - Soumettre',
			)
		),
		
		// cms
		'@cms'=>array(
			
			// jsonForm
			'jsonForm'=>array(
				'label'=>'Libellé',
				'required'=>'Requis',
				'description'=>'Description',
				'type'=>'Type',
				'choices'=>'Choix de réponse (un par ligne)',
			),
			
			// emailNewsletter
			'emailNewsletter'=>array(
				'label'=>"Inscrit sur l'infolettre"
			),
			
			// hierarchy
			'hierarchy'=>array(
				'noParent'=>'Aucun parent'
			),
			
			// table
			'table'=>array(
				
				// description
				'description'=>array(
					'contact'=>'Archive des messages envoyés via le formulaire de la page de contact',
					'document'=>'Gère les documents',
					'event'=>'Gérer et créer les événements',
					'eventSubmit'=>'Inscription des utilisateurs aux événements',
					'form'=>'Gérer et créer des formulaires',
					'formSubmit'=>'Réponses des usagers aux formulaires',
					'media'=>'Gère les médias comme les photos, vidéos et fichiers.',
					'news'=>'Gère les nouvelles et actualités',
					'page'=>"Pages accessibles via l'application",
					'pageContent'=>"Contenu à afficher dans les différentes pages",
					'poll'=>'Gérer et créer des sondages',
					'pollSubmit'=>'Contient tous les votes aux différents sondages',
					'section'=>"Regroupement de pages")
			),
			
			// col
			'col'=>array(
				
				// description
				'description'=>array(
					
					// *
					'*'=>array(
						'googleMaps'=>'Entrer une adresse complète comme dans Google Maps, utiliser des virgules.',
						'youTube'=>'Entrer une URL YouTube complète',
						'vimeo'=>'Entrer une URL Vimeo complète'
					),
					
					// section
					'section'=>array(
						'page_id'=>'Spécifie la page racine de la section',
						'page_ids'=>'Spécifie les pages de premier niveau de la section'
					),
					
					// page
					'page'=>array(
						'page_id'=>'Spécifie la page parent de la page',
						'pageContent_ids'=>'Spécifie les contenus de la page'
					),
					
					// contact
					'contact'=>array(
						'message'=>'Contenu du message'
					)
				)
			),
			
			// relation
			'relation'=>array(
				
				// jsonForm 
				'jsonForm'=>array(
					'inputText'=>'Champ texte',
					'textarea'=>'Champ texte long',
					'select'=>'Menu de sélection',
					'radio'=>'Boutons radios (un choix)',
					'checkbox'=>'Cases à cocher (multiples choix)'
				)
			),
			
			// tinycme
			'tinymce'=>array(
				'paragraph'=>'Paragraphe',
				'superscript'=>'Exposant',
				'header2'=>'En-tête 2',
				'header3'=>'En-tête 3',
				'header4'=>'En-tête 4',
				'header5'=>'En-tête 5',
				'alignLeft'=>'Aligner à gauche',
				'alignCenter'=>'Aligner au centre',
				'alignRight'=>'Aligner à droite',
				'floatLeft'=>'Flotter à gauche',
				'floatRight'=>'Flotter à droite'
			)
		)
	);
}

// config
Fr::__config();
?>